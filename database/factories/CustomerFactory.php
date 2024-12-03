<?php

namespace Database\Factories;

use Faker\Generator as Faker;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Customer::class;
    public function definition(): array
    {
        return  [
            'name' => fake()->name,
            'address' => fake()->address,
            'gender' => fake()->randomElement(['MALE', 'FEMALE']),
        ];
    }
}
