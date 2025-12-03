<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Booking;
use App\Models\AdcDate;
use App\Models\CapacityLevel;
use App\Models\CpfLocationMapping;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoAssignBookings extends Command
{
    protected $signature = 'adc:auto-assign-bookings';
    protected $description = 'Auto assign ADC dates to employees who have not booked';

    public function handle()
    {
        $this->info("Starting ADC auto-booking process...");

        $users = User::all()->filter(function ($user) {
            return !Booking::withTrashed()
                ->where('user_id', $user->id)
                ->exists();
        });

        if ($users->isEmpty()) {
            $this->info("All employees already booked");
            return Command::SUCCESS;
        }


        foreach ($users as $user) {

            $this->info("Processing user: {$user->cpf_no} / {$user->name}");

            $mappedCentre = CpfLocationMapping::where('cpf_no', $user->cpf_no)
                ->value('adc_centre_id');

            $booking = null;

            if ($mappedCentre) {
                $booking = $this->assignFromCentre($user, $mappedCentre);
            }

            if (!$booking) {
                $booking = $this->assignFromAnyCentre($user);
            }

            if ($booking) {
                Log::info("AUTO_ASSIGN_SUCCESS", [
                    'cpf_no' => $user->cpf_no,
                    'date' => $booking->adcDate->date->format('Y-m-d'),
                    'centre' => $booking->adcDate->centre->city,
                ]);

                $this->info("✔ Assigned date: " . $booking->adcDate->date->format('Y-m-d') .
                    " at centre: " . $booking->adcDate->centre->city);
            } else {
                Log::warning("AUTO_ASSIGN_FAILED", [
                    'cpf_no' => $user->cpf_no,
                    'level' => $user->level,
                ]);

                $this->error("❌ No slot available for user {$user->cpf_no}");
            }
        }

        $this->info("Auto booking completed.");
        return Command::SUCCESS;
    }


    /**
     * Try booking from a specific centre
     */
    private function assignFromCentre($user, $centreId)
    {
        $dates = AdcDate::where('adc_centre_id', $centreId)
            ->orderBy('date')
            ->get();

        foreach ($dates as $date) {
            $capacity = CapacityLevel::where('adc_date_id', $date->id)
                ->where('level', $user->level)
                ->first();

            if (!$capacity) continue;

            if (Booking::where('user_id', $user->id)->exists()) {
                return null;
            }
            if ($capacity->current_count < $capacity->capacity) {
                return $this->createBooking($user, $date);
            }
        }

        return null;
    }


    /**
     * Try booking from ANY centre
     */
    private function assignFromAnyCentre($user)
    {
        $dates = AdcDate::orderBy('date')->get();

        foreach ($dates as $date) {
            $capacity = CapacityLevel::where('adc_date_id', $date->id)
                ->where('level', $user->level)
                ->first();

            if (!$capacity) continue;

            if (Booking::where('user_id', $user->id)->exists()) {
                return null;
            }
            if ($capacity->current_count < $capacity->capacity) {
                return $this->createBooking($user, $date);
            }
        }

        return null;
    }


    /**
     * Create Booking Record
     */
    private function createBooking($user, $date)
    {
        return DB::transaction(function () use ($user, $date) {
            return Booking::create([
                'user_id' => $user->id,
                'adc_date_id' => $date->id,
                'created_by' => null,
            ]);
        });
    }
}
