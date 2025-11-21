<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCreatedNotification extends Notification
{
    use Queueable;

    public $booking;
    /**
     * Create a new notification instance.
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('ADC Booking Confirmation')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your ADC slot has been successfully booked.')
            ->line('Date: ' . $this->booking->adcDate->date->format('d M Y'))
            ->line('Centre: ' . $this->booking->adcDate->centre->city . ' (' . $this->booking->adcDate->centre->name . ')')
            ->line('Level: ' . $notifiable->level->name)
            ->line('Thank you.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
