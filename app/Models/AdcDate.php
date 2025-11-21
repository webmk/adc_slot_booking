<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AdcCentre;
use App\Models\Booking;

class AdcDate extends Model
{
    protected $fillable = [
        'adc_centre_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function centre()
    {
        return $this->belongsTo(AdcCentre::class, 'adc_centre_id');
    }

    public function capacities()
    {
        return $this->hasMany(CapacityLevel::class, 'adc_date_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'adc_date_id');
    }
}
