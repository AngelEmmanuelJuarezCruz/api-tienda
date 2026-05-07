<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
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
            'rol' => 'cajero',
            'activo' => true,
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

    public function dueno(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'dueno',
            'activo' => true,
        ]);
    }

    public function encargado(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'encargado',
            'activo' => true,
        ]);
    }

    public function cajero(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'cajero',
            'activo' => true,
        ]);
    }
}
