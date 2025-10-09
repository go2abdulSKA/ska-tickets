<?php
// app/Notifications/TicketCreatedNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TicketMaster;

class TicketCreatedNotification extends Notification
{
    use Queueable;

    protected $ticket;

    public function __construct(TicketMaster $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Ticket Created: ' . $this->ticket->ticket_no)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new ticket has been created.')
            ->line('Ticket Number: ' . $this->ticket->ticket_no)
            ->line('Total Amount: ' . $this->ticket->currency->symbol() . number_format($this->ticket->total_amount, 2))
            ->action('View Ticket', url('/tickets/finance'))
            ->line('Thank you!');
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_no' => $this->ticket->ticket_no,
            'total_amount' => $this->ticket->total_amount,
            'message' => 'Ticket ' . $this->ticket->ticket_no . ' has been created.',
        ];
    }
}
