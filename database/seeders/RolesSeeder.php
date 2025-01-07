<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'SuperAdmin',
            'description' => 'Administrator with full access',
            'role_level'  => 5,
        ]);

        Role::create([
            'name' => 'Editor',
            'description' => 'Editor with access to manage content',
            'role_level'  => 3,
        ]);

        Role::create([
            'name' => 'User',
            'description' => 'Regular user with limited access',
            'role_level'  => 1,
        ]);

        Role::create([
            'name' => 'Guest',
            'description' => 'Guest user with minimal access',
            'role_level'  => 1,
        ]);

    }
}
