<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recipe>
 */
class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    private static array $categories = [
        'indonesia', 'malaysian', 'chinese', 'japanese', 'korean',
        'thailand', 'indian', 'italian', 'american', 'french',
        'british', 'dessert',
    ];

    public function definition(): array
    {
        return [
            'cooker_id'   => User::factory()->cooker(),
            'title'       => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'image_path'  => 'recipes/test-image.jpg',
            'ingredients' => fake()->paragraph(),
            'steps'       => fake()->paragraph(),
            'price'       => fake()->randomFloat(2, 5000, 500000),
            'is_published'=> true,
            'category'    => fake()->randomElement(self::$categories),
            'is_halal'    => fake()->boolean(),
            'currency'    => 'IDR',
        ];
    }

    /**
     * Published recipe state.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }

    /**
     * Draft (unpublished) recipe state.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }

    /**
     * SGD currency recipe state.
     */
    public function sgd(): static
    {
        return $this->state(fn (array $attributes) => [
            'currency' => 'SGD',
            'price'    => fake()->randomFloat(2, 5, 100),
        ]);
    }

    /**
     * MYR currency recipe state.
     */
    public function myr(): static
    {
        return $this->state(fn (array $attributes) => [
            'currency' => 'MYR',
            'price'    => fake()->randomFloat(2, 10, 200),
        ]);
    }
}
