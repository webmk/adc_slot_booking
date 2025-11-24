<?php

namespace App\Services;

use App\Models\Booking;

class CapacityCountService
{
    /**
     * Preload booking counts for all (adc_date_id, level) pairs.
     *
     * @return array  ['adc_date_id|level' => count]
     */
    public static function loadCounts()
    {
        return Booking::selectRaw('adc_date_id, users.level, COUNT(*) as total')
            ->join('users', 'users.id', '=', 'bookings.user_id')
            ->groupBy('adc_date_id', 'users.level')
            ->get()
            ->mapWithKeys(function ($row) {
                $key = $row->adc_date_id . '|' . $row->level;
                return [$key => $row->total];
            })
            ->toArray();
    }
}