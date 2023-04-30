<?php

namespace Database\Factories;

use App\Models\BarbershopOwner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barbershop>
 */
class BarbershopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'rating' => $this->faker->randomFloat(0, 5),
            'barbershop_owner_id' => BarbershopOwner::factory(),
        ];
    }
}
