<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = \App\Models\Vehicle::factory(70)->create();
    }
}
