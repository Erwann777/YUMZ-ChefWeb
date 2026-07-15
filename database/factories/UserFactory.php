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

    /**
     * Customer role state.
     */
    public function customer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'           => 'customer',
            'country'        => 'ID',
            'currency'       => 'IDR',
            'wallet_balance' => 10000000.00,
        ]);
    }

    /**
     * Cooker role state.
     */
    public function cooker(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'           => 'cooker',
            'country'        => 'ID',
            'currency'       => 'IDR',
            'wallet_balance' => 0.00,
        ]);
    }

    /**
     * Admin role state.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'           => 'admin',
            'country'        => 'ID',
            'currency'       => 'IDR',
            'wallet_balance' => 0.00,
        ]);
    }

    /**
     * Singapore customer state (SGD currency).
     */
    public function singaporeCustomer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'           => 'customer',
            'country'        => 'SG',
            'currency'       => 'SGD',
            'wallet_balance' => 1000.00,
        ]);
    }

    /**
     * Malaysia customer state (MYR currency).
     */
    public function malaysiaCustomer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'           => 'customer',
            'country'        => 'MY',
            'currency'       => 'MYR',
            'wallet_balance' => 3000.00,
        ]);
    }
}
