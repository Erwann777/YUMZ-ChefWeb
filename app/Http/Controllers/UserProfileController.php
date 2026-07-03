<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    /**
     * Show the user's profile page.
     */
    public function show(Request $request)
    {
        $user = $request->user();

        // Stats for customer
        $recipePurchasesCount = 0;
        $serviceOrdersCount   = 0;

        // Stats for cooker
        $recipesCount  = 0;
        $servicesCount = 0;
        $ordersCount   = 0;

        if ($user->isCustomer()) {
            $recipePurchasesCount = $user->recipePurchases()->count();
            $serviceOrdersCount   = $user->serviceOrders()->count();
        }

        if ($user->isCooker()) {
            $recipesCount  = $user->recipes()->count();
            $servicesCount = $user->cookingServices()->count();
            $ordersCount   = $user->cookerOrders()->count();
        }

        return view('profile.index', compact(
            'user',
            'recipePurchasesCount',
            'serviceOrdersCount',
            'recipesCount',
            'servicesCount',
            'ordersCount',
        ));
    }

    /**
     * Update the user's profile data.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio'      => ['nullable', 'string', 'max:500'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->bio   = $validated['bio'] ?? null;
        $user->phone = $validated['phone'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile')
            ->with('success', 'Profile successfully updated! ✅');
    }

    /**
     * Upload/update the user's profile photo.
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $user = $request->user();

        // Delete old photo if exists
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Store new photo
        $path = $request->file('photo')->store('profile-photos', 'public');
        $user->profile_photo_path = $path;
        $user->save();

        return redirect()->route('profile')
            ->with('success', 'Profile photo successfully updated! 📸');
    }
}
