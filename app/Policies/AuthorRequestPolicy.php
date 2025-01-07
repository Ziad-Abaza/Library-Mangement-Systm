<?php

namespace App\Policies;

use App\Models\AuthorRequest;
use App\Models\User;

class AuthorRequestPolicy
{
    /*
    |------------------------------------------------------
    | Determine if the user can view any author requests.
    |------------------------------------------------------
    */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('superAdmin');
    }

    /*
    |------------------------------------------------------
    | Determine if the user can view a specific author request.
    |------------------------------------------------------
    */
    public function view(User $user, AuthorRequest $authorRequest)
    {
        return $user->hasRole('admin') || $user->hasRole('superAdmin');
    }

    /*
    |------------------------------------------------------
    | Determine if the user can update a specific author request.
    |------------------------------------------------------
    */
    public function update(User $user, AuthorRequest $authorRequest)
    {
        return $user->id === $authorRequest->user_id || $user->hasRole('admin') || $user->hasRole('superAdmin');
    }

    /*
    |------------------------------------------------------
    | Determine if the user can delete a specific author request.
    |------------------------------------------------------
    */
    public function delete(User $user, AuthorRequest $authorRequest)
    {
        return $user->hasRole('admin') || $user->hasRole('superAdmin');
    }

    /*
    |------------------------------------------------------
    | Determine if the user can handle author requests.
    |------------------------------------------------------
    */
    public function handleRequest(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('superAdmin');
    }
}
