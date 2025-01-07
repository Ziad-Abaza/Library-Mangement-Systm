<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Role;
use App\Models\User;

class RoleChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $role;

    /**
     * Constructor
     * ----------------------------------------------------
     * Initialize the notification with a user and role instance.
     */
    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    /**
     * Notification Channels
     * ----------------------------------------------------
     * Define the channels through which the notification will be sent.
     * Here, it's set to the database channel.
     */
    public function via($notifiable)
    {
        return ['database']; 
    }

    /**
     * Notification Payload
     * ----------------------------------------------------
     * Provide the array representation of the notification for database storage.
     * This includes details of the user and their new role.
     */
    public function toArray($notifiable): array
    {
        // Message detailing the role change
        $message = "تم تغيير دور المستخدم {$this->user->name} إلى '{$this->role->name}'.";

        return [
            'user_id' => $this->user->id,        // User ID
            'name' => $this->user->name,         // User's name
            'new_role' => $this->role->name,     // New role name
            'message' => $message,               // Detailed message about the role change
        ];
    }

    // optional if want to implement it by email
//    /**
//     * Get the notification message for mail (optional if you want to send an email).
//     */
//    public function toMail($notifiable): MailMessage
//    {
//        return (new MailMessage)
//            ->subject('Role Changed')
//            ->line("Your role has been changed to {$this->role->name}.")
//            ->action('View Your Profile', url("/profile/{$this->user->id}"))
//            ->line('Thank you for using our platform!');
//    }
}
