<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WalletTransaction>
 */
class WalletTransactionFactory extends Factory
{
    protected $model = WalletTransaction::class;

    public function definition(): array
    {
        return [
            'user_id'          => User::factory()->customer(),
            'type'             => fake()->randomElement(['credit', 'debit']),
            'amount'           => fake()->randomFloat(2, 1000, 500000),
            'currency'         => 'IDR',
            'reference_type'   => 'topup',
            'reference_id'     => null,
            'original_amount'  => null,
            'original_currency'=> null,
            'exchange_rate'    => null,
            'description'      => fake()->sentence(),
        ];
    }

    /**
     * Credit transaction state.
     */
    public function credit(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'credit',
        ]);
    }

    /**
     * Debit transaction state.
     */
    public function debit(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'debit',
        ]);
    }

    /**
     * Top-up reference state.
     */
    public function topup(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'           => 'credit',
            'reference_type' => 'topup',
            'description'    => 'Virtual Wallet Simulation Top-Up',
        ]);
    }

    /**
     * Transaction with currency conversion state.
     */
    public function withConversion(): static
    {
        return $this->state(fn (array $attributes) => [
            'currency'          => 'IDR',
            'original_amount'   => fake()->randomFloat(2, 10, 100),
            'original_currency' => 'SGD',
            'exchange_rate'     => 11500.00,
        ]);
    }
}
