<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Download;

class DownloadPolicy
{
    /*
    |------------------------------------------------------
    | Determine if the user can view the download.
    |------------------------------------------------------
    */
    public function view(User $user, Download $download)
    {
        // Allow viewing if the user is the owner of the download
        return $user->id === $download->user_id;
    }
}
