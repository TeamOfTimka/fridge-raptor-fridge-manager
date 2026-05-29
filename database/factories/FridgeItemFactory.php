<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FridgeItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'unit' => $this->faker->randomElement(['шт', 'кг', 'л', 'г']),
        ];
    }
}