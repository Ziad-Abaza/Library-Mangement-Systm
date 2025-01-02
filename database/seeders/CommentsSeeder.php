<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;

class CommentsSeeder extends Seeder
{
    public function run(): void
    {
        Comment::create([
            'content' => 'A masterpiece! Loved every page of it.',
            'rating' => 5,
            'User_id' => 1, 
            'Book_id' => 1,  
        ]);

        Comment::create([
            'content' => 'A thought-provoking novel.',
            'rating' => 4,
            'User_id' => 2, 
            'Book_id' => 2, 
        ]);

        Comment::create([
            'content' => 'A timeless classic.',
            'rating' => 5,
            'User_id' => 3,  
            'Book_id' => 3,  
        ]);
    }
}
