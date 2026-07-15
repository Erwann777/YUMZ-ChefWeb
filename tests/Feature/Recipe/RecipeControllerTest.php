<?php

namespace Tests\Feature\Recipe;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    // ── create (GET) ──────────────────────────────────────────────────────────

    public function test_guest_cannot_access_create_recipe_page(): void
    {
        $response = $this->get(route('cooker.recipes.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_create_recipe_page(): void
    {
        $customer = User::factory()->customer()->create();
        $response = $this->actingAs($customer)->get(route('cooker.recipes.create'));
        $response->assertForbidden();
    }

    public function test_cooker_can_view_create_recipe_form(): void
    {
        $cooker = User::factory()->cooker()->create();
        $response = $this->actingAs($cooker)->get(route('cooker.recipes.create'));
        $response->assertOk();
    }

    // ── store (POST) ──────────────────────────────────────────────────────────

    public function test_cooker_can_store_a_valid_recipe(): void
    {
        Storage::fake('public');

        $cooker = User::factory()->cooker()->create();
        $image  = UploadedFile::fake()->create('recipe.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($cooker)->post(route('cooker.recipes.store'), [
            'title'       => 'Nasi Goreng Special',
            'description' => 'Delicious Indonesian fried rice with a special twist.',
            'image'       => $image,
            'ingredients' => 'Rice, Eggs, Soy Sauce, Garlic',
            'steps'       => 'Step 1: Cook rice. Step 2: Fry with eggs.',
            'price'       => 50000,
            'category'    => 'indonesia',
            'is_halal'    => 1,
        ]);

        $response->assertRedirect(route('cooker.dashboard'));
        $this->assertDatabaseHas('recipes', [
            'title'     => 'Nasi Goreng Special',
            'cooker_id' => $cooker->id,
        ]);
    }

    public function test_store_validation_fails_when_title_is_missing(): void
    {
        Storage::fake('public');

        $cooker = User::factory()->cooker()->create();
        $image  = UploadedFile::fake()->create('recipe.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($cooker)->post(route('cooker.recipes.store'), [
            'description' => 'Some description',
            'image'       => $image,
            'ingredients' => 'Ingredients',
            'steps'       => 'Steps',
            'price'       => 50000,
            'category'    => 'indonesia',
            'is_halal'    => 1,
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_store_validation_fails_for_invalid_category(): void
    {
        Storage::fake('public');

        $cooker = User::factory()->cooker()->create();
        $image  = UploadedFile::fake()->create('recipe.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($cooker)->post(route('cooker.recipes.store'), [
            'title'       => 'My Recipe',
            'description' => 'Description',
            'image'       => $image,
            'ingredients' => 'Ingredients',
            'steps'       => 'Steps',
            'price'       => 50000,
            'category'    => 'invalid_category',
            'is_halal'    => 1,
        ]);

        $response->assertSessionHasErrors('category');
    }

    public function test_store_validation_fails_when_price_is_negative(): void
    {
        Storage::fake('public');

        $cooker = User::factory()->cooker()->create();
        $image  = UploadedFile::fake()->create('recipe.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($cooker)->post(route('cooker.recipes.store'), [
            'title'       => 'My Recipe',
            'description' => 'Description',
            'image'       => $image,
            'ingredients' => 'Ingredients',
            'steps'       => 'Steps',
            'price'       => -100,
            'category'    => 'indonesia',
            'is_halal'    => 1,
        ]);

        $response->assertSessionHasErrors('price');
    }

    // ── edit (GET) ────────────────────────────────────────────────────────────

    public function test_cooker_can_access_their_own_recipe_edit_page(): void
    {
        $cooker = User::factory()->cooker()->create();
        $recipe = Recipe::factory()->create(['cooker_id' => $cooker->id]);

        $response = $this->actingAs($cooker)->get(route('cooker.recipes.edit', $recipe));
        $response->assertOk();
    }

    public function test_cooker_cannot_edit_another_cookers_recipe(): void
    {
        $cooker1 = User::factory()->cooker()->create();
        $cooker2 = User::factory()->cooker()->create();
        $recipe  = Recipe::factory()->create(['cooker_id' => $cooker1->id]);

        $response = $this->actingAs($cooker2)->get(route('cooker.recipes.edit', $recipe));
        $response->assertForbidden();
    }

    // ── update (PUT) ──────────────────────────────────────────────────────────

    public function test_cooker_can_update_their_own_recipe(): void
    {
        Storage::fake('public');

        $cooker = User::factory()->cooker()->create();
        $recipe = Recipe::factory()->create(['cooker_id' => $cooker->id]);

        $response = $this->actingAs($cooker)->put(route('cooker.recipes.update', $recipe), [
            'title'       => 'Updated Title',
            'description' => 'Updated description text here.',
            'ingredients' => 'Updated ingredients list',
            'steps'       => 'Updated steps list',
            'price'       => 75000,
            'category'    => 'japanese',
            'is_halal'    => 0,
        ]);

        $response->assertRedirect(route('cooker.dashboard'));
        $this->assertDatabaseHas('recipes', ['id' => $recipe->id, 'title' => 'Updated Title']);
    }

    public function test_cooker_cannot_update_another_cookers_recipe(): void
    {
        Storage::fake('public');

        $cooker1 = User::factory()->cooker()->create();
        $cooker2 = User::factory()->cooker()->create();
        $recipe  = Recipe::factory()->create(['cooker_id' => $cooker1->id]);

        $response = $this->actingAs($cooker2)->put(route('cooker.recipes.update', $recipe), [
            'title'       => 'Hacked Title',
            'description' => 'Hacked description.',
            'ingredients' => 'Hacked ingredients',
            'steps'       => 'Hacked steps',
            'price'       => 1,
            'category'    => 'italian',
            'is_halal'    => 0,
        ]);

        $response->assertForbidden();
    }

    // ── destroy (DELETE) ──────────────────────────────────────────────────────

    public function test_cooker_can_delete_their_own_recipe(): void
    {
        Storage::fake('public');

        $cooker = User::factory()->cooker()->create();
        $recipe = Recipe::factory()->create(['cooker_id' => $cooker->id, 'image_path' => null]);

        $response = $this->actingAs($cooker)->delete(route('cooker.recipes.destroy', $recipe));

        $response->assertRedirect(route('cooker.dashboard'));
        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }

    public function test_cooker_cannot_delete_another_cookers_recipe(): void
    {
        Storage::fake('public');

        $cooker1 = User::factory()->cooker()->create();
        $cooker2 = User::factory()->cooker()->create();
        $recipe  = Recipe::factory()->create(['cooker_id' => $cooker1->id]);

        $response = $this->actingAs($cooker2)->delete(route('cooker.recipes.destroy', $recipe));

        $response->assertForbidden();
        $this->assertDatabaseHas('recipes', ['id' => $recipe->id]);
    }

    public function test_guest_cannot_delete_a_recipe(): void
    {
        $cooker = User::factory()->cooker()->create();
        $recipe = Recipe::factory()->create(['cooker_id' => $cooker->id]);

        $response = $this->delete(route('cooker.recipes.destroy', $recipe));
        $response->assertRedirect(route('login'));
    }
}
