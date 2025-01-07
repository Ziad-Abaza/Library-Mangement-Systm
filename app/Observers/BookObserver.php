<?php

namespace App\Observers;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;

class BookObserver
{
    /*
    |------------------------------------------------------
    | Handle "created" Event
    |------------------------------------------------------
    | Triggered when a new book is created. Clears related cache.
    */
    public function created(Book $book)
    {
        $this->clearCache($book);
    }

    /*
    |------------------------------------------------------
    | Handle "updated" Event
    |------------------------------------------------------
    | Triggered when a book is updated. Clears related cache.
    */
    public function updated(Book $book)
    {
        $this->clearCache($book);
    }

    /*
    |------------------------------------------------------
    | Handle "deleted" Event
    |------------------------------------------------------
    | Triggered when a book is deleted. Clears related cache.
    */
    public function deleted(Book $book)
    {
        $this->clearCache($book);
    }

    /*
    |------------------------------------------------------
    | Clear Book Cache
    |------------------------------------------------------
    | Clears specific cache entries related to the given book.
    */
    protected function clearCache(Book $book)
    {
        Cache::forget('file' . $book->id);
        Cache::forget('file_extension_' . $book->id);
        Cache::forget('cover_image' . $book->id);
        Cache::forget('book_' . $book->id . '_category');
        Cache::forget('book_' . $book->id . '_author');
    }
}
