<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
<<<<<<< HEAD
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
=======
>>>>>>> 200ba4eda14599c192446dd1af7ae94e055c543d

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
<<<<<<< HEAD
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 12345678,
            'phone_number' => fake()->unique()->numberBetween(100000000, 9999999999),
            'role' => 'customer',
            'enterprise_name' => fake()->company(),
            'nit' => fake()->unique()->numberBetween(1000000000, 9999999999),
=======
            //
>>>>>>> 200ba4eda14599c192446dd1af7ae94e055c543d
        ];
    }
}
