<?php

namespace App\Providers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Observers\AuthorObserver;
use App\Observers\BookObserver;
use App\Observers\CategoryObserver;
use Illuminate\Support\ServiceProvider;

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
        Book::observe(BookObserver::class);
        Category::observe(CategoryObserver::class);
        Author::observe(AuthorObserver::class);
    }
}
