<?php

namespace App\Observers;

use App\Models\Author;
use Illuminate\Support\Facades\Cache;

class AuthorObserver
{
    /*
    |------------------------------------------------------
    | Handle "updated" Event
    |------------------------------------------------------
    | Triggered when an author is updated. Clears related cache.
    */
    public function updated(Author $author)
    {
        $this->clearCache($author);
    }

    /*
    |------------------------------------------------------
    | Handle "deleted" Event
    |------------------------------------------------------
    | Triggered when an author is deleted. Clears related cache.
    */
    public function deleted(Author $author)
    {
        $this->clearCache($author);
    }

    /*
    |------------------------------------------------------
    | Clear Author Cache
    |------------------------------------------------------
    | Clears cache entries for all books associated with the given author.
    */
    protected function clearCache(Author $author)
    {
        // Clear cache for all books by this author
        foreach ($author->books as $book) {
            Cache::forget('book_' . $book->id . '_author');
        }
    }
}
