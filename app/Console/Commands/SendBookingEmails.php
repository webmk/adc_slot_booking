<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingCreatedNotification;

class SendBookingEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan adc:send-booking-emails
     */
    protected $signature = 'adc:send-booking-emails';

    /**
     * The console command description.
     */
    protected $description = 'Send booking details email to all employees who have booked ADC slot';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting email sending process...");

        // Fetch all bookings with users & dates
        $bookings = Booking::with(['user', 'adcDate.centre'])->get();

        if ($bookings->isEmpty()) {
            $this->warn("No bookings found. Nothing to send.");
            return Command::SUCCESS;
        }

        $count = 0;

        foreach ($bookings as $booking) {

            if (!$booking->user || !$booking->user->email) {
                $this->warn("Skipping booking ID {$booking->id}: No valid email.");
                continue;
            }

            // Send booking confirmation email (existing notification)
            $booking->user->notify(new BookingCreatedNotification($booking));

            $this->info("Email sent to: {$booking->user->email}");
            $count++;
            \Log::info("EMAIL_SENT", [
            'user' => $booking->user->cpf_no,
            'email' => $booking->user->email,
            'date' => $booking->adcDate->date->format('d M Y'),
            'centre' => $booking->adcDate->centre->city
        ]);

        }

        $this->info("Finished. Emails sent to {$count} employees.");

        return Command::SUCCESS;
    }
}