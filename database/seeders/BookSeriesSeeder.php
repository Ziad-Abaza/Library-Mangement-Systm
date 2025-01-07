<?php

namespace Database\Seeders;

use App\Models\BookSeries;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BookSeries::create([
            'title' => 'Classic Literature',
            'description' => 'A collection of timeless classic books.',
            'user_id' => 3, 
        ]);

        BookSeries::create([
            'title' => 'Science Fiction Series',
            'description' => 'Explore the world of futuristic science fiction.',
            'user_id' => 3,
        ]);

        BookSeries::create([
            'title' => 'Mystery Thrillers',
            'description' => 'A thrilling collection of mystery and suspense novels.',
            'user_id' => 3,
        ]);

        BookSeries::create([
            'title' => 'Historical Fiction',
            'description' => 'Books that take you back in time to historical eras.',
            'user_id' => 3,
        ]);
    }
}
