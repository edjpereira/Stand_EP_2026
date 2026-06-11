<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ClientSeeder::class,
            VehicleSeeder::class,
            SaleSeeder::class,
        ]);
    }

    //PARA CORRER NO TERMINAL:
    //php artisan db:seed  <-- CORRER TODOS OS SEEDERS (só esta linha)
    //php artisan db:seed --class=VehicleSeeder
    //php artisan db:seed --class=ClientSeeder
    //php artisan db:seed --class=UserSeeder
    //php artisan db:seed --class=SaleSeeder
}
