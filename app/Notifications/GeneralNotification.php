<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    | Initialize the notification with a message to be stored in the database.
    */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /*
    |--------------------------------------------------------------------------
    | Notification Channels
    |--------------------------------------------------------------------------
    | Define the channels through which the notification will be sent. 
    | Here, it's set to the database channel.
    */
    public function via($notifiable)
    {
        return ['database'];
    }

    /*
    |--------------------------------------------------------------------------
    | Notification Payload
    |--------------------------------------------------------------------------
    | Define the data to be stored in the database for the notification.
    */
    public function toArray($notifiable): array
    {
        return [
            'message' => $this->message,
        ];
    }
}
