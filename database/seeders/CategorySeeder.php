<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryGroup;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'Ancient History',
            'description' => 'Books about ancient civilizations and events',
            'category_group_id' => 1,
        ]);

        Category::create([
            'name' => 'Medieval History',
            'description' => 'Books about the medieval period',
            'category_group_id' => 1,
        ]);

        Category::create([
            'name' => 'Modern History',
            'description' => 'Books about events in modern history',
            'category_group_id' => 1,
        ]);

        Category::create([
            'name' => 'Physical Geography',
            'description' => 'Books about the physical characteristics of Earth',
            'category_group_id' => 2,
        ]);

        Category::create([
            'name' => 'Human Geography',
            'description' => 'Books about human societies and their relation to the Earth',
            'category_group_id' => 2,
        ]);

        Category::create([
            'name' => 'Political Geography',
            'description' => 'Books about the political boundaries and regions of the world',
            'category_group_id' => 2,
        ]);
    }
}
