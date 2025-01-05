<?php

namespace App\Providers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Observers\AuthorObserver;
use App\Observers\BookObserver;
use App\Observers\CategoryObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!function_exists('symlink')) {
            Storage::macro('link', function ($target, $link) {
                return File::copyDirectory($target, $link);
            });
        }

        Book::observe(BookObserver::class);
        Category::observe(CategoryObserver::class);
        Author::observe(AuthorObserver::class);
    }
}
