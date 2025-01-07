<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BooksSeeder extends Seeder
{
    public function run(): void
    {
        Book::create([
            'title' => 'The Great Gatsby',
            'description' => 'A novel by F. Scott Fitzgerald',
            'lang' => 'English',
            'downloads_count' => 180,
            'views_count'  => 727,
            'published_at' => now()->subYears(99), // 1925
            'number_pages' => 218,
            'size' => 1.5,
            'edition_number' => '1st',
            'publisher_name' => 'Scribner',
            'status' => 'approved',
            'category_id' => 1,
            'author_id' => 1,
            'user_id'  => 3,
            'book_series_id' => null,
        ]);

        Book::create([
            'title' => '1984',
            'description' => 'A novel by George Orwell',
            'lang' => 'English',
            'downloads_count' => 250,
            'views_count'  => 1000,
            'published_at' => now()->subYears(75), // 1949
            'number_pages' => 328,
            'size' => 1.2,
            'edition_number' => '1st',
            'publisher_name' => 'Secker & Warburg',
            'status' => 'approved',
            'category_id' => 2,
            'author_id' => 2,
            'user_id'  => 3,
            'book_series_id' => null,
        ]);

        Book::create([
            'title' => 'To Kill a Mockingbird',
            'description' => 'A novel by Harper Lee',
            'lang' => 'English',
            'downloads_count' => 300,
            'views_count'  => 1200,
            'published_at' => now()->subYears(61), // 1960
            'number_pages' => 281,
            'size' => 1.4,
            'edition_number' => '1st',
            'publisher_name' => 'J.B. Lippincott & Co.',
            'status' => 'approved',
            'category_id' => 1,
            'author_id' => 3,
            'user_id'  => 3,
            'book_series_id' => null,
        ]);

        Book::create([
            'title' => 'Pride and Prejudice',
            'description' => 'A novel by Jane Austen',
            'lang' => 'English',
            'downloads_count' => 150,
            'views_count'  => 900,
            'published_at' => now()->subYears(209), // 1813
            'number_pages' => 279,
            'size' => 1.1,
            'edition_number' => '1st',
            'publisher_name' => 'T. Egerton',
            'status' => 'approved',
            'category_id' => 3,
            'author_id' => 1,
            'user_id'  => 3,
            'book_series_id' => null,
        ]);

        Book::create([
            'title' => 'Moby Dick',
            'description' => 'A novel by Herman Melville',
            'lang' => 'English',
            'downloads_count' => 120,
            'views_count'  => 500,
            'published_at' => now()->subYears(172), // 1851
            'number_pages' => 585,
            'size' => 1.9,
            'edition_number' => '1st',
            'publisher_name' => 'Harper & Brothers',
            'status' => 'approved',
            'category_id' => 3,
            'author_id' => 1,
            'user_id'  => 3,
            'book_series_id' => null,
        ]);

        Book::create([
            'title' => 'War and Peace',
            'description' => 'A novel by Leo Tolstoy',
            'lang' => 'English',
            'downloads_count' => 200,
            'views_count'  => 800,
            'published_at' => now()->subYears(158), // 1869
            'number_pages' => 1225,
            'size' => 2.5,
            'edition_number' => '1st',
            'publisher_name' => 'The Russian Messenger',
            'status' => 'approved',
            'category_id' => 4,
            'author_id' => 2,
            'user_id'  => 3,
            'book_series_id' => null,
        ]);

        Book::create([
            'title' => 'The Catcher in the Rye',
            'description' => 'A novel by J.D. Salinger',
            'lang' => 'English',
            'downloads_count' => 400,
            'views_count'  => 1500,
            'published_at' => now()->subYears(71), // 1951
            'number_pages' => 277,
            'size' => 1.3,
            'edition_number' => '1st',
            'publisher_name' => 'Little, Brown and Company',
            'status' => 'approved',
            'category_id' => 1,
            'author_id' => 3,
            'user_id'  => 3,
            'book_series_id' => null,
        ]);
    }
}
