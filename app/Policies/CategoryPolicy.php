<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    /*
    |------------------------------------------------------
    | Determine if the user can view any category.
    |------------------------------------------------------
    */
    public function viewAny(User $user)
    {
        // Allow viewing any category
        return true;
    }

    /*
    |------------------------------------------------------
    | Determine if the user can view a specific category.
    |------------------------------------------------------
    */
    public function view(User $user, Category $category)
    {
        // Allow viewing the category
        return true;
    }

    /*
    |------------------------------------------------------
    | Determine if the user can create a new category.
    |------------------------------------------------------
    */
    public function create(User $user)
    {
        // Allow if the user has the 'create-category' permission
        return $user->hasPermission('create-category');
    }

    /*
    |------------------------------------------------------
    | Determine if the user can update the category.
    |------------------------------------------------------
    */
    public function update(User $user, Category $category)
    {
        // Allow if the user has the 'edit-category' permission
        return $user->hasPermission('edit-category');
    }

    /*
    |------------------------------------------------------
    | Determine if the user can delete the category.
    |------------------------------------------------------
    */
    public function delete(User $user, Category $category)
    {
        // Allow if the user has the 'delete-category' permission
        return $user->hasPermission('delete-category');
    }
}
