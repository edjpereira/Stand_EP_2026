<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Conjunto de marcas reais a usar no campo 'make'
        $makes = ['Volkswagen', 'BMW', 'Mercedes-Benz', 'Audi', 'Renault', 'Peugeot', 'BYD', 'Renault', 'Porsche', 'Ferrari', 'Hyundai', 'Kia'];

        return [
            'make' => $this->faker->randomElement($makes),
            'model' => $this->faker->word(),
            'plate' => strtoupper($this->faker->bothify('??-##-??')),
            'year' => $this->faker->numberBetween(2010, 2026),
            'mileage' => $this->faker->numberBetween(0, 250000),
            'price' => $this->faker->randomFloat(2, 1000, 370000),
            'photo' => null,
            'status' => 'available',
        ];
    }
}
