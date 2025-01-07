<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CategoryGroup;

class CategoryGroupPolicy
{
    /*
    |------------------------------------------------------
    | Determine if the user can view any category group.
    |------------------------------------------------------
    */
    public function viewAny(User $user)
    {
        // Allow viewing any category group
        return true;
    }

    /*
    |------------------------------------------------------
    | Determine if the user can view a specific category group.
    |------------------------------------------------------
    */
    public function view(User $user, CategoryGroup $categoryGroup)
    {
        // Allow viewing the category group
        return true;
    }

    /*
    |------------------------------------------------------
    | Determine if the user can create a new category group.
    |------------------------------------------------------
    */
    public function create(User $user)
    {
        // Allow if the user has the 'create-category' permission
        return $user->hasPermission('create-category');
    }

    /*
    |------------------------------------------------------
    | Determine if the user can update the category group.
    |------------------------------------------------------
    */
    public function update(User $user, CategoryGroup $categoryGroup)
    {
        // Allow if the user has the 'edit-category' permission
        return $user->hasPermission('edit-category');
    }

    /*
    |------------------------------------------------------
    | Determine if the user can delete the category group.
    |------------------------------------------------------
    */
    public function delete(User $user, CategoryGroup $categoryGroup)
    {
        // Allow if the user has the 'delete-category' permission
        return $user->hasPermission('delete-category');
    }
}
