<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanEventNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $event,
        public array $payload
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'event' => $this->event,
            'message' => $this->payload['message'] ?? '',
            'loan_id' => $this->payload['loan_id'] ?? null,
            'book_id' => $this->payload['book_id'] ?? null,
            'book_title' => $this->payload['book_title'] ?? null,
            'by_user_id' => $this->payload['by_user_id'] ?? null,
            'by_user_name' => $this->payload['by_user_name'] ?? null,
            'url' => $this->payload['url'] ?? null,
        ];
    }
}


