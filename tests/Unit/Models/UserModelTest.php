<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    // ── Role helpers ──────────────────────────────────────────────────────────

    public function test_is_admin_returns_true_for_admin_role(): void
    {
        $user = User::factory()->admin()->make();
        $this->assertTrue($user->isAdmin());
    }

    public function test_is_admin_returns_false_for_customer_role(): void
    {
        $user = User::factory()->customer()->make();
        $this->assertFalse($user->isAdmin());
    }

    public function test_is_cooker_returns_true_for_cooker_role(): void
    {
        $user = User::factory()->cooker()->make();
        $this->assertTrue($user->isCooker());
    }

    public function test_is_cooker_returns_false_for_admin_role(): void
    {
        $user = User::factory()->admin()->make();
        $this->assertFalse($user->isCooker());
    }

    public function test_is_customer_returns_true_for_customer_role(): void
    {
        $user = User::factory()->customer()->make();
        $this->assertTrue($user->isCustomer());
    }

    public function test_is_customer_returns_false_for_cooker_role(): void
    {
        $user = User::factory()->cooker()->make();
        $this->assertFalse($user->isCustomer());
    }

    // ── Suspension ────────────────────────────────────────────────────────────

    public function test_is_suspended_returns_true_when_suspended(): void
    {
        $user = User::factory()->customer()->make(['is_suspended' => true]);
        $this->assertTrue($user->isSuspended());
    }

    public function test_is_suspended_returns_false_when_active(): void
    {
        $user = User::factory()->customer()->make(['is_suspended' => false]);
        $this->assertFalse($user->isSuspended());
    }

    // ── Initials ──────────────────────────────────────────────────────────────

    public function test_get_initials_returns_two_letters_for_single_name(): void
    {
        $user = User::factory()->make(['name' => 'Admin']);
        $this->assertSame('AD', $user->getInitials());
    }

    public function test_get_initials_returns_first_letters_of_two_words(): void
    {
        $user = User::factory()->make(['name' => 'John Doe']);
        $this->assertSame('JD', $user->getInitials());
    }

    public function test_get_initials_returns_uppercase(): void
    {
        $user = User::factory()->make(['name' => 'alice wonder']);
        $this->assertSame('AW', $user->getInitials());
    }

    // ── Role label ────────────────────────────────────────────────────────────

    public function test_get_role_label_admin(): void
    {
        $user = User::factory()->admin()->make();
        $this->assertSame('Admin', $user->getRoleLabel());
    }

    public function test_get_role_label_cooker(): void
    {
        $user = User::factory()->cooker()->make();
        $this->assertSame('Cooker', $user->getRoleLabel());
    }

    public function test_get_role_label_customer(): void
    {
        $user = User::factory()->customer()->make();
        $this->assertSame('Customer', $user->getRoleLabel());
    }

    public function test_get_role_label_unknown_defaults_to_user(): void
    {
        $user = User::factory()->make(['role' => 'unknown_role']);
        $this->assertSame('User', $user->getRoleLabel());
    }

    // ── Currency symbol ───────────────────────────────────────────────────────

    public function test_get_currency_symbol_for_idr(): void
    {
        $user = User::factory()->customer()->make(['currency' => 'IDR']);
        $this->assertSame('Rp', $user->getCurrencySymbol());
    }

    public function test_get_currency_symbol_for_sgd(): void
    {
        $user = User::factory()->singaporeCustomer()->make();
        $this->assertSame('S$', $user->getCurrencySymbol());
    }

    public function test_get_currency_symbol_for_myr(): void
    {
        $user = User::factory()->malaysiaCustomer()->make();
        $this->assertSame('RM', $user->getCurrencySymbol());
    }

    // ── Country helpers ───────────────────────────────────────────────────────

    public function test_get_country_name_for_id(): void
    {
        $user = User::factory()->customer()->make(['country' => 'ID']);
        $this->assertSame('Indonesia', $user->getCountryName());
    }

    public function test_get_country_name_for_sg(): void
    {
        $user = User::factory()->singaporeCustomer()->make();
        $this->assertSame('Singapore', $user->getCountryName());
    }

    public function test_get_country_name_for_my(): void
    {
        $user = User::factory()->malaysiaCustomer()->make();
        $this->assertSame('Malaysia', $user->getCountryName());
    }

    // ── Price formatting ──────────────────────────────────────────────────────

    public function test_format_price_for_idr_has_no_decimals(): void
    {
        $user = User::factory()->customer()->make(['currency' => 'IDR']);
        $this->assertSame('Rp 1.000.000', $user->formatPrice(1000000));
    }

    public function test_format_price_for_sgd_has_two_decimals(): void
    {
        $user = User::factory()->singaporeCustomer()->make();
        $this->assertSame('S$1,000.00', $user->formatPrice(1000));
    }

    public function test_format_price_for_myr_has_two_decimals(): void
    {
        $user = User::factory()->malaysiaCustomer()->make();
        $this->assertSame('RM1,000.00', $user->formatPrice(1000));
    }

    // ── Static helpers ────────────────────────────────────────────────────────

    public function test_get_initial_wallet_balance_for_indonesia(): void
    {
        $this->assertSame(10000000.00, User::getInitialWalletBalance('ID'));
    }

    public function test_get_initial_wallet_balance_for_singapore(): void
    {
        $this->assertSame(1000.00, User::getInitialWalletBalance('SG'));
    }

    public function test_get_initial_wallet_balance_for_malaysia(): void
    {
        $this->assertSame(3000.00, User::getInitialWalletBalance('MY'));
    }

    public function test_get_currency_from_country_for_indonesia(): void
    {
        $this->assertSame('IDR', User::getCurrencyFromCountry('ID'));
    }

    public function test_get_currency_from_country_for_singapore(): void
    {
        $this->assertSame('SGD', User::getCurrencyFromCountry('SG'));
    }

    public function test_get_currency_from_country_for_malaysia(): void
    {
        $this->assertSame('MYR', User::getCurrencyFromCountry('MY'));
    }
}
