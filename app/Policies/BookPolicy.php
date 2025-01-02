<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    /*
    |------------------------------------------------------
    | Determine if the user can view any books.
    |------------------------------------------------------
    */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-books');
    }

    /*
    |------------------------------------------------------
    | Determine if the user can view a specific book.
    |------------------------------------------------------
    */
    public function view(User $user, Book $book): bool
    {
        return $user->hasPermission('view-books');
    }

    /*
    |------------------------------------------------------
    | Determine if the user can create books.
    |------------------------------------------------------
    */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-books');
    }

    /*
    |------------------------------------------------------
    | Determine if the user can update a specific book.
    |------------------------------------------------------
    */
    public function update(User $user, Book $book): bool
    {
        // User must have 'update-books' permission and either:
        // - Own the book, or
        // - Have a higher role (e.g., not a regular user).
        return $user->hasPermission('update-books') && ($user->id === $book->user_id || !$user->hasRole('user'));
    }

    /*
    |------------------------------------------------------
    | Determine if the user can delete a specific book.
    |------------------------------------------------------
    */
    public function delete(User $user, Book $book): bool
    {
        // User must have 'delete-books' permission and either:
        // - Own the book, or
        // - Have a higher role (e.g., not a regular user).
        return $user->hasPermission('delete-books') && ($user->id === $book->user_id || !$user->hasRole('user'));
    }

    /*
    |------------------------------------------------------
    | Determine if the user can manage book publications.
    |------------------------------------------------------
    */
    public function managePublications(User $user): bool
    {
        return $user->hasPermission('manage-publications');
    }
}
