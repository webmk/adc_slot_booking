<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
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
            ->greeting('Madam/Sir,')
            ->line('Your ADC slot for **'
                . $this->booking->adcDate->date->format('d M Y')
                . '** at **'
                . $this->booking->adcDate->centre->city
                . '** has been successfully booked.')
            ->line('While every effort will be made to accommodate your preferred choice, it is informed that, in case of any unforeseen circumstances, the allotted ADC slot may be subject to change.')
            ->line('Your exact schedule will be communicated separately through email.')
            ->line('Warm Regards,')
            ->salutation('Corporate Promotions, ONGC');
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
