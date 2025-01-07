<?php

namespace App\Jobs;

use App\Models\Book;
use Smalot\PdfParser\Parser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadBookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $book;
    protected $file;

    public function __construct(Book $book, $file)
    {
        $this->book = $book;
        $this->file = $file;
    }

    public function handle()
    {
        // Parse the PDF to get the number of pages
        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile($this->file->getRealPath());
        $numberOfPages = count($pdf->getPages());

        // Calculate the file size in MB
        $sizeInMB = $this->file->getSize() / (1024 * 1024);
        
        // Store the file in the 'books' directory
        $filePath = $this->file->store('books', 'public');

        // Update the book record with the file details
        $this->book->update([
            'file_path' => $filePath,
            'number_pages' => $numberOfPages,
            'size' => round($sizeInMB, 2)
        ]);
    }
}
