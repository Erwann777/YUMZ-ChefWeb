@extends('layouts.app')

@section('title', $cooker->name . ' — CookSpace')
@section('styles')
<style>
    /* ── Shared Detail Page Styles ────────────────────────────── */
    .cs-card {
        background: #ffffff;
        border: 1px solid #EDE5DA;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(44,24,16,0.05);
    }
    .cs-title  { color: #2C1810; }
    .cs-label  { color: #5C4232; }
    .cs-text   { color: #3D2B1E; }
    .cs-muted  { color: #9A7B5A; }
    .cs-divider { border-color: #EDE5DA; }

    /* Review cards */
    .review-card {
        background: #ffffff;
        border: 1px solid #EDE5DA;
        border-radius: 14px;
        padding: 1.1rem 1.25rem;
        box-shadow: 0 1px 6px rgba(44,24,16,0.04);
        display: flex; gap: 1rem; align-items: flex-start;
        transition: box-shadow 0.2s ease;
    }
    .review-card:hover { box-shadow: 0 4px 14px rgba(44,24,16,0.08); }

    /* Item cards (food/recipe grid) */
    .food-card {
        background: #ffffff;
        border: 1px solid #EDE5DA;
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.22s ease;
        box-shadow: 0 1px 6px rgba(44,24,16,0.04);
    }
    .food-card:hover {
        border-color: rgba(198,124,78,0.4);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(44,24,16,0.09);
    }

    /* Chef profile banner */
    .chef-banner {
        background: linear-gradient(135deg, #ffffff, #FFFAF5);
        border: 1px solid #EDE5DA;
        border-radius: 20px;
        box-shadow: 0 2px 16px rgba(44,24,16,0.06);
    }

    /* Pricing card */
    .price-card {
        background: linear-gradient(135deg, #ffffff, #FBF7F3);
        border: 1.5px solid #DDD0C0;
        border-radius: 18px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(44,24,16,0.08);
        text-align: center;
    }

    /* Section title */
    .page-section-title {
        font-size: 1.05rem; font-weight: 700; color: #2C1810;
        display: flex; align-items: center; gap: 0.5rem;
        margin-bottom: 1.1rem;
    }
    .page-section-title::before {
        content: ''; display: inline-block; width: 4px; height: 1.1em;
        background: linear-gradient(180deg, #C67C4E, #e8a87c);
        border-radius: 4px;
    }

    /* Description block */
    .desc-block {
        background: #FFFAF7;
        border: 1px solid #EDE5DA;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        color: #3D2B1E;
        font-size: 0.92rem;
        line-height: 1.7;
    }
</style>
@endsection
@section('content')
@section('body-class', 'cs-bg')

@if(session('success'))
    <div class="bg-cs-green/10 border border-cs-green/20 text-cs-green px-4 py-3 rounded-xl text-sm mb-6 animate-fadeInUp mt-20">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="bg-red-500/10 border border-red-500/20 text-red-600 px-4 py-3 rounded-xl text-sm mb-6 animate-fadeInUp mt-20">❌ {{ session('error') }}</div>
@endif

<div class="chef-banner flex items-center gap-6 mt-20 mb-8 p-6 sm:p-8 max-sm:flex-col max-sm:text-center animate-fadeInUp">
    <!-- Avatar -->
    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-cs-orange to-[#ff7337] flex items-center justify-center text-3xl font-bold text-white flex-shrink-0 border-2 border-[#E8DDD2] overflow-hidden">
        @if($cooker->profile_photo_path)
            <img src="{{ $cooker->getProfilePhotoUrl() }}" alt="{{ $cooker->name }}" class="w-full h-full object-cover">
        @else
            {{ strtoupper(substr($cooker->name, 0, 1)) }}
        @endif
    </div>

    <div class="flex-1 min-w-0">
        <div class="text-2xl font-bold text-[#2C1810] mb-1 truncate flex items-center gap-2 flex-wrap">
            {{ $cooker->name }}
            <span class="text-xl leading-none">{{ $cooker->getCountryFlag() }}</span>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <div class="inline-flex items-center gap-1 text-[0.75rem] text-cs-orange bg-cs-orange-glow px-2.5 py-0.5 rounded-full border border-cs-orange/20 font-semibold">👨‍🍳 Cooker</div>
            <div class="inline-flex items-center gap-1 text-[0.68rem] text-[#5D3A1A] bg-[#F5EFE6] px-2 py-0.5 rounded-full border border-[#E0D5C8] font-medium">
                {{ $cooker->getCountryFlag() }} {{ $cooker->getCountryName() }}
            </div>
            <div class="inline-flex items-center gap-1 text-[0.65rem] text-[#C67C4E] bg-[#FDF5EE] px-2 py-0.5 rounded-full border border-[#E8D8C8] font-bold uppercase tracking-wide">
                {{ $cooker->currency ?? 'IDR' }}
            </div>
        </div>

        @if($cooker->bio)
            <p class="text-xs text-[#7A6248] italic mt-2 max-w-xl">"{{ $cooker->bio }}"</p>
        @endif

        <div class="text-[0.7rem] text-[#9A7B5A] mt-2">
            Joined {{ $cooker->created_at->format('M Y') }}
            &bull; Phone: {{ $cooker->phone ?? '-' }}
        </div>
    </div>

    <!-- Stats summary -->
    <div class="flex gap-6 shrink-0 max-sm:justify-center border-l border-[#E8DDD2] pl-6 max-sm:border-l-0 max-sm:pl-0">
        <div class="text-center">
            <span class="block text-2xl font-bold text-[#2C1810]">{{ $services->count() }}</span> 
            <span class="text-[0.65rem] text-[#7A6248] uppercase tracking-wider">Services</span>
        </div>
        <div class="text-center">
            <span class="block text-2xl font-bold text-[#2C1810]">{{ $recipes->count() }}</span> 
            <span class="text-[0.65rem] text-[#7A6248] uppercase tracking-wider">Recipes</span>
        </div>
        <div class="text-center">
            <span class="block text-2xl font-bold text-[#2C1810]">{{ number_format($cooker->averageCookerRating(), 1) }}</span> 
            <span class="text-[0.65rem] text-[#7A6248] uppercase tracking-wider">⭐ Rating</span>
        </div>
    </div>
</div>

<!-- Cooking Services Section -->
<div class="page-section-title animate-fadeInUp [animation-delay:0.05s] opacity-0">
    🍽️ Food Menu & Cooking Services
</div>

@if($services->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4 mb-12">
        @foreach($services as $service)
            <div class="food-card flex flex-col relative group animate-fadeInUp [animation-delay:0.1s] opacity-0">
                <!-- Halal/Non-halal badge -->
                <span class="absolute top-1.5 left-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-sm border
                    @if($service->is_halal)
                        bg-green-500/90 text-white border-green-400/20
                    @else
                        bg-red-500/90 text-white border-red-400/20
                    @endif">
                    {{ $service->is_halal ? 'Halal 🟢' : 'Non-Halal 🔴' }}
                </span>

                <!-- Category Badge -->
                <span class="absolute top-1.5 right-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-semibold px-1.5 py-0.5 rounded-full bg-slate-900/75 text-white backdrop-blur-sm capitalize truncate max-w-[50px] sm:max-w-none">
                    {{ $service->category }}
                </span>

                <div class="h-20 sm:h-28 bg-cs-bg-primary overflow-hidden relative">
                    @if($service->image_path)
                        <img src="{{ $service->getImageUrl() }}" alt="{{ $service->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-3xl bg-cs-orange/5 text-cs-orange">🍽️</div>
                    @endif
                    <x-price-display :item="$service" :viewerCurrency="$viewerCurrency" size="sm" color="text-[#D4A574]" class="absolute bottom-1.5 right-1.5 bg-[#2C1810]/85 backdrop-blur-md px-1.5 py-0.5 rounded-full" />
                </div>

                <div class="p-2 sm:p-3 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 mb-1">
                            <span class="text-[0.55rem] sm:text-[0.62rem] text-amber-500">⭐</span>
                            <span class="text-[0.55rem] sm:text-[0.62rem] font-bold text-[#2C1810]">{{ number_format($service->averageRating(), 1) }}</span>
                            <span class="text-[0.55rem] sm:text-[0.62rem] text-[#9A7B5A]">({{ $service->reviews()->count() }})</span>
                        </div>
                        <a href="{{ route('cookers.service', [$cooker, $service]) }}" class="no-underline">
                            <h3 class="text-[0.7rem] sm:text-xs font-bold text-[#2C1810] mb-0.5 hover:text-cs-orange transition-colors line-clamp-1">{{ $service->title }}</h3>
                        </a>
                        <p class="hidden sm:block text-[0.68rem] text-[#7A6248] line-clamp-2 mb-2 leading-tight">{{ $service->description }}</p>
                    </div>

                    <div class="pt-1.5 sm:pt-2 border-t border-[#E8DDD2] flex justify-between items-center">
                        <a href="{{ route('cookers.service', [$cooker, $service]) }}" class="text-[0.62rem] sm:text-[0.68rem] text-[#7A6248] hover:text-cs-orange no-underline font-semibold flex items-center gap-0.5">
                            Detail &rarr;
                        </a>
                        <a href="{{ route('services.order', [$cooker, $service]) }}" class="px-2 py-1 bg-gradient-to-br from-cs-orange to-[#ff7337] text-white rounded-lg text-[0.62rem] sm:text-[0.68rem] font-bold no-underline transition-all hover:-translate-y-px shadow-sm">
                            Order
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-12 bg-[#FBF7F3] border border-[#EDE5DA] rounded-2xl text-[#7A6248]">
        <span class="text-3xl mb-1 block">🍽️</span>
        <p class="text-sm font-semibold text-[#2C1810]">No cooking services available from this chef yet.</p>
    </div>
@endif

<!-- Recipes Section -->
<div class="page-section-title mt-10 animate-fadeInUp [animation-delay:0.12s] opacity-0">
    🥘 Secret Recipes (Unlock & Secrets)
</div>

@if($recipes->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4 mb-12">
        @foreach($recipes as $recipe)
            <div class="food-card flex flex-col relative group animate-fadeInUp [animation-delay:0.15s] opacity-0">
                <!-- Halal/Non-halal badge -->
                <span class="absolute top-1.5 left-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-sm border
                    @if($recipe->is_halal)
                        bg-green-500/90 text-white border-green-400/20
                    @else
                        bg-red-500/90 text-white border-red-400/20
                    @endif">
                    {{ $recipe->is_halal ? 'Halal 🟢' : 'Non-Halal 🔴' }}
                </span>

                <!-- Category Badge -->
                <span class="absolute top-1.5 right-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-semibold px-1.5 py-0.5 rounded-full bg-slate-900/75 text-white backdrop-blur-sm capitalize truncate max-w-[50px] sm:max-w-none">
                    {{ $recipe->category }}
                </span>

                <div class="h-20 sm:h-28 bg-cs-bg-primary overflow-hidden relative">
                    @if($recipe->image_path)
                        <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-3xl bg-cs-orange/5 text-cs-orange">🥘</div>
                    @endif
                    <x-price-display :item="$recipe" :viewerCurrency="$viewerCurrency" size="sm" color="text-[#D4A574]" class="absolute bottom-1.5 right-1.5 bg-[#2C1810]/85 backdrop-blur-md px-1.5 py-0.5 rounded-full" />
                </div>

                <div class="p-2 sm:p-3 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 mb-1">
                            <span class="text-[0.55rem] sm:text-[0.62rem] text-amber-500">⭐</span>
                            <span class="text-[0.55rem] sm:text-[0.62rem] font-bold text-[#2C1810]">{{ number_format($recipe->averageRating(), 1) }}</span>
                            <span class="text-[0.55rem] sm:text-[0.62rem] text-[#9A7B5A]">({{ $recipe->reviews()->count() }})</span>
                        </div>
                        <a href="{{ route('cookers.recipe', [$cooker, $recipe]) }}" class="no-underline">
                            <h3 class="text-[0.7rem] sm:text-xs font-bold text-[#2C1810] mb-0.5 hover:text-cs-orange transition-colors line-clamp-1">{{ $recipe->title }}</h3>
                        </a>
                        <p class="hidden sm:block text-[0.68rem] text-[#7A6248] line-clamp-2 mb-2 leading-tight">{{ $recipe->description }}</p>
                    </div>

                    <div class="pt-1.5 sm:pt-2 border-t border-[#E8DDD2] flex justify-between items-center">
                        <a href="{{ route('cookers.recipe', [$cooker, $recipe]) }}" class="text-[0.62rem] sm:text-[0.68rem] text-[#7A6248] hover:text-cs-orange no-underline font-semibold flex items-center gap-0.5">
                            Detail &rarr;
                        </a>
                        @if($recipe->isPurchasedBy($user))
                            <a href="{{ route('cookers.recipe', [$cooker, $recipe]) }}" class="px-2 py-1 bg-cs-green/12 border border-cs-green/20 text-cs-green rounded-lg text-[0.62rem] sm:text-[0.68rem] font-bold no-underline transition-all">
                                Open
                            </a>
                        @else
                            <a href="{{ route('cookers.recipe', [$cooker, $recipe]) }}" class="px-2 py-1 bg-[#2C1810] hover:bg-[#C67C4E] text-white rounded-lg text-[0.62rem] sm:text-[0.68rem] font-bold no-underline transition-all hover:-translate-y-px shadow-sm">
                                Unlock
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-12 bg-[#FBF7F3] border border-[#EDE5DA] rounded-2xl text-[#7A6248] mb-12">
        <span class="text-3xl mb-1 block">🥘</span>
        <p class="text-sm font-semibold text-[#2C1810]">No secret recipes available from this chef yet.</p>
    </div>
@endif
@endsection
