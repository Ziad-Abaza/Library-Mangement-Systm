<?php

namespace App\Http\Controllers\API;

use App\Models\Book;
use App\Models\Author;
use App\Http\Resources\HomeResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class HomePageController extends Controller
{
    /*
    |------------------------------------------------------
    | Handle the incoming request to fetch data for the homepage
    |------------------------------------------------------
    */
    public function __invoke(Request $request)
    {
        // Generate a unique cache key based on the request URL to ensure unique caching
        $cacheKey = $this->generateCacheKey($request);

        // Store the generated cache key for future invalidation
        $this->storeCacheKey($cacheKey);

        // Try to get data from the cache or fetch from the database if not cached
        $data = Cache::rememberForever($cacheKey, function () use ($request) {

            // Fetch the latest approved books, ordering by the most recent published date
            $latestBooks = Book::with(['category', 'author'])
                ->where('status', 'approved')
                ->orderBy('published_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($book) {
                    // Add cover image URL to each book
                    $book->cover_image_url = $book->getFirstMediaUrl('cover_image');
                    return $book;
                });

            // Fetch the most popular books based on views count
            $popularBooks = Book::with(['category', 'author'])
                ->where('status', 'approved')
                ->orderBy('views_count', 'desc')
                ->take(5)
                ->get()
                ->map(function ($book) {
                    // Add cover image URL to each book
                    $book->cover_image_url = $book->getFirstMediaUrl('cover_image');
                    return $book;
                });

            // Fetch the top-rated books based on average comment rating
            $topRatedBooks = Book::with(['category', 'author'])
                ->where('status', 'approved')
                ->withAvg('comments', 'rating') // Average rating of comments
                ->orderByDesc('comments_avg_rating') // Sort by average rating
                ->take(5)
                ->get()
                ->map(function ($book) {
                    // Add cover image URL to each book
                    $book->cover_image_url = $book->getFirstMediaUrl('cover_image');
                    return $book;
                });

            // Fetch a list of authors with their image URL
            $authors = Author::take(5)->get()->map(function ($author) {
                $author->author_image_url = $author->getFirstMediaUrl('author'); // Add author image URL
                return $author;
            });

            // Search for books based on user input and paginate the results
            $searchBooks = Book::with(['category', 'author'])
                ->where('status', 'approved')
                ->when($request->input('search'), function ($query, $search) {
                    // Apply filters based on search input: title, description, category, or author
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', '%' . $search . '%')
                            ->orWhere('description', 'like', '%' . $search . '%')
                            ->orWhereHas('category', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            })
                            ->orWhereHas('author', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
                    });
                })
                ->paginate(4); // Paginate the results

            // Return the compiled data as an array
            return [
                'latestBooks' => $latestBooks,
                'popularBooks' => $popularBooks,
                'topRatedBooks' => $topRatedBooks,
                'authors' => $authors,
                'searchBooks' => $searchBooks,
            ];
        });

        // Return the data as a JSON response wrapped in a HomeResource
        return response()->json(new HomeResource($data));
    }

    /*
    |------------------------------------------------------
    | Generate a unique cache key for the request
    |------------------------------------------------------
    */
    private function generateCacheKey(Request $request): string
    {
        return 'home_page_' . md5($request->fullUrl()); // Generate a unique hash of the URL
    }

    /*
    |------------------------------------------------------
    | Store cache key for invalidation purposes
    |------------------------------------------------------
    */
    private function storeCacheKey(string $cacheKey): void
    {
        // Get the list of existing cache keys or initialize an empty array
        $keys = Cache::get('home_page_cache_keys', []);

        // If the cache key is not already in the list, add it
        if (!in_array($cacheKey, $keys)) {
            $keys[] = $cacheKey;
            // Store the updated list of keys in the cache forever
            Cache::forever('home_page_cache_keys', $keys);
        }
    }
}
