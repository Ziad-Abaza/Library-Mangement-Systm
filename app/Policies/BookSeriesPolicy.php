<?php

namespace App\Policies;

use App\Models\BookSeries;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookSeriesPolicy
{
    use HandlesAuthorization;

    /*
    |------------------------------------------------------
    | Determine if the user can update a book series.
    |------------------------------------------------------
    */
    public function update(User $user, BookSeries $bookSeries)
    {
        // Allow if the user owns the book series or has a privileged role (admin or superAdmin).
        return $user->id === $bookSeries->user_id || $user->hasRole('superAdmin') || $user->hasRole('admin');
    }

    /*
    |------------------------------------------------------
    | Determine if the user can delete a book series.
    |------------------------------------------------------
    */
    public function delete(User $user, BookSeries $bookSeries)
    {
        // Allow if the user owns the book series or has a privileged role (admin or superAdmin).
        return $user->id === $bookSeries->user_id || $user->hasRole('superAdmin') || $user->hasRole('admin');
    }
}
