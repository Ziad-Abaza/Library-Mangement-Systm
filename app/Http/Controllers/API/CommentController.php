<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $environment;

    /*
    |------------------------------------------------------
    | Constructor to handle authorization based on environment.
    |------------------------------------------------------
    */
    public function __construct()
    {
        $this->environment = env('DEV_ENVIRONMENT', false); 
        // If in development environment, auto-login user with ID 1
        if ($this->environment) {
            Auth::loginUsingId(1); // Auto-login for development
        }
    }

    /*
    |------------------------------------------------------
    | Show all comments for a specific book.
    |------------------------------------------------------
    */
    public function index($bookId)
    {
        // Fetch the book by ID
        $book = Book::findOrFail($bookId);

        // Get all approved comments (status 1) for the book
        $comments = $book->comments()->where('status', 1)->get();

        // Return the comments as a collection of resources
        return CommentResource::collection($comments);
    }

    /*
    |------------------------------------------------------
    | Store a new comment for a specific book.
    |------------------------------------------------------
    */
    public function store(Request $request, $bookId)
    {
        // Validate the request data
        $validated = $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        // Find the book by ID
        $book = Book::findOrFail($bookId);

        // Determine the user ID (auto-login for dev environment, otherwise use authenticated user)
        $userId = $this->environment ? 1 : Auth::id(); 

        // Create a new comment associated with the book
        $comment = $book->comments()->create([
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'user_id' => $userId,
            'status' => 1, // Set status as approved
        ]);

        // Return the newly created comment as a resource
        return new CommentResource($comment);
    }

    /*
    |------------------------------------------------------
    | Update an existing comment.
    |------------------------------------------------------
    */
    public function update(Request $request, $bookId, $commentId)
    {
        // Validate the request data
        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'rating' => 'required|integer|between:1,5',
        ]);

        // Determine the user ID (auto-login for dev environment, otherwise use authenticated user)
        $userId = $this->environment ? 1 : Auth::id(); 

        // Find the comment by book ID, comment ID, and user ID
        $comment = Comment::where('book_id', $bookId)
            ->where('id', $commentId)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Update the comment with the validated data
        $comment->update($validated);

        // Return the updated comment as a resource
        return new CommentResource($comment);
    }

    /*
    |------------------------------------------------------
    | Delete a comment.
    |------------------------------------------------------
    */
    public function destroy($bookId, $commentId)
    {
        // Determine the user ID (auto-login for dev environment, otherwise use authenticated user)
        $userId = $this->environment ? 1 : Auth::id();

        // Find the comment by book ID, comment ID, and user ID
        $comment = Comment::where('book_id', $bookId)
            ->where('id', $commentId)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Delete the comment
        $comment->delete();

        // Return a success response indicating the comment has been deleted
        return response()->json(['message' => 'Comment deleted successfully.']);
    }
}
