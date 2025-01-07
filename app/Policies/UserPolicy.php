<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /*
    |------------------------------------------------------
    | Determine whether the user can view the model.
    |------------------------------------------------------
    */
    public function view(User $user, User $model)
    {
        // Allow viewing if the user is the same or has higher role level
        if ($user->id === $model->id) {
            return true;
        }

        if ($user->getRoleLevel() > $model->getRoleLevel()) {
            return true;
        }

        // Check if the user has permission to view users
        return $user->hasPermission('view-users');
    }

    /*
    |------------------------------------------------------
    | Determine whether the user can view any user.
    |------------------------------------------------------
    */
    public function viewAny(User $user)
    {
        // Check if the user has permission to view users
        return $user->hasPermission('view-users');
    }

    /*
    |------------------------------------------------------
    | Determine whether the user can update the model.
    |------------------------------------------------------
    */
    public function update(User $user, User $model)
    {
        // Prevent updating if the user is superAdmin or same user
        if ($model->hasRole('superAdmin')) {
            return false;
        }

        if ($user->id === $model->id) {
            return false;
        }

        // Prevent update if the user has lower or equal role level
        if ($user->getRoleLevel() <= $model->getRoleLevel()) {
            return false;
        }

        // Check if the user has permission to edit users
        return $user->hasPermission('edit-user');
    }

    /*
    |------------------------------------------------------
    | Determine whether the user can create a user.
    |------------------------------------------------------
    */
    public function create(User $user)
    {
        // Check if the user has permission to create users
        return $user->hasPermission('create-user');
    }

    /*
    |------------------------------------------------------
    | Determine whether the user can delete the model.
    |------------------------------------------------------
    */
    public function delete(User $user, User $model)
    {
        // Prevent deleting if the user is superAdmin or same user
        if ($model->hasRole('superAdmin')) {
            return false;
        }

        if ($user->id === $model->id) {
            return false;
        }

        // Prevent delete if the user has lower or equal role level
        if ($user->getRoleLevel() <= $model->getRoleLevel()) {
            return false;
        }

        // Check if the user has permission to delete users
        return $user->hasPermission('delete-user');
    }
}
