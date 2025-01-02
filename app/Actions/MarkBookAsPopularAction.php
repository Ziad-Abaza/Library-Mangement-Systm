<?php

namespace App\Actions;

use App\Models\Book;
use App\Models\User;
use App\Notifications\NewOrPopularBookNotification;
use Illuminate\Support\Facades\Log;

class MarkBookAsPopularAction
{
    /**
     * Mark a book as popular and notify users.
     *
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function markBookAsPopular(Book $book)
    {
        try {
            // Validate the book object
            if (!$book || !$book->exists) {
                return response()->json(['error' => 'Invalid book provided.'], 400);
            }

            // Check if the book meets the popularity criteria
            if ($book->views_count > 1000 || $book->downloads_count > 500) {
                // Fetch all users (or filter by criteria, e.g., active users)
                $users = User::where('is_active', true)->get();

                if ($users->isEmpty()) {
                    Log::warning('No active users found for notifications.');
                    return response()->json(['warning' => 'No users to notify.'], 204);
                }

                // Send notifications to all selected users
                foreach ($users as $user) {
                    $user->notify(new NewOrPopularBookNotification($book, 'popular'));
                }

                // Log successful notification
                Log::info('Popular book notifications sent.', ['book_id' => $book->id]);

                return response()->json(['message' => 'Popular book notification sent to users.']);
            }

            return response()->json(['message' => 'The book does not meet the popularity criteria.'], 200);
        } catch (\Throwable $e) {
            // Log the error details
            Log::error('Failed to mark book as popular or send notifications.', [
                'error' => $e->getMessage(),
                'book_id' => $book->id ?? null,
            ]);

            return response()->json([
                'error' => 'An unexpected error occurred while marking the book as popular.'
            ], 500);
        }
    }
}
