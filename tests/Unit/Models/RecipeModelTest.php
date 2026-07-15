<?php

namespace Tests\Unit\Models;

use App\Models\Recipe;
use App\Models\RecipePurchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeModelTest extends TestCase
{
    use RefreshDatabase;

    // ── scopePublished ────────────────────────────────────────────────────────

    public function test_published_scope_only_returns_published_recipes(): void
    {
        Recipe::factory()->published()->create();
        Recipe::factory()->draft()->create();

        $results = Recipe::published()->get();

        $this->assertCount(1, $results);
        $this->assertTrue((bool) $results->first()->is_published);
    }

    public function test_published_scope_returns_nothing_when_no_published_recipes(): void
    {
        Recipe::factory()->draft()->create();

        $this->assertCount(0, Recipe::published()->get());
    }

    // ── getCurrencySymbol ─────────────────────────────────────────────────────

    public function test_get_currency_symbol_for_idr(): void
    {
        $recipe = Recipe::factory()->make(['currency' => 'IDR']);
        $this->assertSame('Rp', $recipe->getCurrencySymbol());
    }

    public function test_get_currency_symbol_for_sgd(): void
    {
        $recipe = Recipe::factory()->sgd()->make();
        $this->assertSame('S$', $recipe->getCurrencySymbol());
    }

    public function test_get_currency_symbol_for_myr(): void
    {
        $recipe = Recipe::factory()->myr()->make();
        $this->assertSame('RM', $recipe->getCurrencySymbol());
    }

    // ── getFormattedPriceAttribute ────────────────────────────────────────────

    public function test_formatted_price_for_idr_has_no_decimals_and_dots_as_thousands(): void
    {
        $recipe = Recipe::factory()->make(['currency' => 'IDR', 'price' => 75000]);
        $this->assertSame('Rp 75.000', $recipe->formatted_price);
    }

    public function test_formatted_price_for_sgd_has_two_decimal_places(): void
    {
        $recipe = Recipe::factory()->make(['currency' => 'SGD', 'price' => 12.50]);
        $this->assertSame('S$12.50', $recipe->formatted_price);
    }

    public function test_formatted_price_for_myr_has_two_decimal_places(): void
    {
        $recipe = Recipe::factory()->make(['currency' => 'MYR', 'price' => 35.00]);
        $this->assertSame('RM35.00', $recipe->formatted_price);
    }

    // ── averageRating ─────────────────────────────────────────────────────────

    public function test_average_rating_returns_zero_when_no_reviews(): void
    {
        $recipe = Recipe::factory()->create();
        $this->assertSame(0.0, $recipe->averageRating());
    }

    // ── isPurchasedBy ─────────────────────────────────────────────────────────

    public function test_is_purchased_by_returns_false_for_null_user(): void
    {
        $recipe = Recipe::factory()->create();
        $this->assertFalse($recipe->isPurchasedBy(null));
    }

    public function test_is_purchased_by_returns_true_for_the_cooker_who_owns_it(): void
    {
        $cooker = User::factory()->cooker()->create();
        $recipe = Recipe::factory()->create(['cooker_id' => $cooker->id]);

        $this->assertTrue($recipe->isPurchasedBy($cooker));
    }
}
