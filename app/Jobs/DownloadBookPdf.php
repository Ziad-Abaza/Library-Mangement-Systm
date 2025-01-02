<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\Download;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class DownloadBookPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $book;
    protected $userId;

    /**
     * Constructor to initialize the job with the necessary data.
     *
     * @param Book $book The book to be downloaded.
     * @param int|null $userId The ID of the user downloading the book.
     */
    public function __construct(Book $book, int $userId = null)
    {
        $this->book = $book;
        $this->userId = $userId;
    }

    /**
     * Handle the book download process.
     *
     * @return BinaryFileResponse The response to download the book's PDF file.
     * @throws Exception If the book or its file is not valid or found.
     */
    public function handle(): BinaryFileResponse
    {
        try {
            // Ensure the book exists and is valid
            if (!$this->book || !$this->book->exists) {
                throw new Exception('The specified book is not valid or does not exist.');
            }

            // Retrieve the file URL for the book
            $fileUrl = $this->book->getFirstMediaPath('file');

            // Check if the file exists
            if (!$fileUrl || !file_exists($fileUrl)) {
                throw new Exception('File not found.');
            }

            // Increment the book's download count
            $this->book->increment('downloads_count');

            // Log the download in the database if a user ID is provided
            if ($this->userId) {
                Download::create([
                    'user_id' => $this->userId,
                    'book_id' => $this->book->id,
                ]);
            }

            // Return the file as a downloadable response
            return response()->download($fileUrl, "{$this->book->title}.pdf");

        } catch (Exception $e) {
            // Log the error for debugging purposes
            Log::error('Failed to handle book download', [
                'book_id' => $this->book->id ?? null,
                'error' => $e->getMessage(),
            ]);

            // Return a JSON response indicating an error occurred
            return response()->json([
                'error' => 'An error occurred while processing the download request.'
            ], 500);
        }
    }
}
