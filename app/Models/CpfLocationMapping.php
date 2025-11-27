<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CpfLocationMapping extends Model
{
    protected $fillable = [
        'cpf_no',
        'adc_centre_id',
    ];
    public function centre()
    {
        return $this->belongsTo(AdcCentre::class, 'adc_centre_id');
    }
}
