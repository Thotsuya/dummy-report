<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

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
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'registered_at' => now()->subDays(random_int(1, 1460)),
            'country' => fake()->country(),
            'age' => random_int(18, 65),
            'gender' => fake()->randomElement(['male', 'female']),
            'last_login' => now()->subDays(random_int(1, 30)),
            'subscription_status' => fake()->randomElement(['standard', 'premium']),
            'referral_source' => fake()->randomElement(['Google', 'Facebook', 'Twitter', 'LinkedIn', 'Instagram']),
            'account_status' => fake()->randomElement(['active', 'inactive', 'suspended']),
            'occupation' => fake()->jobTitle(),
            'lifetime_value' => random_int(100, 1000000),
            'preferred_language' => fake()->randomElement(['en', 'es', 'fr', 'de', 'it', 'pt']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
