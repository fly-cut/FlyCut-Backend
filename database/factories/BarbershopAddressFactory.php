<?php

namespace Database\Factories;

use App\Models\Barbershop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BarbershopAddress>
 */
class BarbershopAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'longitude' => $this->faker->longitude(),
            'latitude' => $this->faker->latitude(),
            'address' => $this->faker->address(),
            'barbershop_id' => Barbershop::factory(),
        ];
    }
}
