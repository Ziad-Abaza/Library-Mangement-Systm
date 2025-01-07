<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DownloadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'book_title' => $this->book->title,
            'downloaded_at' => $this->created_at->format('Y-m-d H:i:s'),
            'book_details_url' => route('books.show', $this->book->id), // Link to book details
        ];
    }
}
