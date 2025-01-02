<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'create-user', 'description' => 'Create new users'],
            ['name' => 'edit-user', 'description' => 'Edit existing users'],
            ['name' => 'delete-user', 'description' => 'Delete users'],
            ['name' => 'view-users', 'description' => 'View all users'],
            
            ['name' => 'create-role', 'description' => 'Create new roles'],
            ['name' => 'edit-role', 'description' => 'Edit existing roles'],
            ['name' => 'delete-role', 'description' => 'Delete roles'],
            ['name' => 'view-roles', 'description' => 'View all roles'],
            
            ['name' => 'create-permission', 'description' => 'Create new permissions'],
            ['name' => 'edit-permission', 'description' => 'Edit existing permissions'],
            ['name' => 'delete-permission', 'description' => 'Delete permissions'],
            ['name' => 'view-permissions', 'description' => 'View all permissions'],
            
            ['name' => 'create-book', 'description' => 'Create new books'],
            ['name' => 'edit-book', 'description' => 'Edit existing books'],
            ['name' => 'delete-book', 'description' => 'Delete books'],
            ['name' => 'view-books', 'description' => 'View all books'],
            
            ['name' => 'create-category', 'description' => 'Create new categories'],
            ['name' => 'edit-category', 'description' => 'Edit existing categories'],
            ['name' => 'delete-category', 'description' => 'Delete categories'],
            ['name' => 'view-categories', 'description' => 'View all categories'],

            ['name' => 'manage-publications', 'description' => 'Manage all publication requests'],

            ['name' => 'manage-author', 'description' => 'Manage all author requests'],
            
            ['name' => 'create-comment', 'description' => 'Create comments on books'],
            ['name' => 'edit-comment', 'description' => 'Edit existing comments'],
            ['name' => 'delete-comment', 'description' => 'Delete comments'],
            ['name' => 'view-comments', 'description' => 'View all comments'],
            
            ['name' => 'create-notification', 'description' => 'Create notifications'],
            ['name' => 'edit-notification', 'description' => 'Edit notifications'],
            ['name' => 'delete-notification', 'description' => 'Delete notifications'],
            ['name' => 'view-notifications', 'description' => 'View all notifications'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
