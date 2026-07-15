<?php

namespace Tests\Unit\Models;

use App\Models\CookingService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CookingServiceModelTest extends TestCase
{
    use RefreshDatabase;

    // ── scopeAvailable ────────────────────────────────────────────────────────

    public function test_available_scope_only_returns_available_services(): void
    {
        CookingService::factory()->available()->create();
        CookingService::factory()->unavailable()->create();

        $results = CookingService::available()->get();

        $this->assertCount(1, $results);
        $this->assertTrue((bool) $results->first()->is_available);
    }

    public function test_available_scope_returns_empty_when_no_available_services(): void
    {
        CookingService::factory()->unavailable()->create();

        $this->assertCount(0, CookingService::available()->get());
    }

    // ── getCurrencySymbol ─────────────────────────────────────────────────────

    public function test_get_currency_symbol_for_idr(): void
    {
        $service = CookingService::factory()->make(['currency' => 'IDR']);
        $this->assertSame('Rp', $service->getCurrencySymbol());
    }

    public function test_get_currency_symbol_for_sgd(): void
    {
        $service = CookingService::factory()->sgd()->make();
        $this->assertSame('S$', $service->getCurrencySymbol());
    }

    public function test_get_currency_symbol_for_myr(): void
    {
        $service = CookingService::factory()->myr()->make();
        $this->assertSame('RM', $service->getCurrencySymbol());
    }

    // ── getFormattedPriceAttribute ────────────────────────────────────────────

    public function test_formatted_price_for_idr_has_no_decimals(): void
    {
        $service = CookingService::factory()->make(['currency' => 'IDR', 'price' => 150000]);
        $this->assertSame('Rp 150.000', $service->formatted_price);
    }

    public function test_formatted_price_for_sgd_has_two_decimal_places(): void
    {
        $service = CookingService::factory()->make(['currency' => 'SGD', 'price' => 50.00]);
        $this->assertSame('S$50.00', $service->formatted_price);
    }

    public function test_formatted_price_for_myr_has_two_decimal_places(): void
    {
        $service = CookingService::factory()->make(['currency' => 'MYR', 'price' => 80.50]);
        $this->assertSame('RM80.50', $service->formatted_price);
    }

    // ── averageRating ─────────────────────────────────────────────────────────

    public function test_average_rating_returns_zero_when_no_reviews(): void
    {
        $service = CookingService::factory()->create();
        $this->assertSame(0.0, $service->averageRating());
    }

    // ── getImageUrl ───────────────────────────────────────────────────────────

    public function test_get_image_url_returns_null_when_no_image_path(): void
    {
        $service = CookingService::factory()->make(['image_path' => null]);
        $this->assertNull($service->getImageUrl());
    }

    public function test_get_image_url_returns_storage_url_when_image_exists(): void
    {
        $service = CookingService::factory()->make(['image_path' => 'services/test.jpg']);
        $this->assertStringContainsString('services/test.jpg', $service->getImageUrl());
    }
}
