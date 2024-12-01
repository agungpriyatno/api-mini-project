<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return  [
            'code' => fake()->uuid(),
            'name' => fake()->catchPhrase,
            'category' => fake()->company,
            'price' => fake()->randomFloat(0, 100000, 1000000),	
        ];
    }
}
