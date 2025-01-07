<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryGroupResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Exception;

class CategoryController extends Controller
{
    protected $environment;

    /*
    |------------------------------------------------------
    | Constructor to handle authorization based on environment
    |------------------------------------------------------
    */
    public function __construct()
    {
        $this->environment = env('DEV_ENVIRONMENT', false); // Determine if it's a development environment
        if ($this->environment) {
            Auth::loginUsingId(1); // Auto-login for development purposes
        }
    }

    /*
    |---------------------------------------
    | Get list of categories with search filter and pagination
    |---------------------------------------
    */
    public function index(Request $request)
    {
        try {
            // Authorization check for non-development environment
            // if(!$this->environment){
            //     $this->authorize('viewAny', Category::class); // Ensure user has permission to view categories
            // }

            // Retrieve categories from cache or database if not cached
            $categories = Cache::remember('categories_index_' . md5($request->fullUrl()), 120, function () use ($request) {
                $query = Category::with('categoryGroup'); // Include related category group

                // Apply search filter if provided
                if ($request->filled('search')) {
                    $query->where(function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%') // Search by category name
                          ->orWhereHas('categoryGroup', function ($q) use ($request) {
                              $q->where('name', 'like', '%' . $request->search . '%'); // Search by category group name
                          });
                    });
                }

                // Filter by group ID if provided
                if ($request->filled('group_id')) {
                    $query->where('category_group_id', $request->group_id); // Filter by group ID
                }

                return $query->get(); // Return categories
            });

            // Return paginated categories in API resource format
            return CategoryResource::collection($categories);

        } catch (Exception $e) {
            // Return error response if exception occurs
            return response()->json(['error' => 'Error fetching categories', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Get a single category by ID
    |------------------------------------------------------
    */
    public function show($id)
    {
        try {
            // Fetch category and its associated category group
            $category = Category::with('categoryGroup')->findOrFail($id);

            // Authorization check for non-development environment
            // if(!$this->environment){
            //     $this->authorize('view', $category); // Ensure user has permission to view the category
            // }

            // Return category in API resource format
            return new CategoryResource($category);

        } catch (Exception $e) {
            // Return error response if exception occurs
            return response()->json(['error' => 'Error fetching category', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Create a new category
    |------------------------------------------------------
    */
    public function store(Request $request)
    {
        try {
            // Authorization check for non-development environment
            if(!$this->environment){
                $this->authorize('create', Category::class); // Ensure user has permission to create a category
            }

            // Validate the request data
            $validated = $request->validate([
                'name' => 'required|string|max:255', // Category name is required and should be a string
                'description' => 'nullable|string|max:255', // Description is optional but must be a string
                'category_group_id' => 'required|exists:category_groups,id', // Ensure valid category group ID
            ]);

            // Create the new category and store in database
            $category = Category::create($validated);

            // Forget the cached list of categories so it can be refreshed
            Cache::forget('categories_index_' . md5($request->fullUrl()));

            // Return newly created category in API resource format
            return new CategoryResource($category);

        } catch (Exception $e) {
            // Return error response if exception occurs
            return response()->json(['error' => 'Error creating category', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Update an existing category
    |------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        try {
            // Find the category by ID, or throw an exception if not found
            $category = Category::findOrFail($id);

            // Authorization check for non-development environment
            if(!$this->environment){
                $this->authorize('update', $category); // Ensure user has permission to update the category
            }

            // Validate the request data
            $validated = $request->validate([
                'name' => 'required|string|max:255', // Category name is required and should be a string
                'description' => 'nullable|string|max:255', // Description is optional but must be a string
                'category_group_id' => 'required|exists:category_groups,id', // Ensure valid category group ID
            ]);

            // Update the category in the database
            $category->update($validated);

            // Forget the cached list of categories to ensure it reflects the update
            Cache::forget('categories_index_' . md5($request->fullUrl()));

            // Return the updated category in API resource format
            return new CategoryResource($category);

        } catch (Exception $e) {
            // Return error response if exception occurs
            return response()->json(['error' => 'Error updating category'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Delete a category by ID
    |------------------------------------------------------
    */
    public function destroy($id) 
    {
        try {
            // Find the category by ID, or throw an exception if not found
            $category = Category::findOrFail($id); 

            // Authorization check for non-development environment
            if(!$this->environment){
                $this->authorize('delete', $category); // Ensure user has permission to delete the category
            }

            // Delete the category from the database
            $category->delete(); 

            // Clear the cached categories to reflect the deletion
            $cacheKey = 'categories_index_' . md5(request()->fullUrl());
            if (Cache::has($cacheKey)) {
                Cache::forget($cacheKey); // Remove cached list of categories
            }

            // Return success response
            return response()->json(['message' => 'Category deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Return error response if exception occurs
            return response()->json(['error' => 'Error deleting category', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /*
    |------------------------------------------------------
    | Method to fetch category groups with optional search functionality.
    |------------------------------------------------------
    */
    public function categoryGroups(Request $request)
    {
        try {
            // Check if environment is not development, then authorize the user.
            // if(!$this->environment){
            //     $this->authorize('viewAny', CategoryGroup::class);
            // }

            // Using Cache to store the category groups for 120 minutes based on the request URL.
            $categoryGroups = Cache::remember('category_groups_' . md5($request->fullUrl()), 120, function () use ($request) {
                $query = CategoryGroup::with('categories');

                // If 'search' parameter is present, filter by name.
                if ($request->filled('search')) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                }

                return $query->get();
            });

            // Return the fetched category groups as a resource collection.
            return CategoryGroupResource::collection($categoryGroups);

        } catch (Exception $e) {
            // Handle any exceptions and return an error response.
            return response()->json(['error' => 'Error fetching category groups', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Method to fetch a single category group by ID.
    |------------------------------------------------------
    */
    public function showCategoryGroup($id)
    {
        try {
            // Fetch the category group with the given ID, along with its associated categories.
            $categoryGroup = CategoryGroup::with('categories')->findOrFail($id);
            
            // Check if environment is not development, then authorize the user.
            // if(!$this->environment){
            //     $this->authorize('view', $categoryGroup);
            // }

            // Return the category group as a resource.
            return new CategoryGroupResource($categoryGroup);

        } catch (Exception $e) {
            // Handle any exceptions and return an error response.
            return response()->json(['error' => 'Error fetching category group', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Method to create a new category group.
    |------------------------------------------------------
    */
    public function storeCategoryGroup(Request $request)
    {
        try {
            // Check if environment is not development, then authorize the user.
            if(!$this->environment){
                $this->authorize('create', CategoryGroup::class);
            }

            // Validate the incoming request data.
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Create a new category group with validated data.
            $categoryGroup = CategoryGroup::create($validated);

            // Return the created category group as a resource.
            return new CategoryGroupResource($categoryGroup);

        } catch (Exception $e) {
            // Handle any exceptions and return an error response.
            return response()->json(['error' => 'Error creating category group', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Method to update an existing category group.
    |------------------------------------------------------
    */
    public function updateCategoryGroup(Request $request, $id)
    {
        try {
            // Fetch the category group with the given ID.
            $categoryGroup = CategoryGroup::findOrFail($id);
            
            // Check if environment is not development, then authorize the user.
            if(!$this->environment){
                $this->authorize('update', $categoryGroup);
            }

            // Validate the incoming request data.
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Update the category group with the validated data.
            $categoryGroup->update($validated);

            // Return the updated category group as a resource.
            return new CategoryGroupResource($categoryGroup);

        } catch (Exception $e) {
            // Handle any exceptions and return an error response.
            return response()->json(['error' => 'Error updating category group', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Method to delete an existing category group.
    |------------------------------------------------------
    */
    public function destroyCategoryGroup($id)
    {
        try {
            // Fetch the category group with the given ID.
            $categoryGroup = CategoryGroup::findOrFail($id);
            
            // Check if environment is not development, then authorize the user.
            if(!$this->environment){
                $this->authorize('delete', $categoryGroup);
            }

            // Delete the category group.
            $categoryGroup->delete();

            // Return a success response indicating the category group was deleted.
            return response()->json(['message' => 'Category group deleted successfully'], Response::HTTP_NO_CONTENT);

        } catch (Exception $e) {
            // Handle any exceptions and return an error response.
            return response()->json(['error' => 'Error deleting category group', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
