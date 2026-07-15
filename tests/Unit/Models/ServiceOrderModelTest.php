<?php

namespace Tests\Unit\Models;

use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceOrderModelTest extends TestCase
{
    use RefreshDatabase;

    // ── getStatusBadgeAttribute ───────────────────────────────────────────────

    public function test_status_badge_is_a_string_for_pending(): void
    {
        $order = ServiceOrder::factory()->pending()->make();
        $this->assertIsString($order->status_badge);
        $this->assertSame('pending', $order->status_badge);
    }

    public function test_status_badge_is_a_string_for_confirmed(): void
    {
        $order = ServiceOrder::factory()->confirmed()->make();
        $this->assertIsString($order->status_badge);
        $this->assertSame('confirmed', $order->status_badge);
    }

    public function test_status_badge_is_a_string_for_completed(): void
    {
        $order = ServiceOrder::factory()->completed()->make();
        $this->assertIsString($order->status_badge);
        $this->assertSame('completed', $order->status_badge);
    }

    public function test_status_badge_is_a_string_for_cancelled(): void
    {
        $order = ServiceOrder::factory()->cancelled()->make();
        $this->assertIsString($order->status_badge);
        $this->assertSame('cancelled', $order->status_badge);
    }

    public function test_status_badge_differs_per_status(): void
    {
        $pending   = ServiceOrder::factory()->pending()->make()->status_badge;
        $confirmed = ServiceOrder::factory()->confirmed()->make()->status_badge;
        $completed = ServiceOrder::factory()->completed()->make()->status_badge;
        $cancelled = ServiceOrder::factory()->cancelled()->make()->status_badge;

        // All four statuses must resolve to distinct badge strings
        $this->assertCount(4, array_unique([$pending, $confirmed, $completed, $cancelled]));
    }

    // ── status values ─────────────────────────────────────────────────────────

    public function test_factory_creates_pending_status_by_default(): void
    {
        $order = ServiceOrder::factory()->make();
        $this->assertSame('pending', $order->status);
    }

    public function test_factory_completed_state_has_rating(): void
    {
        $order = ServiceOrder::factory()->completed()->make();
        $this->assertNotNull($order->rating);
        $this->assertSame('completed', $order->status);
    }

    public function test_factory_cancelled_state_sets_status(): void
    {
        $order = ServiceOrder::factory()->cancelled()->make();
        $this->assertSame('cancelled', $order->status);
    }

    // ── relationships ─────────────────────────────────────────────────────────

    public function test_order_belongs_to_a_customer(): void
    {
        $order = ServiceOrder::factory()->create();
        $this->assertInstanceOf(User::class, $order->customer);
    }

    public function test_order_belongs_to_a_cooker(): void
    {
        $order = ServiceOrder::factory()->create();
        $this->assertInstanceOf(User::class, $order->cooker);
    }
}
