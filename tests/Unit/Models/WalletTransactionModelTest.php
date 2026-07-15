<?php

namespace Tests\Unit\Models;

use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTransactionModelTest extends TestCase
{
    use RefreshDatabase;

    // ── hadConversion() ───────────────────────────────────────────────────────

    public function test_had_conversion_returns_true_when_currencies_differ(): void
    {
        $tx = WalletTransaction::factory()->withConversion()->make();
        $this->assertTrue($tx->hadConversion());
    }

    public function test_had_conversion_returns_false_when_no_original_currency(): void
    {
        $tx = WalletTransaction::factory()->make([
            'currency'          => 'IDR',
            'original_currency' => null,
        ]);
        $this->assertFalse($tx->hadConversion());
    }

    public function test_had_conversion_returns_false_when_currencies_are_same(): void
    {
        $tx = WalletTransaction::factory()->make([
            'currency'          => 'IDR',
            'original_currency' => 'IDR',
        ]);
        $this->assertFalse($tx->hadConversion());
    }

    // ── getTypeLabelAttribute ─────────────────────────────────────────────────

    public function test_type_label_is_masuk_for_credit(): void
    {
        $tx = WalletTransaction::factory()->credit()->make();
        $this->assertSame('Masuk', $tx->type_label);
    }

    public function test_type_label_is_keluar_for_debit(): void
    {
        $tx = WalletTransaction::factory()->debit()->make();
        $this->assertSame('Keluar', $tx->type_label);
    }

    // ── getReferenceLabelAttribute ────────────────────────────────────────────

    public function test_reference_label_for_recipe_purchase(): void
    {
        $tx = WalletTransaction::factory()->make(['reference_type' => 'recipe_purchase']);
        $this->assertStringContainsString('Pembelian Resep', $tx->reference_label);
    }

    public function test_reference_label_for_service_order(): void
    {
        $tx = WalletTransaction::factory()->make(['reference_type' => 'service_order']);
        $this->assertStringContainsString('Pemesanan Service', $tx->reference_label);
    }

    public function test_reference_label_for_topup(): void
    {
        $tx = WalletTransaction::factory()->make(['reference_type' => 'topup']);
        $this->assertStringContainsString('Top-Up', $tx->reference_label);
    }

    public function test_reference_label_for_sale_credit(): void
    {
        $tx = WalletTransaction::factory()->make(['reference_type' => 'sale_credit']);
        $this->assertStringContainsString('Hasil Penjualan', $tx->reference_label);
    }

    public function test_reference_label_for_refund(): void
    {
        $tx = WalletTransaction::factory()->make(['reference_type' => 'refund']);
        $this->assertStringContainsString('Pengembalian Dana', $tx->reference_label);
    }

    public function test_reference_label_for_initial_credit(): void
    {
        $tx = WalletTransaction::factory()->make(['reference_type' => 'initial_credit']);
        $this->assertStringContainsString('Saldo Awal', $tx->reference_label);
    }

    public function test_reference_label_for_unknown_type_shows_generic_label(): void
    {
        $tx = WalletTransaction::factory()->make(['reference_type' => 'unknown']);
        $this->assertStringContainsString('Transaksi', $tx->reference_label);
    }
}
