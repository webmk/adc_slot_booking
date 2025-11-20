<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'adc_date_id',
        'status',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function adcDate()
    {
        return $this->belongsTo(AdcDate::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
