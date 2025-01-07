<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::find(1); // Assuming user with id 1 exists
        $user2 = User::find(2); // Assuming user with id 2 exists

        $roleAdmin = Role::find(1); // Assuming role with id 1 exists (Admin)
        $roleUser = Role::find(3);  // Assuming role with id 3 exists (User)

        $user1->roles()->attach($roleAdmin); // Assign Admin role to user 1
        $user2->roles()->attach($roleUser);  // Assign User role to user 2

    }
}
