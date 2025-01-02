<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Book;

class PublicationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $book;

    /**
     * Constructor
     * ----------------------------------------------------
     * Initialize the notification with a book instance.
     */
    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    /**
     * Notification Channels
     * ----------------------------------------------------
     * Define the channels through which the notification will be sent.
     * Here, it's set to the database channel.
     */
    public function via($notifiable)
    {
        return ['database'];  // The notification will be stored in the database.
    }

    /**
     * Notification Payload
     * ----------------------------------------------------
     * Provide the array representation of the notification for database storage.
     */
    public function toArray($notifiable): array
    {
        // Create a message based on the book's status
        $message = match ($this->book->status) {
            'approved' => "تمت الموافقة على الكتاب: {$this->book->title}",
            'rejected' => "تم رفض الكتاب: {$this->book->title}",
            'pending' => "الكتاب قيد المراجعة: {$this->book->title}",
            default => "تم تحديث حالة الكتاب: {$this->book->title}",
        };

        return [
            'book_id' => $this->book->id,         // Book ID
            'book_title' => $this->book->title,   // Book title
            'status' => $this->book->status,      // Current status of the book
            'message' => $message,                // Generated message based on status
        ];
    }
}
