<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
     public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            RolesSeeder::class,
            PermissionsSeeder::class,
            RoleUserSeeder::class,
            PermissionRoleSeeder::class,
            CategoryGroupsSeeder::class,
            CategorySeeder::class,
            AuthorSeeder::class,
            BookSeriesSeeder::class,
            BooksSeeder::class,
            CommentsSeeder::class,
            KeywordsSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
