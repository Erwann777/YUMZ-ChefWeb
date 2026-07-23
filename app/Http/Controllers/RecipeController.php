<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    // ===================== [READ] =====================
    public function create()
    {
        return view('cooker.recipes.create', [
            'user' => Auth::user(),
        ]);
    }

    // ===================== [CREATE] =====================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'ingredients' => ['required', 'string'],
            'steps' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'in:indonesia,malaysian,chinese,japanese,korean,thailand,indian,italian,american,french,british,dessert'],
            'is_halal' => ['required', 'boolean'],
        ]);

        $imagePath = $request->file('image')->store('recipes', 'public');

        $recipe = Recipe::create([
            'cooker_id'   => Auth::id(),
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'image_path'  => $imagePath,
            'ingredients' => $validated['ingredients'],
            'steps'       => $validated['steps'],
            'price'       => $validated['price'],
            'category'    => $validated['category'],
            'is_halal'    => $validated['is_halal'],
            'currency'    => Auth::user()->currency ?? 'IDR', // Auto-set from cooker's country
        ]);

        ActivityLog::log(
            'recipe_created',
            Auth::user()->name . " uploaded recipe: {$recipe->title}",
            Auth::id(), null, $request->ip()
        );

        return redirect()->route('cooker.dashboard')->with('success', "Recipe \"{$recipe->title}\" uploaded successfully!");
    }

    // ===================== [READ] =====================
    public function edit(Recipe $recipe)
    {
        if ($recipe->cooker_id !== Auth::id()) {
            abort(403);
        }

        return view('cooker.recipes.edit', [
            'user' => Auth::user(),
            'recipe' => $recipe,
        ]);
    }

    // ===================== [UPDATE] =====================
    public function update(Request $request, Recipe $recipe)
    {
        if ($recipe->cooker_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'ingredients' => ['required', 'string'],
            'steps' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_published' => ['sometimes', 'boolean'],
            'category' => ['required', 'string', 'in:indonesia,malaysian,chinese,japanese,korean,thailand,indian,italian,american,french,british,dessert'],
            'is_halal' => ['required', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($recipe->image_path) {
                Storage::disk('public')->delete($recipe->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('recipes', 'public');
        }

        $recipe->update([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'image_path'  => $validated['image_path'] ?? $recipe->image_path,
            'ingredients' => $validated['ingredients'],
            'steps'       => $validated['steps'],
            'price'       => $validated['price'],
            'is_published' => $request->boolean('is_published', true),
            'category'    => $validated['category'],
            'is_halal'    => $validated['is_halal'],
            'currency'    => Auth::user()->currency ?? 'IDR',
        ]);

        return redirect()->route('cooker.dashboard')->with('success', "Recipe \"{$recipe->title}\" updated successfully!");
    }

    // ===================== [DELETE] =====================
    public function destroy(Request $request, Recipe $recipe)
    {
        if ($recipe->cooker_id !== Auth::id()) {
            abort(403);
        }

        if ($recipe->image_path) {
            Storage::disk('public')->delete($recipe->image_path);
        }

        $title = $recipe->title;

        ActivityLog::log(
            'recipe_deleted',
            Auth::user()->name . " deleted recipe: {$title}",
            Auth::id(), null, $request->ip()
        );

        $recipe->delete();

        return redirect()->route('cooker.dashboard')->with('success', "Recipe \"{$title}\" deleted successfully.");
    }
}
