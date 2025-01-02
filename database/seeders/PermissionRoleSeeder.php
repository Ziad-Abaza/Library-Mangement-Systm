<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('name', 'SuperAdmin')->first();
        $editor = Role::where('name', 'Editor')->first();
        $user = Role::where('name', 'User')->first();
        $guest = Role::where('name', 'Guest')->first();
        
        $permissions = Permission::all();
        
        $admin->permissions()->attach($permissions->pluck('id'));
        
        $editor->permissions()->attach(
            Permission::whereIn('name', [
                'create_book', 'edit_book', 'delete_book', 'view_books',
                'create_category', 'edit_category', 'view_categories',
                'create_comment', 'edit_comment', 'view_comments',
            ])->pluck('id')
        );
        
        $user->permissions()->attach(
            Permission::whereIn('name', [
                'view_books', 'view_categories', 'create_comment', 'view_comments',
                'download_book', 'view_downloads'
            ])->pluck('id')
        );
        
        $guest->permissions()->attach(
            Permission::whereIn('name', [
                'view_books', 'view_categories'
            ])->pluck('id')
        );
    }
}
