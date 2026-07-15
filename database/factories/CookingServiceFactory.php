<?php

namespace Database\Factories;

use App\Models\CookingService;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CookingService>
 */
class CookingServiceFactory extends Factory
{
    protected $model = CookingService::class;

    private static array $categories = [
        'indonesia', 'malaysian', 'chinese', 'japanese', 'korean',
        'thailand', 'indian', 'italian', 'american', 'french',
        'british', 'dessert',
    ];

    public function definition(): array
    {
        return [
            'cooker_id'    => User::factory()->cooker(),
            'title'        => fake()->sentence(3),
            'description'  => fake()->paragraph(),
            'price'        => fake()->randomFloat(2, 20000, 1000000),
            'is_available' => true,
            'image_path'   => 'services/test-image.jpg',
            'category'     => fake()->randomElement(self::$categories),
            'is_halal'     => fake()->boolean(),
            'currency'     => 'IDR',
        ];
    }

    /**
     * Available service state.
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => true,
        ]);
    }

    /**
     * Unavailable service state.
     */
    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => false,
        ]);
    }

    /**
     * SGD currency state.
     */
    public function sgd(): static
    {
        return $this->state(fn (array $attributes) => [
            'currency' => 'SGD',
            'price'    => fake()->randomFloat(2, 20, 200),
        ]);
    }

    /**
     * MYR currency state.
     */
    public function myr(): static
    {
        return $this->state(fn (array $attributes) => [
            'currency' => 'MYR',
            'price'    => fake()->randomFloat(2, 30, 400),
        ]);
    }
}
