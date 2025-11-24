<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeLocationMapping extends Model
{
    protected $fillable = [
        'employee_location',
        'adc_centre_id',
    ];

    public function centre()
    {
        return $this->belongsTo(AdcCentre::class, 'adc_centre_id');
    }
}
