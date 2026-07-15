<?php

namespace Tests\Unit\Services;

use App\Services\CurrencyService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CurrencyServiceTest extends TestCase
{
    private CurrencyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        // Flush cache so tests don't bleed into each other
        Cache::flush();
        $this->service = new CurrencyService();
    }

    // ── getSymbol ─────────────────────────────────────────────────────────────

    public function test_get_symbol_returns_s_dollar_for_sgd(): void
    {
        $this->assertSame('S$', $this->service->getSymbol('SGD'));
    }

    public function test_get_symbol_returns_rm_for_myr(): void
    {
        $this->assertSame('RM', $this->service->getSymbol('MYR'));
    }

    public function test_get_symbol_returns_rp_for_idr(): void
    {
        $this->assertSame('Rp', $this->service->getSymbol('IDR'));
    }

    public function test_get_symbol_defaults_to_rp_for_unknown_currency(): void
    {
        $this->assertSame('Rp', $this->service->getSymbol('USD'));
    }

    // ── formatAmount ──────────────────────────────────────────────────────────

    public function test_format_amount_idr_uses_dots_as_thousands_separator(): void
    {
        $formatted = $this->service->formatAmount(1500000, 'IDR');
        $this->assertSame('Rp 1.500.000', $formatted);
    }

    public function test_format_amount_idr_has_no_decimal_places(): void
    {
        $formatted = $this->service->formatAmount(5000, 'IDR');
        $this->assertSame('Rp 5.000', $formatted);
    }

    public function test_format_amount_sgd_has_two_decimal_places(): void
    {
        $formatted = $this->service->formatAmount(99.50, 'SGD');
        $this->assertSame('S$99.50', $formatted);
    }

    public function test_format_amount_sgd_uses_commas_as_thousands_separator(): void
    {
        $formatted = $this->service->formatAmount(1500.00, 'SGD');
        $this->assertSame('S$1,500.00', $formatted);
    }

    public function test_format_amount_myr_has_two_decimal_places(): void
    {
        $formatted = $this->service->formatAmount(250.75, 'MYR');
        $this->assertSame('RM250.75', $formatted);
    }

    public function test_format_amount_unknown_currency_uses_generic_format(): void
    {
        $formatted = $this->service->formatAmount(100.00, 'EUR');
        $this->assertStringContainsString('EUR', $formatted);
    }

    // ── convert ───────────────────────────────────────────────────────────────

    public function test_convert_returns_same_amount_when_from_and_to_are_identical(): void
    {
        $result = $this->service->convert(1000.00, 'IDR', 'IDR');
        $this->assertSame(1000.00, $result);
    }

    public function test_convert_sgd_to_sgd_returns_same_amount(): void
    {
        $result = $this->service->convert(50.00, 'SGD', 'SGD');
        $this->assertSame(50.00, $result);
    }

    public function test_convert_produces_a_positive_number_for_idr_to_sgd(): void
    {
        // 1 IDR ≈ 0.000087 SGD — regardless of live rate, result must be > 0
        $result = $this->service->convert(100000, 'IDR', 'SGD');
        $this->assertGreaterThan(0, $result);
    }

    public function test_convert_produces_a_positive_number_for_sgd_to_idr(): void
    {
        $result = $this->service->convert(10, 'SGD', 'IDR');
        $this->assertGreaterThan(0, $result);
    }

    // ── formatWithConversion ──────────────────────────────────────────────────

    public function test_format_with_conversion_same_currency_returns_same_currency_true(): void
    {
        $result = $this->service->formatWithConversion(50000, 'IDR', 'IDR');

        $this->assertTrue($result['same_currency']);
        $this->assertNull($result['original']);
        $this->assertStringContainsString('Rp', $result['primary']);
    }

    public function test_format_with_conversion_different_currencies_returns_same_currency_false(): void
    {
        $result = $this->service->formatWithConversion(100000, 'IDR', 'SGD');

        $this->assertFalse($result['same_currency']);
        $this->assertNotNull($result['original']);
        $this->assertStringContainsString('S$', $result['primary']);
        $this->assertStringContainsString('Rp', $result['original']);
    }

    public function test_format_with_conversion_normalises_lowercase_currency_codes(): void
    {
        // Should not throw; lowercase should be treated same as uppercase
        $result = $this->service->formatWithConversion(50000, 'idr', 'idr');
        $this->assertTrue($result['same_currency']);
    }

    // ── priceBlockFor ─────────────────────────────────────────────────────────

    public function test_price_block_for_returns_correct_structure(): void
    {
        $item = (object) ['price' => 150000, 'currency' => 'IDR'];
        $result = $this->service->priceBlockFor($item, 'IDR');

        $this->assertArrayHasKey('primary', $result);
        $this->assertArrayHasKey('original', $result);
        $this->assertArrayHasKey('same_currency', $result);
    }

    public function test_price_block_for_same_currency_shows_no_original(): void
    {
        $item = (object) ['price' => 100000, 'currency' => 'IDR'];
        $result = $this->service->priceBlockFor($item, 'IDR');

        $this->assertNull($result['original']);
        $this->assertTrue($result['same_currency']);
    }

    // ── getAllRatesForFrontend ─────────────────────────────────────────────────

    public function test_get_all_rates_for_frontend_returns_required_keys(): void
    {
        $result = $this->service->getAllRatesForFrontend();

        $this->assertArrayHasKey('rates', $result);
        $this->assertArrayHasKey('updated', $result);
        $this->assertArrayHasKey('source', $result);
    }

    public function test_get_all_rates_includes_idr_base_rate_of_one(): void
    {
        $result = $this->service->getAllRatesForFrontend();
        $this->assertArrayHasKey('IDR', $result['rates']);
        $this->assertSame(1.0, $result['rates']['IDR']);
    }
}
