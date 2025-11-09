<?php

namespace Database\Factories;

use App\Models\CarModel;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'license_plate' => strtoupper($this->faker->bothify('?#??#??')),
            'car_model_id' => CarModel::inRandomOrder()->first()?->id,
            'driver_id' => Driver::inRandomOrder()->first()?->id,
        ];
    }
}
