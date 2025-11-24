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
            'cpf_no' => 'A006142',
            'name' => 'Manish',
            'email' => 'manish.kumar@cipl.org.in',
            'mobile' => '8873535456',
            'level' => 'Level 1',
            'location' => 'Location A',
            'work_pattern' => 'Full-time',
            'duty_type' => 'On-site',
            'role' => 'admin',
            'password' => Hash::make('ongc@123'),
        ]);

        /* $this->call([
            \Database\Seeders\AdcCentreAndDatesSeeder::class,
            //\Database\Seeders\LevelSeeder::class,
        ]); */
    }
}
