<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        Author::create([
            'name' => 'F. Scott Fitzgerald',
            'biography' => 'An American novelist and short story writer.',
            'birthdate' => '1896-09-24',
        ]);

        Author::create([
            'name' => 'George Orwell',
            'biography' => 'An English novelist, essayist, journalist, and critic.',
            'birthdate' => '1903-06-25',
        ]);

        Author::create([
            'name' => 'Harper Lee',
            'biography' => 'An American novelist widely known for "To Kill a Mockingbird".',
            'birthdate' => '1926-04-28',
        ]);
    }
}
