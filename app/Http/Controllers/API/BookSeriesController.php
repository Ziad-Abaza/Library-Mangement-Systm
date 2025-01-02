<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookSeriesResource;
use App\Models\BookSeries;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Support\Facades\Cache;

class BookSeriesController extends Controller
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
        if ($this->environment) {
            Auth::loginUsingId(1); // Auto-login for development
        }
    }

    /*
    |------------------------------------------------------
    | List all book series with optional search
    |------------------------------------------------------
    */
    public function index(Request $request)
    {
        try {
            // Generate a cache key based on the full request URL
            $cacheKey = 'book_series_index_' . md5($request->fullUrl());
            
            // Retrieve the list of book series from cache or database
            $bookSeries = Cache::remember($cacheKey, 1200, function () use ($request) {
                $query = BookSeries::query();

                // Apply search filter if provided in the request
                if ($request->filled('search')) {
                    $query->where('title', 'like', '%' . $request->search . '%');
                }

                return $query->with('books')->get();  // Return the series with associated books
            });

            return BookSeriesResource::collection($bookSeries);  // Return the collection of book series wrapped in a resource
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to retrieve book series.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Show a single book series by its ID
    |------------------------------------------------------
    */
    public function show($id)
    {
        try {
            // Generate a cache key for the specific book series
            $cacheKey = 'book_series_show_' . md5($id);
            
            // Retrieve the book series from cache or database
            $bookSeries = Cache::remember($cacheKey, 1200, function () use ($id) {
                return BookSeries::with('books')->findOrFail($id);  // Find the series by ID, or fail if not found
            });

            return new BookSeriesResource($bookSeries);  // Return the book series wrapped in a resource
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book series not found.'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to retrieve book series.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Store a new book series
    |------------------------------------------------------
    */
    public function store(Request $request)
    {
        try {
            // Validate incoming request data
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'required|image',
            ]);

            // Set user ID based on environment or authenticated user
            $userId = $this->environment ? 1 : Auth::id(); 
            $validated['user_id'] = $userId;

            // Create the new book series in the database
            $bookSeries = BookSeries::create($validated);

            // If an image is uploaded, associate it with the book series
            if ($request->hasFile('image')) {
                $bookSeries->addMediaFromRequest('image')->toMediaCollection('book_series');
            }

            return new BookSeriesResource($bookSeries, Response::HTTP_CREATED);  // Return the created book series wrapped in a resource
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to create book series.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Update an existing book series
    |------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        try {
            // Find the book series by ID or fail
            $bookSeries = BookSeries::findOrFail($id);
            
            // Authorize the update action if not in development environment
            if(!$this->environment){
                $this->authorize('update', $bookSeries);
            }

            // Validate incoming request data
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|nullable|string',
                'image' => 'sometimes|nullable|image',
            ]);

            // Update the book series with validated data
            $bookSeries->update($validated);

            // If an image is uploaded, replace the old image
            if ($request->hasFile('image')) {
                $bookSeries->clearMediaCollection('book_series'); 
                $bookSeries->addMediaFromRequest('image')->toMediaCollection('book_series');
            }

            // Clear the cache for the updated book series
            Cache::forget('book_series_show_' . md5($id));

            return new BookSeriesResource($bookSeries);  // Return the updated book series wrapped in a resource
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book series not found.'], Response::HTTP_NOT_FOUND);
        } catch (AccessDeniedHttpException $e) {
            return response()->json(['error' => 'You do not have permission to update this book series.'], Response::HTTP_FORBIDDEN);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to update book series.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Delete an existing book series
    |------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            // Find the book series by ID or fail
            $bookSeries = BookSeries::findOrFail($id);
            
            // Authorize the delete action if not in development environment
            if(!$this->environment){
                $this->authorize('delete', $bookSeries);
            }

            // Delete the book series from the database
            $bookSeries->delete();

            // Clear the cache for the deleted book series
            Cache::forget('book_series_show_' . md5($id));

            return response()->json(['message' => 'Book series deleted successfully'], Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book series not found.'], Response::HTTP_NOT_FOUND);
        } catch (AccessDeniedHttpException $e) {
            return response()->json(['error' => 'You do not have permission to delete this book series.'], Response::HTTP_FORBIDDEN);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to delete book series.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
