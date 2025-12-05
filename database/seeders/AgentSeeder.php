<?php

// database/seeders/AgentSeeder.php
namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        Agent::insert([
            [
                'name' => 'Masref Agent – Hamra',
                'address' => 'Hamra St, Beirut',
                'city' => 'Beirut', 'country' => 'Lebanon',
                'phone' => '+961 1 555 111',
                'latitude' => 33.896275, 'longitude' => 35.480194,
                'opening_hours' => ['mon-fri' => '9:00–18:00', 'sat' => '10:00–14:00'],
            ],
            [
                'name' => 'Masref Agent – Jnah',
                'address' => 'Jnah, Beirut',
                'city' => 'Beirut', 'country' => 'Lebanon',
                'phone' => '+961 1 555 222',
                'latitude' => 33.859350, 'longitude' => 35.486940,
                'opening_hours' => ['mon-fri' => '9:30–17:30'],
            ],
            [
                'name' => 'Masref Agent – Tripoli',
                'address' => 'Mina Rd, Tripoli',
                'city' => 'Tripoli', 'country' => 'Lebanon',
                'phone' => '+961 6 555 333',
                'latitude' => 34.436670, 'longitude' => 35.833090,
                'opening_hours' => ['mon-fri' => '9:00–18:00'],
            ],
        ]);
    }
}
