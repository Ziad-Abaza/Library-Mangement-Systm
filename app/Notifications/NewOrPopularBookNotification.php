<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Book;

class NewOrPopularBookNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $book;
    protected $type; // To distinguish between new or popular book

    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    | Initialize the notification with a book instance and the type (new or popular).
    */
    public function __construct(Book $book, string $type)
    {
        $this->book = $book;
        $this->type = $type;
    }

    /*
    |--------------------------------------------------------------------------
    | Notification Channels
    |--------------------------------------------------------------------------
    | Specify the channels through which the notification will be delivered.
    | Here, it is set to the database channel.
    */
    public function via($notifiable)
    {
        return ['database']; 
    }

    /*
    |--------------------------------------------------------------------------
    | Notification Payload
    |--------------------------------------------------------------------------
    | Define the data structure for storing the notification in the database.
    */
    public function toArray($notifiable): array
    {
        $message = $this->type == 'new' 
            ? "تم نشر كتاب جديد بعنوان '{$this->book->title}' من تأليف {$this->book->author->name}."
            : "هذا الكتاب بعنوان '{$this->book->title}' من تأليف {$this->book->author->name} أصبح من الكتب الشائعة!";

        return [
            'book_id' => $this->book->id, 
            'title' => $this->book->title, 
            'author' => $this->book->author->name, 
            'type' => $this->type, 
            'message' => $message, 
        ];
    }

    // Optional mail notification logic if email support is needed
    /**
     * Get the notification message for mail (optional).
     */
//    public function toMail($notifiable): MailMessage
//    {
//        return (new MailMessage)
//            ->subject('New or Popular Book Published')
//            ->line("A {$this->type} book titled '{$this->book->title}' has just been published!")
//            ->action('View Book', url("/books/{$this->book->id}"))
//            ->line('Thank you for using our platform!');
//    }
}
