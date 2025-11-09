<?php

namespace Database\Factories;

use App\Models\ComfortCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarModel>
 */
class CarModelFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cars = [
            'Mercedes-Benz S-Class' => 4,
            'BMW 7 Series'          => 4,
            'Audi A8'               => 4,
            'Toyota Camry'          => 3,
            'Honda Accord'          => 3,
            'Volkswagen Passat'     => 3,
            'Kia Rio'               => 2,
            'Hyundai Solaris'       => 2,
            'Skoda Octavia'         => 2,
            'Ford Focus'            => 2,
            'Nissan Almera'         => 1,
            'Chevrolet Spark'       => 1,
            'Renault Logan'         => 1,
        ];

        $car = $this->faker->randomElement(array_keys($cars));

        return [
            'name' => $car,
            'comfort_category_id' => $cars[$car],
        ];
    }
}
