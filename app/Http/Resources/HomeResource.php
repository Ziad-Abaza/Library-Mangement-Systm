<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'latestBooks' => $this->resource['latestBooks']->map(function ($book) {
                $averageRating = $book->comments()->avg('rating');
                $formattedRating = $averageRating ? number_format($averageRating, 1) : null;

                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'description' => $book->description,
                    'cover_image' => $book->cover_image,
                    'published_at' => $book->published_at,
                    'author' => $book->author,
                    'category' => $book->category,
                    'average_rating' => $formattedRating,
                ];
            }),
            'popularBooks' => $this->resource['popularBooks']->map(function ($book) {
                $averageRating = $book->comments()->avg('rating');
                $formattedRating = $averageRating ? number_format($averageRating, 1) : null;

                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'description' => $book->description,
                    'cover_image' => $book->cover_image,
                    'views_count' => $book->views_count,
                    'author' => $book->author,
                    'category' => $book->category,
                    'average_rating' => $formattedRating,
                ];
            }),
            'topRatedBooks' => $this->resource['topRatedBooks']->map(function ($book) {
                $averageRating = $book->comments()->avg('rating');
                $formattedRating = $averageRating ? number_format($averageRating, 1) : null;

                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'description' => $book->description,
                    'cover_image' => $book->cover_image,
                    'average_rating' => $formattedRating,
                    'author' => $book->author,
                    'category' => $book->category,
                ];
            }),
            'authors' => $this->resource['authors']->map(function ($author) {
                return [
                    'id' => $author->id,
                    'name' => $author->name,
                    'biography' => $author->biography,
                    'birthdate' => $author->birthdate,
                    'profile_image' => $author->profile_image,
                ];
            }),
            'searchBooks' => $this->resource['searchBooks'],
        ];
    }
}
