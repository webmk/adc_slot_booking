<?php

namespace App\Models;

use App\Services\CapacityCountService;

use Illuminate\Database\Eloquent\Model;

class CapacityLevel extends Model
{
    protected $fillable = [
        'adc_date_id',
        'level_id',
        'capacity',
    ];
    public function adcDate()
    {
        return $this->belongsTo(AdcDate::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    protected static $cachedCounts = null;

    public function getCurrentCountAttribute()
    {
        // Load once per HTTP request
        if (static::$cachedCounts === null) {
            static::$cachedCounts = CapacityCountService::loadCounts();
        }

        $key = $this->adc_date_id . '|' . $this->level_id;

        return static::$cachedCounts[$key] ?? 0;
    }
}
