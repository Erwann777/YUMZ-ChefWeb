@extends('layouts.app')

@section('title', 'Edit Service — Yumz')

@section('content')
@section('body-class', 'cs-bg')
<div class="max-w-[600px] mx-auto mt-20 animate-fadeInUp">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#2C1810] mb-1">✏️ Edit Service</h1>
        <p class="text-[#7A6248] text-sm">Edit "{{ $service->title }}"</p>
    </div>

    <div class="bg-white border border-[#E8DDD2] rounded-2xl p-8 shadow-sm">
        <form method="POST" action="{{ route('cooker.services.update', $service) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Foto Makanan -->
            <div class="text-base font-semibold text-[#2C1810] mb-4 flex items-center gap-2">📸 Dish Photo</div>
            <div class="mb-5">
                @if($service->image_path)
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-[#9A7B5A] mb-1">Current Photo:</label>
                        <img src="{{ $service->getImageUrl() }}" alt="{{ $service->title }}" class="max-w-full max-h-[180px] rounded-lg object-cover border border-[#E8DDD2]">
                    </div>
                @endif
                <div class="border-2 border-dashed border-[#E8DDD2] bg-[#F5EFE6] rounded-xl p-8 text-center cursor-pointer transition-all duration-300 relative overflow-hidden hover:border-cs-orange hover:bg-cs-orange/4" id="image-upload-zone">
                    <div class="text-sm text-[#7A6248] image-upload-text">📷 Click to change photo (optional)</div>
                    <div class="text-[0.72rem] text-[#9A7B5A] mt-1 image-upload-hint">Format: JPG, PNG, WebP — Max 2MB</div>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" id="image-input" class="absolute inset-0 opacity-0 cursor-pointer">
                </div>
                <img id="image-preview" class="max-w-full max-h-[180px] rounded-lg object-cover hidden mt-3" alt="Preview">
                @error('image') <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div> @enderror
            </div>

            <hr class="border-t border-[#E8DDD2] my-6">

            <!-- Detail Service -->
            <div class="text-base font-semibold text-[#2C1810] mb-4 flex items-center gap-2">📋 Service Details</div>

            <div class="mb-5">
                <label for="title" class="block text-sm font-medium text-[#7A6248] mb-1.5">Food / Service Name</label>
                <input type="text" name="title" id="title" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-sm font-sans outline-none transition-all focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8" value="{{ old('title', $service->title) }}" required>
                @error('title') <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                <!-- Kategori Makanan -->
                <div class="flex flex-col gap-1.5">
                    <label for="category" class="text-sm font-medium text-[#7A6248]">Food Category</label>
                    <select name="category" id="category" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-sm font-sans outline-none focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8" required>
                        <optgroup label="Asian">
                            <option value="indonesia" {{ old('category', $service->category) === 'indonesia' ? 'selected' : '' }}>Indonesia</option>
                            <option value="malaysian" {{ old('category', $service->category) === 'malaysian' ? 'selected' : '' }}>Malaysia</option>
                            <option value="chinese" {{ old('category', $service->category) === 'chinese' ? 'selected' : '' }}>Chinese</option>
                            <option value="japanese" {{ old('category', $service->category) === 'japanese' ? 'selected' : '' }}>Japanese</option>
                            <option value="korean" {{ old('category', $service->category) === 'korean' ? 'selected' : '' }}>Korean</option>
                            <option value="thailand" {{ old('category', $service->category) === 'thailand' ? 'selected' : '' }}>Thailand</option>
                            <option value="indian" {{ old('category', $service->category) === 'indian' ? 'selected' : '' }}>Indian</option>
                        </optgroup>
                        <optgroup label="Western">
                            <option value="italian" {{ old('category', $service->category) === 'italian' ? 'selected' : '' }}>Italian</option>
                            <option value="american" {{ old('category', $service->category) === 'american' ? 'selected' : '' }}>American</option>
                            <option value="french" {{ old('category', $service->category) === 'french' ? 'selected' : '' }}>French</option>
                            <option value="british" {{ old('category', $service->category) === 'british' ? 'selected' : '' }}>British</option>
                        </optgroup>
                        <optgroup label="Dessert">
                            <option value="dessert" {{ old('category', $service->category) === 'dessert' ? 'selected' : '' }}>Dessert</option>
                        </optgroup>
                    </select>
                    @error('category') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Halal Status -->
                <div class="flex flex-col gap-1.5">
                    <label for="is_halal" class="text-sm font-medium text-[#7A6248]">Halal Certification</label>
                    <select name="is_halal" id="is_halal" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-sm font-sans outline-none focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8" required>
                        <option value="1" {{ old('is_halal', $service->is_halal ? '1' : '0') == '1' ? 'selected' : '' }}>Halal 🟢</option>
                        <option value="0" {{ old('is_halal', $service->is_halal ? '1' : '0') == '0' ? 'selected' : '' }}>Non-Halal 🔴</option>
                    </select>
                    @error('is_halal') <div class="text-xs text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-5">
                <label for="description" class="block text-sm font-medium text-[#7A6248] mb-1.5">Description</label>
                <textarea name="description" id="description" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-sm font-sans outline-none transition-all focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8 resize-vertical min-h-[120px]" required>{{ old('description', $service->description) }}</textarea>
                @error('description') <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div> @enderror
            </div>

            @php
                $currencySymbol = Auth::user()->getCurrencySymbol();
                $currencyCode = Auth::user()->currency ?? 'IDR';
                $priceStep = match($currencyCode) {
                    'SGD', 'MYR' => 0.50,
                    default => 5000,
                };
            @endphp
            <div class="mb-5">
                <label for="price" class="block text-sm font-medium text-[#7A6248] mb-1.5">Price (in {{ $currencyCode }})</label>
                <div class="flex items-center">
                    <span class="px-4 py-3 bg-[#F5EFE6] border border-[#E8DDD2] border-r-0 rounded-l-lg text-[#7A6248] text-sm font-medium">{{ $currencySymbol }}</span>
                    <input type="number" name="price" id="price" class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-r-lg text-[#2C1810] text-sm font-sans outline-none transition-all focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/8" value="{{ old('price', $service->price) }}" min="0" step="{{ $priceStep }}" required>
                </div>
                @error('price') <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div> @enderror
            </div>

            <div class="mb-5">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_available" id="is_available" value="1" {{ old('is_available', $service->is_available) ? 'checked' : '' }} class="w-4 h-4 accent-cs-orange cursor-pointer">
                    <label for="is_available" class="text-sm text-[#7A6248] cursor-pointer select-none">Available (can be ordered by customers)</label>
                </div>
            </div>

            <div class="flex gap-3 mt-7">
                <button type="submit" class="px-6 py-3 bg-gradient-to-br from-cs-orange to-[#ff7337] text-white border-none rounded-lg text-sm font-semibold cursor-pointer transition-all shadow-[0_2px_10px_rgba(238,77,45,0.15)] hover:-translate-y-px hover:shadow-[0_4px_15px_rgba(238,77,45,0.25)]">💾 Save Changes</button>
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
                uploadZone.querySelector('.image-upload-text').textContent = file.name;
                uploadZone.querySelector('.image-upload-hint').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
