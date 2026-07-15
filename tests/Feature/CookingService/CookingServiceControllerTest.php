<?php

namespace Tests\Feature\CookingService;

use App\Models\CookingService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CookingServiceControllerTest extends TestCase
{
    use RefreshDatabase;

    // ── create (GET) ──────────────────────────────────────────────────────────

    public function test_guest_cannot_access_create_service_page(): void
    {
        $response = $this->get(route('cooker.services.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_create_service_page(): void
    {
        $customer = User::factory()->customer()->create();
        $response = $this->actingAs($customer)->get(route('cooker.services.create'));
        $response->assertForbidden();
    }

    public function test_cooker_can_view_create_service_form(): void
    {
        $cooker = User::factory()->cooker()->create();
        $response = $this->actingAs($cooker)->get(route('cooker.services.create'));
        $response->assertOk();
    }

    // ── store (POST) ──────────────────────────────────────────────────────────

    public function test_cooker_can_store_a_valid_service(): void
    {
        Storage::fake('public');

        $cooker = User::factory()->cooker()->create();
        $image  = UploadedFile::fake()->create('service.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($cooker)->post(route('cooker.services.store'), [
            'title'       => 'Private Cooking Class',
            'description' => str_repeat('A', 50), // min length satisfied
            'price'       => 250000,
            'image'       => $image,
            'category'    => 'indonesia',
            'is_halal'    => 1,
        ]);

        $response->assertRedirect(route('cooker.dashboard'));
        $this->assertDatabaseHas('cooking_services', [
            'title'     => 'Private Cooking Class',
            'cooker_id' => $cooker->id,
        ]);
    }

    public function test_store_validation_fails_when_title_is_missing(): void
    {
        Storage::fake('public');

        $cooker = User::factory()->cooker()->create();
        $image  = UploadedFile::fake()->create('service.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($cooker)->post(route('cooker.services.store'), [
            'description' => 'Some description',
            'price'       => 100000,
            'image'       => $image,
            'category'    => 'indonesia',
            'is_halal'    => 1,
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_store_validation_fails_for_invalid_category(): void
    {
        Storage::fake('public');

        $cooker = User::factory()->cooker()->create();
        $image  = UploadedFile::fake()->create('service.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($cooker)->post(route('cooker.services.store'), [
            'title'       => 'My Service',
            'description' => 'Description here',
            'price'       => 100000,
            'image'       => $image,
            'category'    => 'not_a_valid_category',
            'is_halal'    => 1,
        ]);

        $response->assertSessionHasErrors('category');
    }

    public function test_store_validation_fails_when_price_is_negative(): void
    {
        Storage::fake('public');

        $cooker = User::factory()->cooker()->create();
        $image  = UploadedFile::fake()->create('service.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($cooker)->post(route('cooker.services.store'), [
            'title'       => 'My Service',
            'description' => 'Description here',
            'price'       => -500,
            'image'       => $image,
            'category'    => 'indonesia',
            'is_halal'    => 1,
        ]);

        $response->assertSessionHasErrors('price');
    }

    // ── edit (GET) ────────────────────────────────────────────────────────────

    public function test_cooker_can_access_their_own_service_edit_page(): void
    {
        $cooker  = User::factory()->cooker()->create();
        $service = CookingService::factory()->create(['cooker_id' => $cooker->id]);

        $response = $this->actingAs($cooker)->get(route('cooker.services.edit', $service));
        $response->assertOk();
    }

    public function test_cooker_cannot_edit_another_cookers_service(): void
    {
        $cooker1 = User::factory()->cooker()->create();
        $cooker2 = User::factory()->cooker()->create();
        $service = CookingService::factory()->create(['cooker_id' => $cooker1->id]);

        $response = $this->actingAs($cooker2)->get(route('cooker.services.edit', $service));
        $response->assertForbidden();
    }

    // ── update (PUT) ──────────────────────────────────────────────────────────

    public function test_cooker_can_update_their_own_service(): void
    {
        Storage::fake('public');

        $cooker  = User::factory()->cooker()->create();
        $service = CookingService::factory()->create(['cooker_id' => $cooker->id]);

        $response = $this->actingAs($cooker)->put(route('cooker.services.update', $service), [
            'title'       => 'Updated Service Title',
            'description' => str_repeat('B', 50),
            'price'       => 300000,
            'category'    => 'japanese',
            'is_halal'    => 0,
        ]);

        $response->assertRedirect(route('cooker.dashboard'));
        $this->assertDatabaseHas('cooking_services', [
            'id'    => $service->id,
            'title' => 'Updated Service Title',
        ]);
    }

    public function test_cooker_cannot_update_another_cookers_service(): void
    {
        Storage::fake('public');

        $cooker1 = User::factory()->cooker()->create();
        $cooker2 = User::factory()->cooker()->create();
        $service = CookingService::factory()->create(['cooker_id' => $cooker1->id]);

        $response = $this->actingAs($cooker2)->put(route('cooker.services.update', $service), [
            'title'       => 'Hijacked Title',
            'description' => 'Hijacked description text',
            'price'       => 1,
            'category'    => 'italian',
            'is_halal'    => 0,
        ]);

        $response->assertForbidden();
    }

    // ── destroy (DELETE) ──────────────────────────────────────────────────────

    public function test_cooker_can_delete_their_own_service(): void
    {
        Storage::fake('public');

        $cooker  = User::factory()->cooker()->create();
        $service = CookingService::factory()->create([
            'cooker_id'  => $cooker->id,
            'image_path' => null,
        ]);

        $response = $this->actingAs($cooker)->delete(route('cooker.services.destroy', $service));

        $response->assertRedirect(route('cooker.dashboard'));
        $this->assertDatabaseMissing('cooking_services', ['id' => $service->id]);
    }

    public function test_cooker_cannot_delete_another_cookers_service(): void
    {
        Storage::fake('public');

        $cooker1 = User::factory()->cooker()->create();
        $cooker2 = User::factory()->cooker()->create();
        $service = CookingService::factory()->create(['cooker_id' => $cooker1->id]);

        $response = $this->actingAs($cooker2)->delete(route('cooker.services.destroy', $service));

        $response->assertForbidden();
        $this->assertDatabaseHas('cooking_services', ['id' => $service->id]);
    }

    public function test_guest_cannot_delete_a_service(): void
    {
        $cooker  = User::factory()->cooker()->create();
        $service = CookingService::factory()->create(['cooker_id' => $cooker->id]);

        $response = $this->delete(route('cooker.services.destroy', $service));
        $response->assertRedirect(route('login'));
    }
}
