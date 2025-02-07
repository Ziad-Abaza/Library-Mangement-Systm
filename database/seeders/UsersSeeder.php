<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'mustafa elsheshy',
            'email' => 'mustafa.elsheshy@gmail.com',
            'password' => bcrypt('mustafa7258'),
            'is_active' => true,
            'token' => Str::random(60),
            'token_expiration' => now()->addDays(7),
        ]);
/*
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
            'is_active' => true,
            'token' => Str::random(60),
            'token_expiration' => now()->addDays(7),
        ]);

        User::create([
            'name' => 'Michael Johnson',
            'email' => 'michael@example.com',
            'password' => bcrypt('password123'),
            'is_active' => false,
            'token' => Str::random(60),
            'token_expiration' => now()->addDays(7),
        ]);
*/
    }
}
