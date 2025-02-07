<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle user login and return an API token.
     */
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Retrieve the user
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if the user is active
        if (!$user->is_active) {
            return response()->json([
                'message' => 'Your account is not activated.',
                'success' => false,
            ], Response::HTTP_FORBIDDEN);
        }

        // Optionally delete old tokens (force single device login)
        // $user->tokens()->delete();

        // Create new API token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return response
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $token,
            'success' => true,
        ], Response::HTTP_OK);
    }

    /**
     * Handle user logout by revoking the current token.
     */
    public function logout(Request $request)
    {
        // Revoke the user's current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
            'success' => true,
        ], Response::HTTP_OK);
    }
}
