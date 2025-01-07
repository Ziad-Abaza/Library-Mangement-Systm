<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $averageRating = $this->comments()->avg('rating'); 

        $formattedRating = $averageRating ? number_format($averageRating, 1) : null; 

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'file' => $this->getFirstMediaUrl('file'),
            'edition_number' => $this->edition_number,
            'lang' => $this->lang,
            'published_at' => $this->published_at ? Carbon::parse($this->published_at)->translatedFormat('d F Y') : null,
            'publisher_name' => $this->publisher_name,
            'downloads_count' => $this->downloads_count,
            'keywords' => $this->keywords->pluck('name'),
            'copyright_image' => $this->getFirstMediaUrl('copyright_image'),
            'cover_image' => $this->getFirstMediaUrl('cover_image'), 
            'category' => new CategoryResource($this->whenLoaded('category')), 
            'author' => new AuthorResource($this->whenLoaded('author')), 
            'number_pages' => $this->number_pages,
            'size' => $this->size,
            'views_count' => $this->views_count,
            'created_at' => $this->created_at,
            'average_rating' => $formattedRating, 
        ];
    }
}
