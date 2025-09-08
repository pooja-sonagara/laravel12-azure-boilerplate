<?php

namespace App\Notifications;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SignalRNotification extends Notification  implements ShouldQueue
{
    use Queueable;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    // Specify delivery channels
    public function via($notifiable)
    {
        return ['signalr', 'database']; // database optional
    }

    // Optional: store in database
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message
        ];
    }

    // Data for SignalR channel
    public function toSignalR($notifiable)
    {
        return [
            'target' => 'newNotification', // must match client listener
            'arguments' => [$this->message]
        ];
    }
}
