<?php

namespace App\Http\Controllers\API;

use App\Jobs\DownloadBookPdf;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Smalot\PdfParser\Parser;
use App\Actions\PublishNewBookAction;
use App\Actions\MarkBookAsPopularAction;
use App\Notifications\PublicationNotification;

class BookController extends Controller
{
    protected $environment;

    /*
    |------------------------------------------------------
    | Constructor to handle authorization based on environment
    |------------------------------------------------------
    */
    public function __construct()
    {
        $this->environment = env('DEV_ENVIRONMENT', false); 
        // if ($this->environment) {
            Auth::loginUsingId(1); // Auto-login for development
        // }
    }

    /*
    |--------------------------------
    |> Fetch and filter book list with caching
    |--------------------------------
    */
    public function index()
    {
        // Get the search query, sort option, and pagination size from the request
        $search = request('search');
        $sort = request('sort'); 
        $paginationSize = request('per_page', 10); // Default pagination size is 10 if not provided

        // Initialize the query to fetch books with their related category and author, filtered by 'approved' status
        $booksQuery = Book::with(['category', 'author'])->where('status', 'approved');

        // Apply search filters if a search term is provided
        if ($search) {
            $booksQuery->where(function ($query) use ($search) {
                // Search for books by title, author name, or category name
                $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhereHas('author', function ($authorQuery) use ($search) {
                        $authorQuery->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Apply sorting based on the 'sort' parameter
        if ($sort === 'highest_rated') {
            $booksQuery->orderBy('rating', 'desc'); // Sort by highest rating
        } elseif ($sort === 'most_viewed') {
            $booksQuery->orderBy('views_count', 'desc'); // Sort by most viewed
        } elseif ($sort === 'latest') {
            $booksQuery->orderBy('published_at', 'desc'); // Sort by latest published
        }

        // Generate a cache key based on search, sort, and pagination size for caching the results
        $cacheKey = 'books_list_' . md5("search={$search}&sort={$sort}&paginationSize={$paginationSize}");
        
        // Check cache for the results and paginate the books
        $books = Cache::remember($cacheKey, 60, function () use ($booksQuery, $paginationSize) {
            return $booksQuery->paginate($paginationSize);
        });

        // Return paginated books as a collection of BookResources
        return BookResource::collection($books);
    }

    /*
    |--------------------------------
    |> Store new book entry and handle uploads
    |--------------------------------
    */
    public function store(Request $request)
    {
        try {
            // Ensure the user has permission to create a book (unless in a special environment)
            if (!$this->environment) {
                // $this->authorize('create', Book::class); // Authorization check
            }

            // Validate incoming data to ensure the book details are correct
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'file' => 'required|file|mimes:pdf', // File must be a PDF
                'edition_number' => 'nullable|string',
                'lang' => 'required|string|max:10',
                'published_at' => 'nullable|date',
                'publisher_name' => 'required|string|max:255',
                'copyright_image' => 'required|file|image|mimes:jpg,png,jpeg', // Copyright image must be a valid image file
                'cover_image' => 'nullable|image|mimes:jpg,jpeg,png', 
                'keywords' => 'nullable|array',
                'keywords.*' => 'exists:keywords,id', // Keywords must exist in the keywords table
                'category_id' => 'required|exists:categories,id', // Category must exist in the categories table
                'author_id' => 'required|exists:authors,id', // Author must exist in the authors table
                'book_series_id' => 'nullable|exists:book_series,id', 
            ]);

            // Add the current user ID and set the status to 'pending' for new books
            $validatedData['user_id']  = auth()->id();
            $validatedData['status']  = "pending";

            // Create a new book entry in the database
            $book = Book::create($validatedData);

            // Attach the provided keywords to the book, if any
            if (isset($validatedData['keywords'])) {
                $book->keywords()->attach($validatedData['keywords']);
            }

            // Check if the book has an uploaded file (PDF)
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Calculate file size in MB and add to validated data
                $sizeInMB = $file->getSize() / (1024 * 1024);
                $validatedData['size'] = round($sizeInMB, 2);

                // Parse the PDF file to count the number of pages
                $pdfParser = new Parser();
                $pdf = $pdfParser->parseFile($file->getRealPath());
                $numberOfPages = count($pdf->getPages());

                // Update the book with the number of pages and file size
                $book->update([
                    'number_pages' => $numberOfPages,
                    'size' => $validatedData['size']
                ]);

                // Handle the media uploads (e.g., cover images, copyright images)
                $this->handleMediaUploads($request, $book);
            }

            // Clear any relevant cache that might be affected by the new book
            $this->clearCache();

            // Notify the book's user (creator) about the publication
            $book->user->notify(new PublicationNotification($book));

            // Return a success message
            return response()->json(['message' => 'Book created successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Return error response if book creation fails
            return response()->json(['error' => 'Failed to create book: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /*
    |--------------------------------
    |> Retrieve a specific book by ID, cache and track views
    |--------------------------------
    */
    public function show($id)
    {
        try {
            // Define cache key and session key to track views
            $cacheKey = "book_{$id}";
            $sessionKey = "viewed_books_{$id}";

            // Retrieve book from cache, or query the database if not cached
            $book = Cache::rememberForever($cacheKey, function () use ($id) {
                return Book::with(['category', 'author', 'comments.user', 'bookSeries'])
                    ->where('status', 'approved')
                    ->find($id); // Find book by ID
            });

            // If the book is not found, return a not found response
            if (!$book) {
                return response()->json(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
            }

            // Increment the views count only if the user hasn't viewed the book yet in this session
            if (!session()->has($sessionKey)) {
                $book->increment('views_count');
                session()->put($sessionKey, true); // Mark as viewed in the session
            }

            return new BookResource($book); // Return the book as a resource

        } catch (\Exception $e) {
            // Handle errors and return an error message
            return response()->json(['error' => 'Error fetching book: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /*
    |--------------------------------
    |> Update book information and handle media files
    |--------------------------------
    */
    public function update(Request $request, Book $book)
    {
        try {
            // Authorize the update if not in the specified environment
            if (!$this->environment) {
                $this->authorize('update', $book);
            }

            // Validate the incoming request data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'file' => 'nullable|file|mimes:pdf',
                'edition_number' => 'nullable|string',
                'lang' => 'required|string|max:10',
                'published_at' => 'nullable|date',
                'publisher_name' => 'required|string|max:255',
                'copyright_image' => 'required|file|image|mimes:jpg,png,jpeg',
                'cover_image' => 'nullable|image|mimes:jpg,jpeg,png',
                'keywords' => 'nullable|array',
                'keywords.*' => 'exists:keywords,id',
                'category_id' => 'required|exists:categories,id',
                'author_id' => 'required|exists:authors,id',
                'book_series_id' => 'nullable|exists:book_series,id',
            ]);

            // Add user ID and set the book status to pending
            $validatedData['user_id'] = auth()->id();
            $validatedData['status'] = "pending";

            // Update the book with validated data
            $book->update($validatedData);

            // Sync keywords if provided
            if (isset($validatedData['keywords'])) {
                $book->keywords()->sync($validatedData['keywords']);
            }

            // If a new file is uploaded, process it
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $sizeInMB = $file->getSize() / (1024 * 1024); // Convert file size to MB
                $validatedData['size'] = round($sizeInMB, 2);

                // Parse the PDF to get the number of pages
                $pdfParser = new Parser();
                $pdf = $pdfParser->parseFile($file->getRealPath());
                $numberOfPages = count($pdf->getPages());

                // Update book with file details
                $book->update([
                    'number_pages' => $numberOfPages,
                    'size' => $validatedData['size']
                ]);
            }

            // Handle media uploads (cover, copyright image, etc.)
            $this->handleMediaUploads($request, $book);

            // Clear the cache for the book
            $this->clearCache($book);

            // Notify the user about the publication update
            $book->user->notify(new PublicationNotification($book));

            return response()->json(['message' => 'Book updated successfully'], Response::HTTP_OK); // Return success response
        } catch (\Exception $e) {
            // Handle errors and return an error message
            return response()->json(['error' => 'Failed to update book: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /*
    |--------------------------------
    |> Approve or reject book publication
    |--------------------------------
    */
    public function approve(Request $request, Book $book)
    {
        // Authorize action if not in the specified environment
        if (!$this->environment) {
            $this->authorize('managePublications', Book::class);
        }

        // Validate the status to be either approved or rejected
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        // If rejected, delete the book
        if($request->status === 'rejected'){
            $this->destroy($book); // Delete the book if rejected
        } else {
            // Otherwise, update the book status
            $book->update(['status' => $request->status]);

            // Perform any necessary actions for publishing the book
            $publishBookAction = new PublishNewBookAction();
            $publishBookAction->publishNewBook($book);
        }

        // Notify the user about the publication status update
        $book->user->notify(new PublicationNotification($book));

        return response()->json(['message' => 'Book status updated successfully.'], Response::HTTP_OK); // Return success response
    }

    /*
    |--------------------------------
    |> Delete a book and clear related media files
    |--------------------------------
    */
    public function destroy(Book $book)
    {
        try {
            // Authorize the delete action if not in the specified environment
            if (!$this->environment) {
                // $this->authorize('delete', $book);
            }

            // Clear the book's media collections (cover image, file, copyright image)
            $book->clearMediaCollection('cover_image');
            $book->clearMediaCollection('file');
            $book->clearMediaCollection('copyright_image');

            // Delete the book record from the database
            $book->delete();

            // Clear the book cache
            $this->clearCache($book);

            return response()->json(['message' => 'Book deleted successfully'],  Response::HTTP_OK); // Return success response
        } catch (\Exception $e) {
            // Handle errors and return an error message
            return response()->json(['error' => 'Failed to delete book: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /*
    |--------------------------------
    |> List books pending approval
    |--------------------------------
    */
    public function pendingApproval()
    {
        // Check if the environment allows the action (usually for testing)
        if (!$this->environment) {
            // Authorize the user to manage publications
            $this->authorize('managePublications', Book::class);
        }

        try {
            // Determine the user ID (use ID 1 for testing, else use logged-in user)
            $userId = $this->environment ? 1 : Auth::id();
            $user = User::find($userId);

            // Check if the user has permission to manage publications
            if (!$user->hasPermission('manage-publications')) {
                return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
            }

            // Fetch books with 'pending' status and include category and author relationships
            $books = Book::with(['category', 'author'])
                        ->where('status', 'pending')
                        ->paginate(10);  // Paginate the result with 10 books per page

            return BookResource::collection($books);  // Return books wrapped in a resource collection
        } catch (\Exception) {
            // Catch any exception and return an error message
            return response()->json(['error' => 'Failed to retrieve books'], Response::HTTP_BAD_REQUEST);
        }
    }

    /*
    |--------------------------------
    |> Manage media uploads for book resources
    |--------------------------------
    */
    private function handleMediaUploads($request, Book $book)
    {
        // Check if a cover image is uploaded and handle it
        if ($request->hasFile('cover_image')) {
            $book->clearMediaCollection('cover_image');  // Clear existing cover image
            $book->addMedia($request->file('cover_image'))->toMediaCollection('cover_image');  // Upload the new cover image
        }

        // Check if a file (book file) is uploaded and handle it
        if ($request->hasFile('file')) {
            $book->clearMediaCollection('file');  // Clear existing file
            $book->addMedia($request->file('file'))->toMediaCollection('file');  // Upload the new file
        }

        // Check if a copyright image is uploaded and handle it
        if ($request->hasFile('copyright_image')) {
            $book->clearMediaCollection('copyright_image');  // Clear existing copyright image
            $book->addMedia($request->file('copyright_image'))->toMediaCollection('copyright_image');  // Upload the new copyright image
        }
    }

    /*
    |--------------------------------
    |> Clear cached entries related to books
    |--------------------------------
    */
    private function clearCache(Book $book = null)
    {
        // Clear cache for the list of books
        Cache::forget('books_list');

        // If a specific book is provided, clear its cached data
        if ($book) {
            Cache::forget("book_{$book->id}");
        }
    }

    /*
    |--------------------------------
    |> Dispatch a job to download the book PDF
    |--------------------------------
    */
    public function download(Book $book)
        {
            try {
                // Ensure the book file exists before proceeding
                $filePath = $book->getFirstMediaPath('file');
                if (!$filePath || !file_exists($filePath)) {
                    return response()->json(['error' => 'The book file does not exist.'], Response::HTTP_NOT_FOUND);
                }
                $userId = Auth::check() ? Auth::id() : null;

                // Dispatch a job to handle the download, passing the user ID
                DownloadBookPdf::dispatch($book, $userId);

                // Mark the book as popular if it meets criteria
                (new MarkBookAsPopularAction())->markBookAsPopular($book);

                // Respond to the user
                return response()->json([
                    'message' => 'Your download is being processed and will start shortly.'
                ], Response::HTTP_ACCEPTED);

            } catch (\Throwable $e) {
                return response()->json([
                    'error' => 'An unexpected error occurred while processing your request.'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
}
