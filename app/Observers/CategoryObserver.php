<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    /*
    |------------------------------------------------------
    | Handle "updated" Event
    |------------------------------------------------------
    | Triggered when a category is updated. Clears related cache.
    */
    public function updated(Category $category)
    {
        $this->clearCache($category);
    }

    /*
    |------------------------------------------------------
    | Handle "deleted" Event
    |------------------------------------------------------
    | Triggered when a category is deleted. Clears related cache.
    */
    public function deleted(Category $category)
    {
        $this->clearCache($category);
    }

    /*
    |------------------------------------------------------
    | Clear Category Cache
    |------------------------------------------------------
    | Clears specific cache entries for books related to the given category.
    */
    protected function clearCache(Category $category)
    {
        if ($category->books && $category->books->count() > 0) {
            foreach ($category->books as $book) {
                Cache::forget('book_' . $book->id . '_category');
            }
        }
    }
}
