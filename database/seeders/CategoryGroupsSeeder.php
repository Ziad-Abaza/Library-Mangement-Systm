<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryGroup;

class CategoryGroupsSeeder extends Seeder
{
    public function run(): void
    {
        CategoryGroup::create([
            'name' => 'History',
        ]);

        CategoryGroup::create([
            'name' => 'Geography',
        ]);
    }
}
