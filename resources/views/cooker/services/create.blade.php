@extends('layouts.app')

@section('title', 'Create Service — Yumz')

@section('content')
@section('body-class', 'cs-bg')
<div class="max-w-[600px] mx-auto mt-20 animate-fadeInUp">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#2C1810] mb-1">🍽️ Create Cooking Service</h1>
        <p class="text-[#7A6248] text-sm">Offer cooking services that customers can order</p>
    </div>

    <div class="bg-white border border-[#E8DDD2] rounded-2xl p-8 shadow-sm">
        <form method="POST" action="{{ route('cooker.services.store') }}" enctype="multipart/form-data" id="service-form">
            @csrf

            <!-- Foto Makanan -->
            <div class="text-base font-semibold text-[#2C1810] mb-4 flex items-center gap-2">📸 Dish Photo</div>
            <div class="mb-5">
                <div class="border-2 border-dashed border-[#E8DDD2] bg-[#F5EFE6] rounded-xl p-8 text-center cursor-pointer transition-all duration-300 relative overflow-hidden hover:border-cs-orange hover:bg-cs-orange/4" id="image-upload-zone">
                    <div class="text-3xl mb-2 image-upload-icon">📷</div>
                    <div class="text-sm text-[#7A6248] image-upload-text">Click or drag dish photo here</div>
                    <div class="text-[0.72rem] text-[#9A7B5A] mt-1 image-upload-hint">Format: JPG, PNG, WebP — Max 2MB</div>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" id="image-input" class="absolute inset-0 opacity-0 cursor-pointer" required>
                </div>
                <img id="image-preview" class="max-w-full max-h-[200px] rounded-lg object-cover hidden mt-3" alt="Preview">
                @error('image')
                    <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div>
                @enderror
            </div>

            <hr class="border-t border-[#E8DDD2] my-6">

            <!-- Detail Service -->
            <div class="text-base font-semibold text-[#2C1810] mb-4 flex items-center gap-2">📋 Service Details</div>

            <div class="mb-5">
                <label for="title" class="block text-sm font-medium text-[#7A6248] mb-1.5">Food / Service Name</label>
                <input type="text" name="title" id="title" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-sm font-sans outline-none transition-all focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8" placeholder="example: Private Cooking Special Fried Rice" value="{{ old('title') }}" required>
                @error('title') <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                <!-- Kategori Makanan -->
                <div class="flex flex-col gap-1.5">
                    <label for="category" class="text-sm font-medium text-[#7A6248]">Food Category</label>
                    <select name="category" id="category" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-sm font-sans outline-none focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8" required>
                        <optgroup label="Asian">
                            <option value="indonesia" {{ old('category') === 'indonesia' ? 'selected' : '' }}>Indonesia</option>
                            <option value="malaysian" {{ old('category') === 'malaysian' ? 'selected' : '' }}>Malaysia</option>
                            <option value="chinese" {{ old('category') === 'chinese' ? 'selected' : '' }}>Chinese</option>
                            <option value="japanese" {{ old('category') === 'japanese' ? 'selected' : '' }}>Japanese</option>
                            <option value="korean" {{ old('category') === 'korean' ? 'selected' : '' }}>Korean</option>
                            <option value="thailand" {{ old('category') === 'thailand' ? 'selected' : '' }}>Thailand</option>
                            <option value="indian" {{ old('category') === 'indian' ? 'selected' : '' }}>Indian</option>
                        </optgroup>
                        <optgroup label="Western">
                            <option value="italian" {{ old('category') === 'italian' ? 'selected' : '' }}>Italian</option>
                            <option value="american" {{ old('category') === 'american' ? 'selected' : '' }}>American</option>
                            <option value="french" {{ old('category') === 'french' ? 'selected' : '' }}>French</option>
                            <option value="british" {{ old('category') === 'british' ? 'selected' : '' }}>British</option>
                        </optgroup>
                        <optgroup label="Dessert">
                            <option value="dessert" {{ old('category') === 'dessert' ? 'selected' : '' }}>Dessert</option>
                        </optgroup>
                    </select>
                    @error('category') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Halal Status -->
                <div class="flex flex-col gap-1.5">
                    <label for="is_halal" class="text-sm font-medium text-[#7A6248]">Halal Certification</label>
                    <select name="is_halal" id="is_halal" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-sm font-sans outline-none focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8" required>
                        <option value="1" {{ old('is_halal', '1') == '1' ? 'selected' : '' }}>Halal 🟢</option>
                        <option value="0" {{ old('is_halal') == '0' ? 'selected' : '' }}>Non-Halal 🔴</option>
                    </select>
                    @error('is_halal') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-5">
                <label for="description" class="block text-sm font-medium text-[#7A6248] mb-1.5">Food / Service Description</label>
                <textarea name="description" id="description" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-sm font-sans outline-none transition-all focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8 resize-vertical min-h-[120px]" placeholder="Describe what the customer gets from this service..." required>{{ old('description') }}</textarea>
                @error('description') <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div> @enderror
            </div>

            @php
                $currencySymbol = Auth::user()->getCurrencySymbol();
                $currencyCode = Auth::user()->currency ?? 'IDR';
                $defaultPrice = match($currencyCode) {
                    'SGD' => 10.00,
                    'MYR' => 30.00,
                    default => 100000,
                };
                $priceStep = match($currencyCode) {
                    'SGD', 'MYR' => 0.50,
                    default => 5000,
                };
                $placeholder = match($currencyCode) {
                    'SGD' => '12.50',
                    'MYR' => '35.00',
                    default => '150000',
                };
            @endphp
            <div class="mb-5">
                <label for="price" class="block text-sm font-medium text-[#7A6248] mb-1.5">Selling Price for Food / Service (in {{ $currencyCode }})</label>
                <div class="flex items-center">
                    <span class="px-4 py-3 bg-[#F5EFE6] border border-[#E8DDD2] border-r-0 rounded-l-lg text-[#7A6248] text-sm font-medium">{{ $currencySymbol }}</span>
                    <input type="number" name="price" id="price" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-r-lg text-[#2C1810] text-sm font-sans outline-none transition-all focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8" placeholder="{{ $placeholder }}" value="{{ old('price', $defaultPrice) }}" min="0" step="{{ $priceStep }}" required>
                </div>
                @error('price') <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div> @enderror
            </div>

            <div class="flex gap-3 mt-7">
                <button type="submit" class="px-6 py-3 bg-gradient-to-br from-cs-orange to-[#ff7337] text-white border-none rounded-lg text-sm font-semibold cursor-pointer transition-all shadow-[0_2px_10px_rgba(238,77,45,0.15)] hover:-translate-y-px hover:shadow-[0_4px_15px_rgba(238,77,45,0.25)]">✅ Create Service</button>
                <a href="{{ route('cooker.dashboard') }}" class="px-6 py-3 bg-white border border-[#E8DDD2] text-[#2C1810] rounded-lg text-sm font-semibold cursor-pointer transition-all hover:bg-[#F5EFE6] hover:border-slate-300 inline-flex items-center justify-center">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const imageInput = document.getElementById('image-input');
    const imagePreview = document.getElementById('image-preview');
    const uploadZone = document.getElementById('image-upload-zone');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                imagePreview.src = ev.target.result;
                imagePreview.style.display = 'block';
                uploadZone.querySelector('.image-upload-icon').style.display = 'none';
                uploadZone.querySelector('.image-upload-text').textContent = file.name;
                uploadZone.querySelector('.image-upload-hint').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
