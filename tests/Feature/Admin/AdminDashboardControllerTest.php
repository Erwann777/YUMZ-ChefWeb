<?php

namespace Tests\Feature\Admin;

use App\Models\CookingService;
use App\Models\Recipe;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    // ── Dashboard access ──────────────────────────────────────────────────────

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $customer = User::factory()->customer()->create();
        $response = $this->actingAs($customer)->get(route('admin.dashboard'));
        $response->assertForbidden();
    }

    public function test_cooker_cannot_access_admin_dashboard(): void
    {
        $cooker = User::factory()->cooker()->create();
        $response = $this->actingAs($cooker)->get(route('admin.dashboard'));
        $response->assertForbidden();
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_dashboard(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertOk();
    }

    // ── User management ───────────────────────────────────────────────────────

    public function test_admin_can_view_users_list(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->customer()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.users'));
        $response->assertOk();
    }

    public function test_admin_can_update_a_user(): void
    {
        $admin  = User::factory()->admin()->create();
        $target = User::factory()->customer()->create(['name' => 'Old Name']);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $target), [
            'name'  => 'New Name',
            'email' => $target->email,
            'role'  => 'customer',
        ]);

        $response->assertRedirect(route('admin.users'));
        $this->assertDatabaseHas('users', ['id' => $target->id, 'name' => 'New Name']);
    }

    public function test_admin_update_user_validation_fails_for_invalid_role(): void
    {
        $admin  = User::factory()->admin()->create();
        $target = User::factory()->customer()->create();

        $response = $this->actingAs($admin)->put(route('admin.users.update', $target), [
            'name'  => 'Valid Name',
            'email' => $target->email,
            'role'  => 'superuser', // invalid
        ]);

        $response->assertSessionHasErrors('role');
    }

    // ── Suspend / unsuspend ───────────────────────────────────────────────────

    public function test_admin_can_suspend_a_customer(): void
    {
        $admin    = User::factory()->admin()->create();
        $customer = User::factory()->customer()->create(['is_suspended' => false]);

        $response = $this->actingAs($admin)->post(route('admin.users.toggle-suspend', $customer));

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $customer->id, 'is_suspended' => true]);
    }

    public function test_admin_can_unsuspend_a_user(): void
    {
        $admin    = User::factory()->admin()->create();
        $customer = User::factory()->customer()->create(['is_suspended' => true]);

        $this->actingAs($admin)->post(route('admin.users.toggle-suspend', $customer));

        $this->assertDatabaseHas('users', ['id' => $customer->id, 'is_suspended' => false]);
    }

    public function test_admin_cannot_suspend_their_own_account(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.users.toggle-suspend', $admin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'is_suspended' => false]);
    }

    public function test_admin_cannot_suspend_another_admin(): void
    {
        $admin1 = User::factory()->admin()->create();
        $admin2 = User::factory()->admin()->create(['is_suspended' => false]);

        $response = $this->actingAs($admin1)->post(route('admin.users.toggle-suspend', $admin2));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin2->id, 'is_suspended' => false]);
    }

    // ── Delete user ───────────────────────────────────────────────────────────

    public function test_admin_can_delete_a_customer(): void
    {
        $admin    = User::factory()->admin()->create();
        $customer = User::factory()->customer()->create();

        $response = $this->actingAs($admin)->delete(route('admin.users.delete', $customer));

        $response->assertRedirect(route('admin.users'));
        $this->assertDatabaseMissing('users', ['id' => $customer->id]);
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->delete(route('admin.users.delete', $admin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    // ── Content management ────────────────────────────────────────────────────

    public function test_admin_can_view_content_page(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get(route('admin.content'));
        $response->assertOk();
    }

    public function test_admin_can_toggle_recipe_publish_status(): void
    {
        $admin  = User::factory()->admin()->create();
        $cooker = User::factory()->cooker()->create();
        $recipe = Recipe::factory()->create(['cooker_id' => $cooker->id, 'is_published' => true]);

        $response = $this->actingAs($admin)->put(route('admin.recipes.toggle-publish', $recipe));

        $response->assertRedirect();
        $this->assertDatabaseHas('recipes', ['id' => $recipe->id, 'is_published' => false]);
    }

    public function test_admin_can_toggle_service_availability(): void
    {
        $admin   = User::factory()->admin()->create();
        $cooker  = User::factory()->cooker()->create();
        $service = CookingService::factory()->create([
            'cooker_id'    => $cooker->id,
            'is_available' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.services.toggle-availability', $service));

        $response->assertRedirect();
        $this->assertDatabaseHas('cooking_services', ['id' => $service->id, 'is_available' => false]);
    }

    public function test_admin_can_delete_a_recipe(): void
    {
        $admin  = User::factory()->admin()->create();
        $cooker = User::factory()->cooker()->create();
        $recipe = Recipe::factory()->create(['cooker_id' => $cooker->id]);

        $response = $this->actingAs($admin)->delete(route('admin.recipes.delete', $recipe));

        $response->assertRedirect();
        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }

    public function test_admin_can_delete_a_service(): void
    {
        $admin   = User::factory()->admin()->create();
        $cooker  = User::factory()->cooker()->create();
        $service = CookingService::factory()->create(['cooker_id' => $cooker->id]);

        $response = $this->actingAs($admin)->delete(route('admin.services.delete', $service));

        $response->assertRedirect();
        $this->assertDatabaseMissing('cooking_services', ['id' => $service->id]);
    }

    // ── Orders ────────────────────────────────────────────────────────────────

    public function test_admin_can_view_orders_page(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get(route('admin.orders'));
        $response->assertOk();
    }

    public function test_admin_can_update_order_status(): void
    {
        $admin  = User::factory()->admin()->create();
        $order  = ServiceOrder::factory()->pending()->create();

        $response = $this->actingAs($admin)->put(route('admin.orders.update-status', $order), [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('service_orders', ['id' => $order->id, 'status' => 'confirmed']);
    }

    public function test_admin_order_status_validation_rejects_invalid_status(): void
    {
        $admin = User::factory()->admin()->create();
        $order = ServiceOrder::factory()->pending()->create();

        $response = $this->actingAs($admin)->put(route('admin.orders.update-status', $order), [
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors('status');
    }

    // ── Transactions & Activity Log ───────────────────────────────────────────

    public function test_admin_can_view_transactions_page(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get(route('admin.transactions'));
        $response->assertOk();
    }

    public function test_admin_can_view_activity_log_page(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get(route('admin.activity-log'));
        $response->assertOk();
    }
}
