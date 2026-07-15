<?php

namespace Database\Factories;

use App\Models\CookingService;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceOrder>
 */
class ServiceOrderFactory extends Factory
{
    protected $model = ServiceOrder::class;

    public function definition(): array
    {
        $service = CookingService::factory()->create();

        return [
            'customer_id' => User::factory()->customer(),
            'service_id'  => $service->id,
            'cooker_id'   => $service->cooker_id,
            'status'      => 'pending',
            'notes'       => fake()->sentence(),
            'total_price' => fake()->randomFloat(2, 20000, 500000),
        ];
    }

    /**
     * Pending order state.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Confirmed order state.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
        ]);
    }

    /**
     * Completed order with rating state.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'   => 'completed',
            'rating'   => fake()->numberBetween(1, 5),
            'review'   => fake()->sentence(),
            'rated_at' => now(),
        ]);
    }

    /**
     * Cancelled order state.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
