<?php

namespace App\Actions;

use App\Models\Book;
use App\Models\User;
use App\Notifications\NewOrPopularBookNotification;

class PublishNewBookAction
{
    /**
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     *
     * Accessing Notifications
     * Users can access the notifications from their profile or dashboard. You can retrieve the notifications like this:
     *
     * $user = auth()->user();
     * $notifications = $user->notifications;
     * Or, to get only unread notifications:
     *
     * $unreadNotifications = $user->unreadNotifications;
     *
     */
    public function publishNewBook(Book $book)
    {
        // Assume this method is called when a new book is published
        // $book->is_approved = true;
        // $book->published_at = now();
        // $book->save();

        // Send notification to all users about the new book
        $users = User::all();  // Or select a specific group of users
        foreach ($users as $user) {
            $user->notify(new NewOrPopularBookNotification($book, 'new'));
        }

        return response()->json(['message' => 'New book published and users notified.']);
    }
}
