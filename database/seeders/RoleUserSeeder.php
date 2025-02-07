<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::find(1);
        $roleAdmin = Role::find(1); 
        $user1->roles()->attach($roleAdmin);
    }
}
