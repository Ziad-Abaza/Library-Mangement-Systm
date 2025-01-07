<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\NotificationResource;
use App\Jobs\SendNotificationJob;

class NotificationController extends Controller
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
    | Send notification to all active users
    |------------------------------------------------------
    */
    public function sendToAllUsers(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        User::where('is_active', true)->chunk(100, function ($users) use ($request) {
            foreach ($users as $user) {
                SendNotificationJob::dispatch($user, $request->message)->delay(now()->addSeconds(5));
            }
        });

        return response()->json(['message' => 'Notification sent to all active users in background.']);
    }

    /*
    |------------------------------------------------------
    | Send notification to a specific user
    |------------------------------------------------------
    */
    public function sendToSpecificUser(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        $user->notify(new GeneralNotification($request->message));

        return response()->json(['message' => 'Notification sent to user.']);
    }

    /*
    |------------------------------------------------------
    | Retrieve unread notifications for the authenticated user
    |------------------------------------------------------
    */
    public function getUserNotifications()
    {
        // Use the logged-in user (development mode uses ID 1)
        // $user = $this->environment ? User::find(1) : auth()->user();
        $user = User::find(1);
        
        // Fetch only unread notifications
        $unreadNotifications = $user->unreadNotifications; 

        return NotificationResource::collection($unreadNotifications);
    }

    /*
    |------------------------------------------------------
    | Retrieve read notifications for the authenticated user
    |------------------------------------------------------
    */
    public function getReadNotifications()
    {
        // Use the logged-in user (development mode uses ID 1)
        // $user = $this->environment ? User::find(1) : auth()->user();
        $user = User::find(1);
        // Fetch only read notifications
        $readNotifications = $user->notifications()->whereNotNull('read_at')->get();

        return NotificationResource::collection($readNotifications); 
    }

    /*
    |------------------------------------------------------
    | Delete all notifications for the authenticated user
    |------------------------------------------------------
    */
    public function deleteAllNotifications()
    {
        // Use the logged-in user (development mode uses ID 1)
        // $user = $this->environment ? User::find(1) : auth()->user();
        $user = User::find(1);
        // Delete all notifications for the user
        $user->notifications()->delete();

        return response()->json(['message' => 'All notifications deleted.']);
    }

    /*
    |------------------------------------------------------
    | Mark a specific notification as read
    |------------------------------------------------------
    */
    public function markAsRead($notificationId)
    {
        // $user = $this->environment ? User::find(1) : auth()->user();
        $user = User::find(1);
        // Find the notification for the selected user
        $notification = $user->notifications()->findOrFail($notificationId);

        // Mark the notification as read
        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read.']);
    }

    /*
    |------------------------------------------------------
    | Delete a specific notification
    |------------------------------------------------------
    */
    public function deleteNotification($notificationId)
    {
        // $user = $this->environment ? User::find(1) : auth()->user();
        $user = User::find(1);

        // Find the notification for the selected user
        $notification = $user->notifications()->findOrFail($notificationId);

        // Delete the notification
        $notification->delete();

        return response()->json(['message' => 'Notification deleted.']);
    }
}
