<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [['name'=>'E5'], ['name'=>'E6'], ['name'=>'E7']];
        foreach ($levels as $l) {
            Level::firstOrCreate(['name' => $l['name']], $l);
        }
    }
}
