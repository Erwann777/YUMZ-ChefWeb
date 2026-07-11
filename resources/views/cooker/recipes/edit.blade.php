@extends('layouts.app')

@section('title', 'Edit Recipe — Yumz')

@section('content')
@section('body-class', 'cs-bg')
<div class="max-w-[700px] mx-auto animate-fadeInUp mt-20">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#2C1810] mb-1"> Edit Recipe</h1>
        <p class="text-[#7A6248] text-sm">Update recipe "{{ $recipe->title }}"</p>
    </div>

    <div class="bg-white border border-[#E8DDD2] rounded-2xl p-8 shadow-sm">
        <form method="POST" action="{{ route('cooker.recipes.update', $recipe) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="text-base font-semibold text-[#2C1810] mb-4 flex items-center gap-2"> Food Photo</div>
            <div class="mb-5">
                @if($recipe->image_path)
                    <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->title }}" class="max-w-full max-h-[200px] rounded-lg object-cover mb-3">
                @endif
                <div class="border-2 border-dashed border-[#E8DDD2] bg-[#F5EFE6] rounded-xl p-8 text-center cursor-pointer transition-all duration-300 relative overflow-hidden hover:border-cs-orange hover:bg-cs-orange/4">
                    <div class="text-sm text-[#7A6248]">Change photo (optional)</div>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="absolute inset-0 opacity-0 cursor-pointer">
                </div>
                @error('image') <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div> @enderror
            </div>

            <hr class="border-t border-[#E8DDD2] my-6">
            <div class="text-base font-semibold text-[#2C1810] mb-4 flex items-center gap-2">Recipe Details</div>

            <div class="mb-5">
                <label for="title" class="block text-sm font-medium text-[#7A6248] mb-1.5">Recipe Title</label>
                <input type="text" name="title" id="title" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-sm font-sans outline-none transition-all focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8" value="{{ old('title', $recipe->title) }}" required>
                @error('title') <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                <!-- Kategori Makanan -->
                <div class="flex flex-col gap-1.5">
                    <label for="category" class="text-sm font-medium text-[#7A6248]">Food Category</label>
                    <select name="category" id="category" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-sm font-sans outline-none focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8" required>
                        <optgroup label="Asian">
                            <option value="indonesia" {{ old('category', $recipe->category) === 'indonesia' ? 'selected' : '' }}>Indonesia</option>
                            <option value="malaysian" {{ old('category', $recipe->category) === 'malaysian' ? 'selected' : '' }}>Malaysia</option>
                            <option value="chinese" {{ old('category', $recipe->category) === 'chinese' ? 'selected' : '' }}>Chinese</option>
                            <option value="japanese" {{ old('category', $recipe->category) === 'japanese' ? 'selected' : '' }}>Japanese</option>
                            <option value="korean" {{ old('category', $recipe->category) === 'korean' ? 'selected' : '' }}>Korean</option>
                            <option value="thailand" {{ old('category', $recipe->category) === 'thailand' ? 'selected' : '' }}>Thailand</option>
                            <option value="indian" {{ old('category', $recipe->category) === 'indian' ? 'selected' : '' }}>Indian</option>
                        </optgroup>
                        <optgroup label="Western">
                            <option value="italian" {{ old('category', $recipe->category) === 'italian' ? 'selected' : '' }}>Italian</option>
                            <option value="american" {{ old('category', $recipe->category) === 'american' ? 'selected' : '' }}>American</option>
                            <option value="french" {{ old('category', $recipe->category) === 'french' ? 'selected' : '' }}>French</option>
                            <option value="british" {{ old('category', $recipe->category) === 'british' ? 'selected' : '' }}>British</option>
                        </optgroup>
                        <optgroup label="Dessert">
                            <option value="dessert" {{ old('category', $recipe->category) === 'dessert' ? 'selected' : '' }}>Dessert</option>
                        </optgroup>
                    </select>
                </div>

                <!-- Halal Status -->
                <div class="flex flex-col gap-1.5">
                    <label for="is_halal" class="text-sm font-medium text-[#7A6248]">Halal Certification</label>
                    <select name="is_halal" id="is_halal" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-sm font-sans outline-none focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8" required>
                        <option value="1" {{ old('is_halal', $recipe->is_halal ? '1' : '0') == '1' ? 'selected' : '' }}>Halal </option>
                        <option value="0" {{ old('is_halal', $recipe->is_halal ? '1' : '0') == '0' ? 'selected' : '' }}>Non-Halal </option>
                    </select>
                </div>
            </div>

            <div class="mb-5">
                <label for="description" class="block text-sm font-medium text-[#7A6248] mb-1.5">Description</label>
                <textarea name="description" id="description" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-sm font-sans outline-none transition-all focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8 resize-vertical min-h-[100px]" required>{{ old('description', $recipe->description) }}</textarea>
                @error('description') <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div> @enderror
            </div>

            <hr class="border-t border-[#E8DDD2] my-6">
            <div class="text-base font-semibold text-[#2C1810] mb-4 flex items-center gap-2"> Ingredients</div>

            <div class="mb-5">
                <textarea name="ingredients" id="ingredients" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-sm font-sans outline-none transition-all focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8 resize-vertical min-h-[150px]" required>{{ old('ingredients', $recipe->ingredients) }}</textarea>
                @error('ingredients') <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div> @enderror
            </div>

            <hr class="border-t border-[#E8DDD2] my-6">
            <div class="text-base font-semibold text-[#2C1810] mb-4 flex items-center gap-2"> Preparation Steps</div>

            <div class="mb-5">
                <textarea name="steps" id="steps" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-sm font-sans outline-none transition-all focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8 resize-vertical min-h-[150px]" required>{{ old('steps', $recipe->steps) }}</textarea>
                @error('steps') <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div> @enderror
            </div>

            <hr class="border-t border-[#E8DDD2] my-6">
            <div class="text-base font-semibold text-[#2C1810] mb-4 flex items-center gap-2"> Price &amp; Status</div>

            @php
                $currencySymbol = Auth::user()->getCurrencySymbol();
                $currencyCode = Auth::user()->currency ?? 'IDR';
                $priceStep = match($currencyCode) {
                    'SGD', 'MYR' => 0.10,
                    default => 1000,
                };
            @endphp
            <div class="mb-5">
                <label for="price" class="block text-sm font-medium text-[#7A6248] mb-1.5">Price (in {{ $currencyCode }})</label>
                <div class="flex items-center">
                    <span class="px-4 py-3 bg-[#F5EFE6] border border-[#E8DDD2] border-r-0 rounded-l-lg text-[#7A6248] text-sm font-medium">{{ $currencySymbol }}</span>
                    <input type="number" name="price" id="price" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-r-lg text-[#2C1810] text-sm font-sans outline-none transition-all focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8" value="{{ old('price', $recipe->price) }}" min="0" step="{{ $priceStep }}" required>
                </div>
                @error('price') <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div> @enderror
            </div>

            <div class="mb-5">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $recipe->is_published) ? 'checked' : '' }} class="w-4 h-4 accent-cs-orange cursor-pointer">
                    <label for="is_published" class="text-sm text-[#7A6248] cursor-pointer select-none">Published (visible to customers)</label>
                </div>
            </div>

            <div class="flex gap-3 mt-7 justify-center flex-col">
                <button type="submit" class="px-6 py-3 bg-gradient-to-br from-cs-orange to-[#ff7337] text-white border-none rounded-lg text-sm font-semibold cursor-pointer transition-all shadow-[0_2px_10px_rgba(238,77,45,0.15)] hover:-translate-y-px hover:shadow-[0_4px_15px_rgba(238,77,45,0.25)]"> Save Changes</button>
                <a href="{{ route('cooker.dashboard') }}" class="px-6 py-3 bg-white border border-[#E8DDD2] text-[#2C1810] rounded-lg text-sm font-semibold cursor-pointer transition-all hover:bg-[#F5EFE6] hover:border-slate-300 inline-flex items-center justify-center">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
