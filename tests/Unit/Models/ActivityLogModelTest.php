<?php

namespace Tests\Unit\Models;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogModelTest extends TestCase
{
    use RefreshDatabase;

    // ── log() static method ───────────────────────────────────────────────────

    public function test_log_creates_a_record_in_the_database(): void
    {
        $user = User::factory()->admin()->create();

        ActivityLog::log('login', 'Admin logged in', $user->id, null, '127.0.0.1');

        $this->assertDatabaseHas('activity_logs', [
            'action'      => 'login',
            'description' => 'Admin logged in',
            'user_id'     => $user->id,
            'ip_address'  => '127.0.0.1',
        ]);
    }

    public function test_log_works_without_user_id_and_ip(): void
    {
        ActivityLog::log('system_event', 'Automated event fired');

        $this->assertDatabaseHas('activity_logs', [
            'action'      => 'system_event',
            'description' => 'Automated event fired',
            'user_id'     => null,
            'ip_address'  => null,
        ]);
    }

    public function test_log_stores_target_id(): void
    {
        $admin  = User::factory()->admin()->create();
        $target = User::factory()->customer()->create();

        ActivityLog::log('user_suspended', 'Admin suspended user', $admin->id, $target->id, '10.0.0.1');

        $this->assertDatabaseHas('activity_logs', [
            'action'    => 'user_suspended',
            'user_id'   => $admin->id,
            'target_id' => $target->id,
        ]);
    }

    // ── getIconAttribute ──────────────────────────────────────────────────────

    public function test_get_icon_attribute_returns_key_emoji_for_login(): void
    {
        $log = new ActivityLog(['action' => 'login']);
        $this->assertSame('🔑', $log->icon);
    }

    public function test_get_icon_attribute_returns_bin_emoji_for_user_deleted(): void
    {
        $log = new ActivityLog(['action' => 'user_deleted']);
        $this->assertSame('🗑️', $log->icon);
    }

    public function test_get_icon_attribute_returns_default_clipboard_for_unknown_action(): void
    {
        $log = new ActivityLog(['action' => 'some_unknown_action']);
        $this->assertSame('📋', $log->icon);
    }

    public function test_get_icon_attribute_for_recipe_created(): void
    {
        $log = new ActivityLog(['action' => 'recipe_created']);
        $this->assertSame('🥘', $log->icon);
    }

    // ── getColorClassAttribute ────────────────────────────────────────────────

    public function test_get_color_class_is_green_for_login(): void
    {
        $log = new ActivityLog(['action' => 'login']);
        $this->assertSame('green', $log->color_class);
    }

    public function test_get_color_class_is_red_for_user_deleted(): void
    {
        $log = new ActivityLog(['action' => 'user_deleted']);
        $this->assertSame('red', $log->color_class);
    }

    public function test_get_color_class_is_gray_for_unknown_action(): void
    {
        $log = new ActivityLog(['action' => 'unknown_action']);
        $this->assertSame('gray', $log->color_class);
    }

    public function test_get_color_class_is_orange_for_role_changed(): void
    {
        $log = new ActivityLog(['action' => 'role_changed']);
        $this->assertSame('orange', $log->color_class);
    }

    // ── scopeAction ───────────────────────────────────────────────────────────

    public function test_scope_action_filters_by_action_type(): void
    {
        $user = User::factory()->admin()->create();
        ActivityLog::log('login', 'Logged in', $user->id);
        ActivityLog::log('logout', 'Logged out', $user->id);
        ActivityLog::log('login', 'Logged in again', $user->id);

        $results = ActivityLog::action('login')->get();

        $this->assertCount(2, $results);
        $this->assertTrue($results->every(fn ($log) => $log->action === 'login'));
    }
}
