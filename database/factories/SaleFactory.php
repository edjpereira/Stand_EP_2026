<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'client_id' => \App\Models\Client::factory(),
        'vehicle_id' => \App\Models\Vehicle::factory(),
        'sale_date' => $this->faker->date(),
        'sale_amount' => $this->faker->randomFloat(2, 1000, 370000),
        'notes' => $this->faker->sentence(),
    ];
}
}
