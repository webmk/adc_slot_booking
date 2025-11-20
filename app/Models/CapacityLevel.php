<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapacityLevel extends Model
{
    public function adcDate()
    {
        return $this->belongsTo(AdcDate::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
