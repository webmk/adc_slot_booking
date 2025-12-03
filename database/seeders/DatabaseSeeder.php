<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'cpf_no' => 'ABC007',
            'name' => 'Test',
            'email' => 'test@example.com',
            'mobile' => '9876543210',
            'level' => 'E5',
            'location' => 'DELH',
            'work_pattern' => 'Full-time',
            'duty_type' => 'On-site',
            'role' => '',
            'password' => Hash::make('adc@123'),
        ]);

        /* $this->call([
            \Database\Seeders\AdcCentreAndDatesSeeder::class,
            //\Database\Seeders\LevelSeeder::class,
        ]); */
    }
}
