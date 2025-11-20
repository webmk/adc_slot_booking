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
}
