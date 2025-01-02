<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Notifications\RoleChangedNotification;

class UserController extends Controller
{
    /*
    |------------------------------------------------------
    | Constructor to handle authorization based on environment
    |------------------------------------------------------
    */
    public function __construct()
    {
        $environment = env('DEV_ENVIRONMENT', false); 
        if ($environment) {
            Auth::loginUsingId(1); // Auto-login for development
        } else {
            // Apply resource authorization for production
            $this->authorizeResource(User::class, 'user');   
        }
    }

    /*
    |------------------------------------------------------
    | Retrieve and cache a list of users based on search query
    |------------------------------------------------------
    */
    public function index(Request $request)
    {
        try {
            $cacheKey = 'users_' . md5($request->get('search'));
            $users = Cache::remember($cacheKey, 20 * 60, function () use ($request) {
                $query = User::with(['roles', 'media']);

                if ($request->has('search')) {
                    $search = $request->get('search');
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                }

                return $query->get();
            });
            return UserResource::collection($users);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve users.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Show details of a specific user
    |------------------------------------------------------
    */
    public function show(User $user)
    {
        try {
            return new UserResource($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found.'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve the user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Create a new user with optional roles and image
    |------------------------------------------------------
    */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
                'role_ids' => 'nullable|array',
                'role_ids.*' => 'integer|exists:roles,id',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            if ($request->has('role_ids')) {
                $user->roles()->attach($validated['role_ids']);
            }

            if ($request->hasFile('image')) {
                $user->addMediaFromRequest('image')->toMediaCollection('images');
            } else {
                $user->addMediaFromUrl(app()->environment('APP_URL') . '/assets/images/static/person.png')
                    ->toMediaCollection('images');
            }

            return response()->json(['message' => 'User created successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Update user details, including roles and image
    |------------------------------------------------------
    */
    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
                'password' => 'sometimes|required|string|min:8',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
                'role_ids' => 'nullable|array',
                'role_ids.*' => 'integer|exists:roles,id',
            ]);

            $data = $validated;
            if (isset($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            $user->update($data);

            if ($request->has('role_ids')) {
                $user->roles()->sync($validated['role_ids']);
            }

            if ($request->hasFile('image')) {
                $user->clearMediaCollection('images');
                $user->addMediaFromRequest('image')->toMediaCollection('images');
            }

            return new UserResource($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found.'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Delete a user along with their associated data
    |------------------------------------------------------
    */
    public function destroy(User $user)
    {
        try {
            $user->clearMediaCollection('images'); 
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found.'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Add a role to a user and notify them of the change
    |------------------------------------------------------
    */
    public function addRole(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'role_id' => 'required|integer|exists:roles,id',
            ]);

            $user->roles()->attach($validated['role_id']);
            $role = Role::find($validated['role_id']);
            $user->notify(new RoleChangedNotification($user, $role));

            return response()->json(['message' => 'Role added successfully'], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found.'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to add role.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    |------------------------------------------------------
    | Remove a role from a user and notify them of the change
    |------------------------------------------------------
    */
    public function removeRole(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'role_id' => 'required|integer|exists:roles,id',
            ]);

            $user->roles()->detach($validated['role_id']);
            $role = Role::find($validated['role_id']);
            $user->notify(new RoleChangedNotification($user, $role));

            return response()->json(['message' => 'Role removed successfully'], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found.'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to remove role.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
