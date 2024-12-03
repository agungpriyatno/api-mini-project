<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;

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
