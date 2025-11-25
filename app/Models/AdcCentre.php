<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdcCentre extends Model
{
    protected $fillable = [
        'name',
        'city',
        'address',
    ];

    public function dates()
    {
        return $this->hasMany(AdcDate::class);
    }

    public function bookings()
{
    return $this->hasManyThrough(
        Booking::class,
        AdcDate::class,
        'adc_centre_id',    // FK on adc_dates table
        'adc_date_id',      // FK on bookings table
        'id',               // PK on centres
        'id'                // PK on adc_dates
    );
}
}
