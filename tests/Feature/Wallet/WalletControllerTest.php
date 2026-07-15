<?php

namespace Tests\Feature\Wallet;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletControllerTest extends TestCase
{
    use RefreshDatabase;

    // ── index (GET /wallet) ───────────────────────────────────────────────────

    public function test_guest_cannot_access_wallet_page(): void
    {
        $response = $this->get(route('wallet'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_wallet_page(): void
    {
        $user = User::factory()->customer()->create();
        $response = $this->actingAs($user)->get(route('wallet'));
        $response->assertOk();
    }

    public function test_cooker_can_view_wallet_page(): void
    {
        $cooker = User::factory()->cooker()->create();
        $response = $this->actingAs($cooker)->get(route('wallet'));
        $response->assertOk();
    }

    // ── topUp (POST /wallet/topup) ────────────────────────────────────────────

    public function test_top_up_increases_wallet_balance(): void
    {
        $user = User::factory()->customer()->create(['wallet_balance' => 100000]);

        $this->actingAs($user)->post(route('wallet.topup'), ['amount' => 50000]);

        $this->assertDatabaseHas('users', [
            'id'             => $user->id,
            'wallet_balance' => 150000,
        ]);
    }

    public function test_top_up_creates_a_wallet_transaction_record(): void
    {
        $user = User::factory()->customer()->create();

        $this->actingAs($user)->post(route('wallet.topup'), ['amount' => 75000]);

        $this->assertDatabaseHas('wallet_transactions', [
            'user_id'        => $user->id,
            'type'           => 'credit',
            'amount'         => 75000,
            'reference_type' => 'topup',
        ]);
    }

    public function test_top_up_redirects_back_with_success_message(): void
    {
        $user = User::factory()->customer()->create();

        $response = $this->actingAs($user)->post(route('wallet.topup'), ['amount' => 50000]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_top_up_validation_fails_when_amount_is_missing(): void
    {
        $user = User::factory()->customer()->create();

        $response = $this->actingAs($user)->post(route('wallet.topup'), []);

        $response->assertSessionHasErrors('amount');
    }

    public function test_top_up_validation_fails_when_amount_is_zero(): void
    {
        $user = User::factory()->customer()->create();

        $response = $this->actingAs($user)->post(route('wallet.topup'), ['amount' => 0]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_top_up_validation_fails_when_amount_exceeds_maximum(): void
    {
        $user = User::factory()->customer()->create();

        $response = $this->actingAs($user)->post(route('wallet.topup'), ['amount' => 999999999]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_top_up_validation_fails_when_amount_is_negative(): void
    {
        $user = User::factory()->customer()->create();

        $response = $this->actingAs($user)->post(route('wallet.topup'), ['amount' => -100]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_top_up_validation_fails_when_amount_is_not_numeric(): void
    {
        $user = User::factory()->customer()->create();

        $response = $this->actingAs($user)->post(route('wallet.topup'), ['amount' => 'abc']);

        $response->assertSessionHasErrors('amount');
    }

    public function test_guest_cannot_top_up_wallet(): void
    {
        $response = $this->post(route('wallet.topup'), ['amount' => 50000]);
        $response->assertRedirect(route('login'));
    }

    public function test_sgd_user_top_up_uses_sgd_currency(): void
    {
        $user = User::factory()->singaporeCustomer()->create();

        $this->actingAs($user)->post(route('wallet.topup'), ['amount' => 100]);

        $this->assertDatabaseHas('wallet_transactions', [
            'user_id'  => $user->id,
            'currency' => 'SGD',
            'amount'   => 100,
        ]);
    }
}
