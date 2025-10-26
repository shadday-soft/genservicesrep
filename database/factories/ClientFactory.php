<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make(12345678),
            'phone_number' => fake()->unique()->numberBetween(100000000, 9999999999),
            'role' => 'customer',
            'enterprise_name' => fake()->company(),
            'nit' => fake()->unique()->numberBetween(1000000000, 9999999999),
        ];
    }
}
