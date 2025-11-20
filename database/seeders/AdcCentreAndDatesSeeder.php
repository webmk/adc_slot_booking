<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdcCentre;
use App\Models\AdcDate;
use Carbon\Carbon;

class AdcCentreAndDatesSeeder extends Seeder
{
    public function run()
    {
        $centre = AdcCentre::firstOrCreate(['city'=>'New Delhi','address'=>'ONSITE']);
        AdcDate::firstOrCreate(['adc_centre_id' => $centre->id, 'date' => Carbon::now()->addDays(10)->toDateString()]);
        AdcDate::firstOrCreate(['adc_centre_id' => $centre->id, 'date' => Carbon::now()->addDays(20)->toDateString()]);
    }
}