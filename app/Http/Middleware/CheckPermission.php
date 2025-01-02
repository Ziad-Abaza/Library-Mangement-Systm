<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        // $user = Auth::user();
        $user = User::find(1);

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (!$this->hasPermission($user, $permission)) {
            return response()->json(['message' => 'You do not have permission to access this resource'], 403);
        }

        return $next($request);
    }

    /**
     *
     * @param  \App\Models\User  $user
     * @param  string  $permission
     * @return bool
     */
    private function hasPermission($user, $permission)
    {
        foreach ($user->roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }
        return false;
    }
}
