<?php

namespace App\Services;

use Mpdf\Mpdf;
use Illuminate\Support\Facades\Blade;

class PdfDownloadService
{
    /*
    |------------------------------------------------------
    | PDF Configuration
    |------------------------------------------------------
    | This array holds the configuration for generating the PDF file.
    | It includes font, encoding, format, font size, and orientation settings.
    */
    protected static array $pdfConfig = [
        'default_font' => 'Cairo', // Default font used in the PDF
        'mode' => 'utf-8',         // Character encoding mode
        'format' => 'A4',          // Paper format
        'default_font_size' => 12, // Default font size in the PDF
        'orientation' => 'P',      // Page orientation (Portrait)
    ];

    /*
    |------------------------------------------------------
    | Generate PDF from Blade View
    |------------------------------------------------------
    | This method generates a PDF from a Blade view, taking in the 
    | view path and data to render the content dynamically.
    */
    protected static function generatePdf(string $viewPath, array $data): Mpdf
    {
        $mpdf = new Mpdf(self::$pdfConfig); // Create a new mPDF instance with the config
        $html = Blade::render($viewPath, $data); // Render the Blade view with the provided data
        $mpdf->WriteHTML($html); // Write the rendered HTML to the PDF

        return $mpdf; // Return the generated PDF instance
    }

    /*
    |------------------------------------------------------
    | Stream PDF for Download
    |------------------------------------------------------
    | This method streams the generated PDF to the browser for download.
    | It takes in the Blade view path, file name, and data for rendering.
    */
    public static function streamPdfDownload(string $viewPath, string $fileName, array $data): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return response()->streamDownload(function () use ($viewPath, $data) {
            $mpdf = self::generatePdf($viewPath, $data); // Generate the PDF using the given view and data
            $mpdf->Output(); // Output the PDF content
        }, "{$fileName}.pdf"); // Specify the file name with .pdf extension
    }
}
