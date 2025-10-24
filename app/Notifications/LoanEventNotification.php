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
        // Send to both database and mail channels
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject($this->getMailSubject())
            ->line($this->payload['message'] ?? 'New notification');

        if (isset($this->payload['book_title'])) {
            $message->line('Book: ' . $this->payload['book_title']);
        }

        if (isset($this->payload['url'])) {
            $message->action('View Details', url($this->payload['url']));
        }

        return $message->line('Thank you for using our library system!');
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

    private function getMailSubject(): string
    {
        return match ($this->event) {
            'loan.requested' => 'New Book Loan Request',
            'loan.approved' => 'Book Loan Request Approved',
            'loan.returned' => 'Book Returned',
            'loan.declined' => 'Book Loan Request Declined',
            default => 'Library Notification',
        };
    }
}


