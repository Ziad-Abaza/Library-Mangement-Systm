<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DownloadResource;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DownloadController extends Controller
{

    /*
    |------------------------------------------------------
    | Constructor to handle authorization based on environment
    |------------------------------------------------------
    | This constructor checks if the environment is development and logs in a default user (ID: 1) 
    | for testing purposes in development environments.
    |------------------------------------------------------
    */
    public function __construct()
    {
        $environment = env('DEV_ENVIRONMENT', false); 
        if ($environment) {
            Auth::loginUsingId(1); // Auto-login for development
        }
    }

    /*
    |------------------------------------------------------
    | Display a listing of the user's downloads.
    |------------------------------------------------------
    | This method fetches all the downloads made by the currently authenticated user.
    | It ensures the user is authenticated before retrieving the downloads.
    | If the user is not authenticated, a 401 Unauthorized response is returned.
    |------------------------------------------------------
    */
    public function index(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();
        
        // Check if the user is authenticated
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch the downloads related to the authenticated user along with their associated book details
        $downloads = Download::where('user_id', $user->id)->with('book')->get();

        // Return the list of downloads as a collection of resources
        return DownloadResource::collection($downloads);
    }
}
