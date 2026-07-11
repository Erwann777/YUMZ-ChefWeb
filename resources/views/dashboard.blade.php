@extends('layouts.app')

@section('title', 'Dashboard Customer — Yumz')
@section('body-class', 'cs-bg')
@section('main-class', 'w-full px-4 sm:px-6 xl:px-8 py-6')

@section('styles')
<style>
    /* Customer Dashboard Premium Styles */
    .dash-stat { background:#ffffff; border:1px solid #EDE5DA; border-radius:16px; padding:1.25rem; box-shadow:0 2px 12px rgba(44,24,16,0.06); transition:all 0.25s ease; position:relative; overflow:hidden; }
    .dash-stat::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; border-radius:16px 16px 0 0; }
    .dash-stat:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(44,24,16,0.10); border-color:rgba(198,124,78,0.35); }
    .dash-stat.accent-orange::before { background:linear-gradient(90deg,#C67C4E,#e8a87c); }
    .dash-stat.accent-green::before  { background:linear-gradient(90deg,#22c55e,#4ade80); }
    .dash-stat.accent-blue::before   { background:linear-gradient(90deg,#3b82f6,#60a5fa); }
    .dash-stat.accent-purple::before { background:linear-gradient(90deg,#8b5cf6,#a78bfa); }
    .dash-stat-label { font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#9A7B5A; margin-bottom:0.4rem; }
    .dash-stat-value { font-size:1.7rem; font-weight:800; color:#2C1810; line-height:1; }
    .dash-stat-sub   { font-size:0.7rem; color:#B0906A; font-weight:500; margin-top:0.2rem; }
    .dash-stat-icon  { font-size:2.2rem; position:absolute; top:1rem; right:1.1rem; opacity:0.15; }
    .dash-section-title { font-size:1rem; font-weight:700; color:#2C1810; display:flex; align-items:center; gap:0.5rem; }
    .dash-section-title::before { content:''; display:inline-block; width:4px; height:1.15em; background:linear-gradient(180deg,#C67C4E,#e8a87c); border-radius:4px; }
    .dash-card { background:#ffffff; border:1px solid #EDE5DA; border-radius:18px; box-shadow:0 2px 12px rgba(44,24,16,0.05); overflow:hidden; }
    .dash-card-header { padding:0.9rem 1.2rem; border-bottom:1px solid #F0E8E0; display:flex; align-items:center; justify-content:space-between; background:linear-gradient(135deg,#FFFAF7,#FBF6F0); }
    .item-card { background:#ffffff; border:1px solid #EDE5DA; border-radius:14px; overflow:hidden; transition:all 0.22s ease; }
    .item-card:hover { border-color:rgba(198,124,78,0.4); transform:translateY(-3px); box-shadow:0 8px 20px rgba(44,24,16,0.09); }
    .item-card-title { font-size:0.82rem; font-weight:700; color:#2C1810; line-height:1.3; }
    .item-card-sub   { font-size:0.7rem; color:#9A7B5A; margin-top:1px; }
    .item-card-price { font-size:0.78rem; font-weight:700; color:#C67C4E; }
    .cooker-card { background:linear-gradient(135deg,#ffffff,#FFFAF5); border:1px solid #EDE5DA; border-radius:16px; padding:1.1rem; text-align:center; transition:all 0.22s ease; box-shadow:0 2px 8px rgba(44,24,16,0.05); }
    .cooker-card:hover { border-color:rgba(198,124,78,0.4); transform:translateY(-3px); box-shadow:0 8px 20px rgba(44,24,16,0.10); }
    .cooker-name { font-size:0.88rem; font-weight:700; color:#2C1810; }
    .cooker-meta { font-size:0.7rem; color:#9A7B5A; margin-top:2px; }
    .order-row { display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1.2rem; border-bottom:1px solid #F5EDE4; transition:background 0.15s ease; }
    .order-row:hover { background:#FFFAF6; }
    .order-row:last-child { border-bottom:none; }
    .order-title  { font-size:0.84rem; font-weight:600; color:#2C1810; }
    .order-meta   { font-size:0.7rem; color:#9A7B5A; }
    .order-amount { font-size:0.86rem; font-weight:700; color:#C67C4E; }
    .filter-sidebar { background:#ffffff; border:1px solid #EDE5DA; border-radius:18px; padding:1.1rem; box-shadow:0 2px 10px rgba(44,24,16,0.05); overflow-y:auto; max-h:calc(100vh - 120px); }
    .filter-sidebar::-webkit-scrollbar { width:6px; }
    .filter-sidebar::-webkit-scrollbar-track { background:transparent; }
    .filter-sidebar::-webkit-scrollbar-thumb { background:rgba(198,124,78,0.2); border-radius:99px; }
    .filter-sidebar::-webkit-scrollbar-thumb:hover { background:rgba(198,124,78,0.4); }
    .filter-label { font-size:0.68rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#9A7B5A; margin-bottom:0.4rem; display:block; }
    .filter-btn { width:100%; text-align:left; padding:0.4rem 0.7rem; border-radius:8px; border:none; font-size:0.8rem; font-weight:500; color:#5C4232; background:transparent; cursor:pointer; transition:all 0.15s ease; }
    .filter-btn:hover { background:rgba(198,124,78,0.08); color:#C67C4E; }
    .filter-btn.active-filter { background:rgba(198,124,78,0.12); color:#C67C4E; font-weight:700; }
    .status-badge { font-size:0.62rem; font-weight:700; padding:0.18rem 0.6rem; border-radius:99px; text-transform:uppercase; letter-spacing:0.05em; }
    .badge-pending  { background:rgba(251,146,60,0.12); color:#c2410c; border:1px solid rgba(251,146,60,0.2); }
    .badge-done     { background:rgba(34,197,94,0.10);  color:#15803d; border:1px solid rgba(34,197,94,0.2); }
    .badge-canceled { background:rgba(239,68,68,0.10);  color:#b91c1c; border:1px solid rgba(239,68,68,0.2); }
    .badge-process  { background:rgba(59,130,246,0.10); color:#1d4ed8; border:1px solid rgba(59,130,246,0.2); }
    .dash-search-wrap { background:#ffffff; border:1.5px solid #DDD0C0; border-radius:14px; padding:5px; box-shadow:0 2px 10px rgba(44,24,16,0.06); transition:all 0.22s ease; }
    .dash-search-wrap:focus-within { border-color:#C67C4E; box-shadow:0 0 0 3px rgba(198,124,78,0.12); }
    /* Section container cards */
    #hot-recommendations-container,
    #recipes-section-container,
    #all-foods-section,
    #cookers-grid-container,
    #transaction-section {
        background: #ffffff;
        border: 1px solid #EDE5DA;
        border-radius: 20px;
        padding: 1.5rem 1.5rem 1.75rem;
        box-shadow: 0 2px 16px rgba(44,24,16,0.055);
    }
    /* Section title bar above content */
    #hot-recommendations-container > :first-child,
    #recipes-section-container > :first-child,
    #all-foods-section > :first-child,
    #cookers-grid-container > :first-child,
    #transaction-section > :first-child {
        padding-bottom: 0.9rem;
        border-bottom: 1px solid #F0E8E0;
        margin-bottom: 1.25rem;
    }
    /* Upgrade h2 in sections */
    #hot-recommendations-container h2,
    #recipes-section-container h2,
    #all-foods-section h2,
    #cookers-grid-container h2 {
        font-size: 1.05rem;
        display: flex; align-items: center; gap: 0.5rem;
    }
    #hot-recommendations-container h2::before,
    #recipes-section-container h2::before,
    #all-foods-section h2::before,
    #cookers-grid-container h2::before {
        content: '';
        display: inline-block; width: 4px; height: 1.1em;
        background: linear-gradient(180deg, #C67C4E, #e8a87c);
        border-radius: 4px; flex-shrink: 0;
    }
</style>
@endsection

@section('content')

{{-- ─── Search & Welcome Header Banner ────────────────────────────── --}}
<div class="mb-6 mt-16 animate-fadeInUp">
    <div class="bg-gradient-to-br from-[#2C1810] to-[#3D2B1F] border border-[#E8DDD2] rounded-3xl p-6 sm:p-10 shadow-xl relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-[#C67C4E]/10 blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full bg-[#D4A574]/5 blur-3xl"></div>
        <div class="relative z-10 max-w-2xl">
            <div class="text-[#D4A574] text-xs sm:text-sm font-bold uppercase tracking-widest mb-1.5 flex items-center gap-2">
                <span>Customer Dashboard</span>
                @if(request('search'))
                    <span class="text-white/40 font-normal">| Search Results: "{{ request('search') }}"</span>
                @endif
            </div>
            <h1 class="text-3xl sm:text-4xl font-['Playfair_Display'] font-bold text-white mb-2 leading-tight">
                Welcome, <span class="text-[#C67C4E]">{{ $user->name }}</span>!
            </h1>
            <p class="text-white/70 text-sm mb-2 leading-relaxed">
                Find the best cooker in your neighborhood, enjoy heritage family recipes, and order cooking services directly to your table.
            </p>
        </div>
    </div>
</div>

{{-- ─── Search Bar ──────────────────────────────────────────────────── --}}
<div class="mb-6 animate-fadeInUp">
    <form action="{{ route('dashboard') }}" method="GET" class="m-0" id="search-form">
        <div class="dash-search-wrap flex items-center relative">
            <div class="pl-4 text-[#C67C4E] shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" name="search" id="global-search" value="{{ request('search') }}"
                placeholder="Search cooker, food, recipe, or cooking ingredients..."
                class="dash-search-input flex-1 border-none focus:outline-none focus:ring-0 ml-2">
            <button type="button" id="clear-search-btn" class="p-2 mr-2 text-[#9A7B5A] hover:text-[#2C1810] bg-transparent border-none cursor-pointer flex items-center justify-center hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <button type="submit" class="hidden md:flex shrink-0 items-center gap-1.5 px-5 py-2.5 bg-gradient-to-r from-[#C67C4E] to-[#D4895A] hover:from-[#B06A3E] hover:to-[#C67C4E] text-white text-xs font-bold rounded-xl border-none cursor-pointer transition-all shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Search
            </button>
        </div>
    </form>
</div>

{{-- ─── Search Results ──────────────────────────────────────────────── --}}
<div id="search-results-container">
    @if(request('search'))
        <div class="mb-8 animate-fadeInUp bg-white border border-[#E8DDD2] rounded-3xl p-6 sm:p-8 shadow-md">
            <div class="text-[#C67C4E] text-xs font-bold uppercase tracking-wider mb-4 flex items-center gap-2">
                <span>🔍 Search Results: "{{ request('search') }}"</span>
            </div>
            @if($searchedFoods->count() > 0 || $searchedCookers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($searchedFoods->count() > 0)
                        <div>
                            <h3 class="text-sm font-bold text-[#2C1810] mb-3"> Menu & Recipes:</h3>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($searchedFoods as $food)
                                    <div class="bg-white border border-[#E8DDD2] rounded-xl overflow-hidden transition-all hover:border-[#C67C4E]/30 hover:shadow-md flex flex-col relative">
                                        <div class="h-20 sm:h-24 bg-cs-bg-primary overflow-hidden relative">
                                            <span class="absolute top-1.5 left-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-md shadow-sm border {{ $food->is_halal ? 'bg-green-500/90 text-white border-green-400/20' : 'bg-red-500/90 text-white border-red-400/20' }}">
                                                {{ $food->is_halal ? 'Halal 🟢' : 'Non-Halal 🔴' }}
                                            </span>
                                            @if($food->image_path)
                                                <img src="{{ asset('storage/' . $food->image_path) }}" alt="{{ $food->title }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-2xl bg-cs-orange/5 text-cs-orange">{{ isset($food->is_available) ? '🍽️' : '🥘' }}</div>
                                            @endif
                                            <x-price-display :item="$food" :viewerCurrency="$viewerCurrency" size="sm" color="text-[#D4A574]" class="absolute bottom-1.5 right-1.5 bg-[#2C1810]/85 backdrop-blur-md px-1.5 py-0.5 rounded-full" />
                                        </div>
                                        <div class="p-2 flex-1 flex flex-col justify-between">
                                            <div>
                                                <div class="flex items-center gap-1 mb-1 justify-between">
                                                    <span class="text-[0.55rem] text-cs-orange font-bold truncate max-w-[70px]">Chef {{ $food->cooker->name }}</span>
                                                    <span class="text-[0.52rem] text-[#7A6248] bg-[#F5EFE6] border border-slate-100 px-1 py-0.5 rounded capitalize truncate">{{ $food->category }}</span>
                                                </div>
                                                <h3 class="text-[0.7rem] font-bold text-[#2C1810] mb-0.5 line-clamp-1">{{ $food->title }}</h3>
                                            </div>
                                            <div class="pt-1.5 border-t border-[#E8DDD2] flex justify-between items-center mt-1">
                                                <div class="flex items-center gap-0.5">
                                                    <span class="text-[0.55rem] text-amber-500">⭐</span>
                                                    <span class="text-[0.55rem] font-bold text-[#2C1810]">{{ number_format($food->averageRating(), 1) }}</span>
                                                </div>
                                                @if(isset($food->is_available))
                                                    <a href="{{ route('cookers.service', [$food->cooker, $food]) }}" class="text-[0.62rem] text-[#C67C4E] font-bold no-underline hover:underline">Order →</a>
                                                @else
                                                    <a href="{{ route('cookers.recipe', [$food->cooker, $food]) }}" class="text-[0.62rem] text-[#C67C4E] font-bold no-underline hover:underline">Unlock →</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if($searchedCookers->count() > 0)
                        <div>
                            <h3 class="text-sm font-bold text-[#2C1810] mb-3">Cooker / Chef:</h3>
                            <div class="flex flex-col gap-3">
                                @foreach($searchedCookers as $cooker)
                                    <div class="p-3 bg-[#F5EFE6] border border-slate-100 rounded-xl flex items-center justify-between gap-3">
                                        <div>
                                            <div class="font-bold text-xs text-[#2C1810]">{{ $cooker->name }}</div>
                                            <div class="text-[0.68rem] text-[#7A6248] mt-1">
                                                @php $allNames = $cooker->cookingServices->pluck('title')->concat($cooker->recipes->pluck('title'))->implode(', '); @endphp
                                                <span class="text-[#2C1810] font-medium">{{ $allNames ?: 'No menu yet' }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('cookers.show', $cooker) }}" class="px-2.5 py-1.5 border border-[#C67C4E] text-[#C67C4E] text-[0.65rem] font-bold rounded-lg no-underline hover:bg-[#C67C4E] hover:text-white transition-colors shrink-0">Profile</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-6 text-[#7A6248] text-xs">No matches found for "{{ request('search') }}".</div>
            @endif
        </div>
    @endif
</div>

{{-- ─── Mobile Filter Toggle ────────────────────────────────────────── --}}
<div class="lg:hidden mb-5 animate-fadeInUp">
    <button id="mobile-filter-toggle" onclick="toggleMobileFilter()"
        class="w-full flex items-center justify-between px-5 py-3 bg-white border border-[#E8DDD2] rounded-2xl text-sm font-semibold text-[#2C1810] shadow-sm hover:border-[#C67C4E]/40 transition-all">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#C67C4E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
            Filters & Categories
            <span id="mobile-filter-badge" class="hidden px-1.5 py-0.5 bg-[#C67C4E] text-white text-[0.6rem] font-bold rounded-full">!</span>
        </div>
        <svg id="mobile-filter-chevron" class="w-4 h-4 text-[#9A7B5A] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
</div>

{{-- ─── Mobile Filter Drawer ────────────────────────────────────────── --}}
<div id="mobile-filter-drawer" class="lg:hidden hidden mb-6 bg-white border border-[#EDE5DA] rounded-2xl shadow-sm overflow-hidden animate-fadeInUp">
    <div class="p-4 space-y-4">
        <div>
            <p class="text-[0.62rem] uppercase font-bold text-[#9A7B5A] mb-2">🍴 Content Type</p>
            <div class="flex gap-2">
                <button id="mobile-tab-services" onclick="switchMarketplaceTab('services-tab', null, this)" class="mobile-sidebar-tab flex-1 py-2.5 text-xs font-semibold rounded-xl border-none cursor-pointer bg-[#C67C4E] text-white transition-all"> Cooking Services</button>
                <button id="mobile-tab-recipes" onclick="switchMarketplaceTab('recipes-tab', null, this)" class="mobile-sidebar-tab flex-1 py-2.5 text-xs font-semibold rounded-xl border border-[#E8DDD2] cursor-pointer bg-transparent text-[#7A6248] transition-all"> Recipes</button>
            </div>
        </div>
        <div>
            <p class="text-[0.62rem] uppercase font-bold text-[#9A7B5A] mb-2">☪️ Halal</p>
            <div class="flex gap-2">
                <button class="mobile-halal-btn flex-1 py-2 text-xs font-semibold rounded-xl border-none cursor-pointer bg-[#F5EFE6] text-[#2C1810] transition-all" onclick="filterHalal('all', this)">All</button>
                <button class="mobile-halal-btn flex-1 py-2 text-xs font-semibold rounded-xl border border-[#E8DDD2] cursor-pointer bg-transparent text-[#7A6248] transition-all" onclick="filterHalal('halal', this)">🟢 Halal</button>
                <button class="mobile-halal-btn flex-1 py-2 text-xs font-semibold rounded-xl border border-[#E8DDD2] cursor-pointer bg-transparent text-[#7A6248] transition-all" onclick="filterHalal('nonhalal', this)">🔴 Non-Halal</button>
            </div>
        </div>
        <div>
            <p class="text-[0.62rem] uppercase font-bold text-[#9A7B5A] mb-2">💰 Price Range</p>
            <div class="flex gap-2">
                <input type="number" id="price-min-mobile" min="0" step="10000" placeholder="Min (Rp)" oninput="syncPriceFromMobile()" class="flex-1 px-3 py-2 border border-[#E8DDD2] rounded-lg text-xs outline-none focus:border-[#C67C4E] bg-white">
                <input type="number" id="price-max-mobile" min="0" step="10000" placeholder="Max (Rp)" oninput="syncPriceFromMobile()" class="flex-1 px-3 py-2 border border-[#E8DDD2] rounded-lg text-xs outline-none focus:border-[#C67C4E] bg-white">
            </div>
        </div>
        <div>
            <p class="text-[0.62rem] uppercase font-bold text-[#9A7B5A] mb-2">↕️ Sort By</p>
            <select id="sort-select-mobile" onchange="syncSortFromMobile()" class="w-full px-3 py-2 border border-[#EDE5DA] rounded-xl text-xs text-[#2C1810] bg-white outline-none focus:border-[#C67C4E] cursor-pointer">
                <option value="default">Default</option>
                <option value="rating-desc">⭐ Highest Rating</option>
                <option value="price-asc">💰 Lowest Price</option>
                <option value="price-desc">💎 Highest Price</option>
            </select>
        </div>
        <button onclick="resetAllFilters()" class="w-full py-2.5 bg-[#FBF7F3] border border-[#EDE5DA] text-[#7A6248] text-xs font-semibold rounded-xl hover:border-[#C67C4E]/40 hover:text-[#C67C4E] transition-all cursor-pointer">Reset All Filters</button>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     2-COLUMN LAYOUT: Sidebar + Main Content
═══════════════════════════════════════════════════════════════════ --}}
<div class="flex gap-6 items-start">

{{-- ═══════ LEFT SIDEBAR (Desktop only) ═══════ --}}
<aside class="w-64 xl:w-72 shrink-0 sticky top-20 hidden lg:block self-start">
    <div class="filter-sidebar overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-br from-[#2C1810] to-[#3D2B1F] px-4 py-3.5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-[#C67C4E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                    <span class="text-white font-bold text-sm">Filter & Explore</span>
                </div>
                <button onclick="resetAllFilters()" class="text-[0.65rem] text-[#C67C4E] hover:text-[#e8a87c] font-semibold border-none bg-transparent cursor-pointer transition-colors p-0">Reset</button>
            </div>
            <div id="sidebar-filter-active" class="hidden mt-2 text-[0.62rem] text-amber-300 font-medium"></div>
        </div>

        <div class="divide-y divide-cs-border">

            {{-- ── Wallet Widget ── --}}
            <div class="p-4">
                <a href="{{ route('wallet') }}" class="block no-underline group">
                    <div class="relative overflow-hidden rounded-2xl p-4" style="background: linear-gradient(135deg, #0bbd2cff 0%, #09cc0cff 100%);">
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute -top-6 -right-6 w-24 h-24 bg-white rounded-full"></div>
                        </div>
                        <div class="relative">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-white/70 text-[0.62rem] font-semibold uppercase tracking-wider flex items-center gap-1">
                                    <span>{{ Auth::user()->getCountryFlag() }}</span>
                                    {{ Auth::user()->getCountryName() }} · {{ Auth::user()->currency ?? 'IDR' }}
                                </div>
                                <span class="text-white/50 text-[0.6rem] bg-white/10 px-1.5 py-0.5 rounded-full">virtual</span>
                            </div>
                            <div class="text-white font-bold text-lg leading-tight">{{ Auth::user()->getFormattedWalletBalance() }}</div>
                            <div class="text-white/60 text-[0.62rem] mt-1 group-hover:text-white/80 transition-colors">View Wallet →</div>
                        </div>
                    </div>
                </a>
                <p class="text-[0.58rem] text-[#b4a89b] mt-2 text-center leading-snug">⚠️ Virtual money for simulation</p>
            </div>

            {{-- ── Navigasi Cepat ── --}}
            <div class="p-4">
                <p class="text-[0.6rem] uppercase tracking-widest font-bold text-[#9A7B5A] mb-2"> Quick Navigation</p>
                <nav class="flex flex-col gap-0.5">
                    <a href="#hot-recommendations-container" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-[0.75rem] font-medium text-[#7A6248] hover:bg-[#C67C4E]/8 hover:text-[#C67C4E] transition-all no-underline">🔥 Hot Recommendations</a>
                    <a href="#recipes-section-container" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-[0.75rem] font-medium text-[#7A6248] hover:bg-[#C67C4E]/8 hover:text-[#C67C4E] transition-all no-underline"> Secret Recipes</a>
                    <a href="#all-foods-section" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-[0.75rem] font-medium text-[#7A6248] hover:bg-[#C67C4E]/8 hover:text-[#C67C4E] transition-all no-underline">All Foods</a>
                    <a href="#cookers-grid-container" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-[0.75rem] font-medium text-[#7A6248] hover:bg-[#C67C4E]/8 hover:text-[#C67C4E] transition-all no-underline"> Top Cookers</a>
                    <a href="#transaction-section" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-[0.75rem] font-medium text-[#7A6248] hover:bg-[#C67C4E]/8 hover:text-[#C67C4E] transition-all no-underline"> My Activity</a>
                </nav>
            </div>

            {{-- ── Jenis Konten ── --}}
            <div class="p-4">
                <p class="text-[0.6rem] uppercase tracking-widest font-bold text-[#9A7B5A] mb-2"> Content Type</p>
                <div class="flex flex-col gap-1">
                    <button id="sidebar-tab-services" onclick="switchMarketplaceTab('services-tab', null, this)"
                        class="sidebar-tab-btn w-full text-left flex items-center gap-2 px-3 py-2.5 rounded-xl text-[0.75rem] font-semibold transition-all border-none cursor-pointer bg-[#C67C4E] text-white">
                        Cooking Services &amp; Menu
                    </button>
                    <button id="sidebar-tab-recipes" onclick="switchMarketplaceTab('recipes-tab', null, this)"
                        class="sidebar-tab-btn w-full text-left flex items-center gap-2 px-3 py-2.5 rounded-xl text-[0.75rem] font-semibold transition-all border-none cursor-pointer bg-transparent text-[#7A6248] hover:bg-[#F5EFE6]">
                        Secret Family Recipes
                    </button>
                </div>
            </div>

            {{-- ── Kategori Masakan ── --}}
            <div class="p-4">
                <p class="text-[0.6rem] uppercase tracking-widest font-bold text-[#9A7B5A] mb-2">Cuisine Category</p>
                <div class="space-y-2.5">
                    <button class="sidebar-cat-btn category-btn w-full text-left px-3 py-2 rounded-xl text-[0.75rem] font-bold bg-[#C67C4E] text-white border-none cursor-pointer transition-all" onclick="filterCategory('all', this)">
                         All Categories
                    </button>
                    <div>
                        <p class="text-[0.55rem] uppercase font-bold text-[#9A7B5A]/60 mb-1 pl-1"> Asia</p>
                        <div class="flex flex-col gap-0.5">
                            @foreach(['indonesia' => '🇮🇩 Indonesia', 'malaysian' => '🇲🇾 Malaysian', 'chinese' => '🇨🇳 Chinese', 'japanese' => '🇯🇵 Japanese', 'korean' => '🇰🇷 Korean', 'thailand' => '🇹🇭 Thai', 'indian' => '🇮🇳 Indian'] as $catVal => $catLabel)
                            <button class="sidebar-cat-btn category-btn w-full text-left px-3 py-1.5 rounded-lg text-[0.72rem] font-medium bg-transparent text-[#7A6248] hover:bg-[#C67C4E]/8 hover:text-[#C67C4E] border-none cursor-pointer transition-all" onclick="filterCategory('{{ $catVal }}', this)">
                                {{ $catLabel }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-[0.55rem] uppercase font-bold text-[#9A7B5A]/60 mb-1 pl-1">Western</p>
                        <div class="flex flex-col gap-0.5">
                            @foreach(['italian' => '🇮🇹 Italian', 'american' => '🇺🇸 American', 'french' => '🇫🇷 French', 'british' => '🇬🇧 British'] as $catVal => $catLabel)
                            <button class="sidebar-cat-btn category-btn w-full text-left px-3 py-1.5 rounded-lg text-[0.72rem] font-medium bg-transparent text-[#7A6248] hover:bg-[#C67C4E]/8 hover:text-[#C67C4E] border-none cursor-pointer transition-all" onclick="filterCategory('{{ $catVal }}', this)">
                                {{ $catLabel }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <p class="text-[0.55rem] uppercase font-bold text-[#9A7B5A]/60 mb-1 pl-1"> Others</p>
                        <button class="sidebar-cat-btn category-btn w-full text-left px-3 py-1.5 rounded-lg text-[0.72rem] font-medium bg-transparent text-[#7A6248] hover:bg-[#C67C4E]/8 hover:text-[#C67C4E] border-none cursor-pointer transition-all" onclick="filterCategory('dessert', this)">
                            Dessert
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── Status Halal ── --}}
            <div class="p-4">
                <p class="text-[0.6rem] uppercase tracking-widest font-bold text-[#9A7B5A] mb-2">Halal Status</p>
                <div class="flex flex-col gap-0.5">
                    <label class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl cursor-pointer hover:bg-[#F5EFE6] transition-all">
                        <input type="radio" name="halal-sidebar" value="all" checked onchange="filterHalal('all', this)" class="accent-[#C67C4E] w-3.5 h-3.5 shrink-0">
                        <span class="text-[0.75rem] font-medium text-[#2C1810]">All</span>
                    </label>
                    <label class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl cursor-pointer hover:bg-[#F5EFE6] transition-all">
                        <input type="radio" name="halal-sidebar" value="halal" onchange="filterHalal('halal', this)" class="accent-[#C67C4E] w-3.5 h-3.5 shrink-0">
                        <span class="text-[0.75rem] font-medium text-[#2C1810]"> Halal Only</span>
                    </label>
                    <label class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl cursor-pointer hover:bg-[#F5EFE6] transition-all">
                        <input type="radio" name="halal-sidebar" value="nonhalal" onchange="filterHalal('nonhalal', this)" class="accent-[#C67C4E] w-3.5 h-3.5 shrink-0">
                        <span class="text-[0.75rem] font-medium text-[#2C1810]"> Non-Halal</span>
                    </label>
                </div>
            </div>

            {{-- ── Range Harga ── --}}
            <div class="p-4">
                <p class="text-[0.6rem] uppercase tracking-widest font-bold text-[#9A7B5A] mb-2"> Price Range</p>
                <div class="space-y-2">
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label class="text-[0.58rem] text-[#9A7B5A] font-medium block mb-1">Min (Rp)</label>
                            <input type="number" id="price-min" min="0" step="10000" placeholder="0" oninput="applyFilters()"
                                class="w-full px-2.5 py-1.5 border border-[#E8DDD2] rounded-lg text-[0.72rem] text-[#2C1810] focus:border-[#C67C4E] outline-none transition-all bg-white">
                        </div>
                        <div class="flex-1">
                            <label class="text-[0.58rem] text-[#9A7B5A] font-medium block mb-1">Max (Rp)</label>
                            <input type="number" id="price-max" min="0" step="10000" placeholder="∞" oninput="applyFilters()"
                                class="w-full px-2.5 py-1.5 border border-[#E8DDD2] rounded-lg text-[0.72rem] text-[#2C1810] focus:border-[#C67C4E] outline-none transition-all bg-white">
                        </div>
                    </div>
                    {{-- Quick Price Presets --}}
                    <div class="grid grid-cols-2 gap-1">
                        <button onclick="setPriceRange(0, 50000)" class="price-preset-btn py-1.5 text-[0.62rem] bg-[#F5EFE6] border border-[#E8DDD2] text-[#7A6248] rounded-lg hover:border-[#C67C4E]/50 hover:text-[#C67C4E] hover:bg-[#C67C4E]/5 transition-all cursor-pointer">&lt; 50k</button>
                        <button onclick="setPriceRange(50000, 150000)" class="price-preset-btn py-1.5 text-[0.62rem] bg-[#F5EFE6] border border-[#E8DDD2] text-[#7A6248] rounded-lg hover:border-[#C67C4E]/50 hover:text-[#C67C4E] hover:bg-[#C67C4E]/5 transition-all cursor-pointer">50–150k</button>
                        <button onclick="setPriceRange(150000, 500000)" class="price-preset-btn py-1.5 text-[0.62rem] bg-[#F5EFE6] border border-[#E8DDD2] text-[#7A6248] rounded-lg hover:border-[#C67C4E]/50 hover:text-[#C67C4E] hover:bg-[#C67C4E]/5 transition-all cursor-pointer">150–500k</button>
                        <button onclick="setPriceRange(500000, 99999999)" class="price-preset-btn py-1.5 text-[0.62rem] bg-[#F5EFE6] border border-[#E8DDD2] text-[#7A6248] rounded-lg hover:border-[#C67C4E]/50 hover:text-[#C67C4E] hover:bg-[#C67C4E]/5 transition-all cursor-pointer">&gt; 500k</button>
                    </div>
                </div>
            </div>

            {{-- ── Rating Minimum ── --}}
            <div class="p-4">
                <p class="text-[0.6rem] uppercase tracking-widest font-bold text-[#9A7B5A] mb-2"> Minimum Rating</p>
                <div class="flex flex-col gap-0.5">
                    <button class="rating-filter-btn w-full text-left flex items-center gap-1.5 px-3 py-2 rounded-xl text-[0.72rem] font-semibold border-none cursor-pointer transition-all bg-white text-white" onclick="filterByRating(0, this)">🌟 All Ratings</button>
                    <button class="rating-filter-btn w-full text-left flex items-center gap-1.5 px-3 py-2 rounded-xl text-[0.72rem] font-medium border-none cursor-pointer transition-all bg-transparent text-[#7A6248] hover:bg-[#F5EFE6]" onclick="filterByRating(3, this)">⭐⭐⭐ 3.0+ &amp; Up</button>
                    <button class="rating-filter-btn w-full text-left flex items-center gap-1.5 px-3 py-2 rounded-xl text-[0.72rem] font-medium border-none cursor-pointer transition-all bg-transparent text-[#7A6248] hover:bg-[#F5EFE6]" onclick="filterByRating(3.5, this)">⭐⭐⭐½ 3.5+ &amp; Up</button>
                    <button class="rating-filter-btn w-full text-left flex items-center gap-1.5 px-3 py-2 rounded-xl text-[0.72rem] font-medium border-none cursor-pointer transition-all bg-transparent text-[#7A6248] hover:bg-[#F5EFE6]" onclick="filterByRating(4, this)">⭐⭐⭐⭐ 4.0+ &amp; Up</button>
                    <button class="rating-filter-btn w-full text-left flex items-center gap-1.5 px-3 py-2 rounded-xl text-[0.72rem] font-medium border-none cursor-pointer transition-all bg-transparent text-[#7A6248] hover:bg-[#F5EFE6]" onclick="filterByRating(4.5, this)">⭐⭐⭐⭐½ 4.5+ &amp; Up</button>
                </div>
            </div>

            {{-- ── Urutkan Berdasarkan ── --}}
            <div class="p-4">
                <p class="text-[0.6rem] uppercase tracking-widest font-bold text-[#9A7B5A] mb-2">Sort By</p>
                <select id="sort-select" onchange="applyFilters()"
                    class="w-full px-3 py-2 border border-[#E8DDD2] rounded-xl text-[0.75rem] font-medium text-[#2C1810] focus:border-[#C67C4E] outline-none transition-all bg-white cursor-pointer">
                    <option value="default">Default</option>
                    <option value="rating-desc">Highest Rating</option>
                    <option value="price-asc"> Lowest Price</option>
                    <option value="price-desc"> Highest Price</option>
                </select>
            </div>

            {{-- ── Filter Status ── --}}
            <div class="p-4">
                <div id="filter-status-box" class="hidden bg-amber-50 border border-amber-200/60 rounded-xl p-3 text-center">
                    <div id="filter-status-text" class="text-[0.7rem] text-amber-700 font-semibold mb-1"></div>
                    <button onclick="resetAllFilters()" class="text-[0.62rem] text-amber-600 hover:text-amber-800 border-none bg-transparent cursor-pointer underline transition-colors">Reset filter</button>
                </div>
            </div>

        </div>
    </div>
</aside>
{{-- ═══════ END SIDEBAR ═══════ --}}

{{-- ═══════ MAIN CONTENT ═══════ --}}
<div class="flex-1 min-w-0">

{{-- Active Shopee Food Style Order Tracker --}}
@php
    $activeOrder = $serviceOrders ? collect($serviceOrders)->whereIn('status', ['pending', 'confirmed'])->first() : null;
@endphp

@if($activeOrder)
<div id="shopee-food-tracker" class="mb-8 p-6 bg-gradient-to-br from-[#FFF5F0] via-white to-[#FFF9F6] border border-[#FFD0B8] rounded-3xl shadow-md relative overflow-hidden animate-fadeInUp" data-order-id="{{ $activeOrder->id }}">
    <!-- Top animation accent -->
    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-cs-orange via-orange-400 to-amber-500 animate-pulse"></div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-5 pb-4 border-b border-[#F5EADF]">
        <div>
            <div class="flex items-center gap-2">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cs-orange opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-cs-orange"></span>
                </span>
                <span class="text-[0.62rem] font-black uppercase tracking-widest text-cs-orange">YUMZ · LIVE</span>
            </div>
            <h3 class="text-base font-extrabold text-[#2C1810] mt-1">Status: <span id="tracker-status-text" class="text-cs-orange">Contacting Chef...</span></h3>
            <p class="text-xs text-[#7A6248] mt-0.5">Ordering <strong class="text-[#2C1810]">{{ $activeOrder->service->title }}</strong> from <strong class="text-[#2C1810]">Chef {{ $activeOrder->cooker->name }}</strong></p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold px-2.5 py-1 bg-white border border-[#E8DDD2] rounded-full text-[#7A6248] shadow-sm">ID: #{{ $activeOrder->id }}</span>
            <button onclick="requestNotificationPermission()" class="p-2 bg-white hover:bg-orange-50 text-cs-orange hover:text-cs-orange-hover border border-[#FFD0B8] rounded-xl cursor-pointer transition-all shadow-sm" title="Enable Desktop Notifications">
                🔔
            </button>
        </div>
    </div>

    <!-- Stepper progress bar -->
    <div class="relative py-6 max-w-2xl mx-auto">
        <!-- Connecting Line -->
        <div class="absolute top-1/2 left-0 right-0 h-1 bg-[#EDE5DA] -translate-y-1/2 rounded-full overflow-hidden">
            <div id="tracker-progress-line" class="h-full bg-gradient-to-r from-cs-orange to-orange-400 transition-all duration-700" style="width: 15%;"></div>
        </div>

        <!-- Step Points -->
        <div class="relative flex justify-between z-10">
            <!-- Step 1: Order Placed -->
            <div class="flex flex-col items-center">
                <div id="step-1-icon" class="w-10 h-10 rounded-full bg-white border-2 border-[#EDE5DA] flex items-center justify-center font-bold text-sm text-[#9A7B5A] transition-all duration-300">
                    📥
                </div>
                <span class="text-[0.7rem] font-bold text-[#2C1810] mt-2">Received</span>
                <span class="text-[0.55rem] text-[#9A7B5A] text-center max-w-[80px] mt-0.5 leading-tight">Awaiting Confirmation</span>
            </div>

            <!-- Step 2: Cooking -->
            <div class="flex flex-col items-center">
                <div id="step-2-icon" class="w-10 h-10 rounded-full bg-white border-2 border-[#EDE5DA] flex items-center justify-center font-bold text-sm text-[#9A7B5A] transition-all duration-300">
                    🍳
                </div>
                <span class="text-[0.7rem] font-bold text-[#2C1810] mt-2">Preparing</span>
                <span class="text-[0.55rem] text-[#9A7B5A] text-center max-w-[85px] mt-0.5 leading-tight">Cooking</span>
            </div>

            <!-- Step 3: Ready -->
            <div class="flex flex-col items-center">
                <div id="step-3-icon" class="w-10 h-10 rounded-full bg-white border-2 border-[#EDE5DA] flex items-center justify-center font-bold text-sm text-[#9A7B5A] transition-all duration-300">
                    🎉
                </div>
                <span class="text-[0.7rem] font-bold text-[#2C1810] mt-2">Ready</span>
                <span class="text-[0.55rem] text-[#9A7B5A] text-center max-w-[80px] mt-0.5 leading-tight">Ready to Serve</span>
            </div>
        </div>
    </div>

    <!-- Active Details Banner -->
    <div id="tracker-banner" class="mt-6 p-4 bg-white border border-[#F5EADF] rounded-2xl flex items-center gap-3 shadow-sm">
        <span id="tracker-banner-icon" class="text-2xl animate-bounce"></span>
        <div class="flex-1 min-w-0">
            <div id="tracker-banner-title" class="text-xs font-bold text-[#2C1810]">Contacting Chef...</div>
            <div id="tracker-banner-desc" class="text-[0.68rem] text-[#7A6248] truncate">Your order has been submitted and is awaiting confirmation from the chef.</div>
        </div>
    </div>
</div>
@endif

{{-- SECTION 1: Hot Recommendation & Best Seller --}}
<div id="hot-recommendations-container" class="mb-10 animate-fadeInUp [animation-delay:0.06s]">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-[#2C1810] flex items-center gap-2">🔥 Hot Recommendation &amp; Best Seller</h2>
            <p class="text-xs text-[#7A6248]">Highly rated dishes (4-5 ⭐) that are best sellers.</p>
        </div>
    </div>
    @if($hotRecommendations->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($hotRecommendations as $recipe)
                <a href="{{ route('services.order', [$recipe->cooker, $recipe]) }}" class="bg-white border border-[#E8DDD2] rounded-2xl overflow-hidden transition-all hover:border-[#C67C4E]/40 hover:-translate-y-1 hover:shadow-lg flex flex-col relative group no-underline text-[#2C1810]">
                    <span class="absolute top-3 left-3 z-10 text-[0.68rem] font-bold px-2.5 py-1 rounded-full backdrop-blur-md shadow-sm border {{ $recipe->is_halal ? 'bg-green-500/90 text-white border-green-400/20' : 'bg-red-500/90 text-white border-red-400/20' }}">
                        {{ $recipe->is_halal ? 'HALAL 🟢' : 'NON-HALAL 🔴' }}
                    </span>
                    <div class="h-44 bg-cs-bg-primary overflow-hidden relative">
                        @if($recipe->image_path)
                            <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-5xl bg-cs-orange/5 text-cs-orange"></div>
                        @endif
                        <x-price-display :item="$recipe" :viewerCurrency="$viewerCurrency" size="md" color="text-[#D4A574]" class="absolute bottom-3 right-3 bg-[#2C1810]/85 backdrop-blur-md px-3 py-1 rounded-full border border-[#E8DDD2]" />
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-2 justify-between">
                                <span class="text-[0.7rem] text-cs-orange font-bold uppercase tracking-wider">Chef {{ $recipe->cooker->name }}</span>
                                <span class="text-xs bg-[#F5EFE6] border border-slate-100 text-slate-500 font-semibold px-2 py-0.5 rounded-full capitalize"> {{ $recipe->category }}</span>
                            </div>
                            <h3 class="text-base font-bold text-[#2C1810] mb-1 line-clamp-1">{{ $recipe->title }}</h3>
                            <p class="text-xs text-[#7A6248] line-clamp-2 mb-3 leading-relaxed">{{ $recipe->description }}</p>
                        </div>
                        <div class="pt-3 border-t border-[#E8DDD2] flex justify-between items-center">
                            <div class="flex flex-col gap-0.5">
                                <div class="flex items-center gap-1">
                                    <span class="text-xs text-amber-500">⭐</span>
                                    <span class="text-xs font-bold text-[#2C1810]">{{ number_format($recipe->averageRating(), 1) }}</span>
                                    <span class="text-[0.65rem] text-[#9A7B5A]">({{ $recipe->reviews()->count() }})</span>
                                </div>
                                <span class="text-[0.68rem] text-[#7A6248] font-medium">🛒 {{ $recipe->orders_count }} sold</span>
                            </div>
                            <span class="px-3.5 py-1.5 bg-[#C67C4E]/10 text-[#C67C4E] hover:bg-[#C67C4E] hover:text-white text-xs font-bold rounded-lg border border-transparent transition-colors">Order Now →</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-10 bg-white border border-[#E8DDD2] rounded-2xl text-[#7A6248]">
            <span class="text-3xl mb-1 block"></span>
            <p class="text-sm font-semibold text-[#2C1810]">No 4-5 star recommended menus available at the moment.</p>
        </div>
    @endif
</div>

{{-- SECTION 2: Semua Makanan dengan Filter --}}
<div class="mb-10 animate-fadeInUp [animation-delay:0.12s]" id="all-foods-section">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-[#2C1810] flex items-center gap-2">Explore All Foods</h2>
            <p class="text-xs text-[#7A6248]">Use filters on the left sidebar to instantly narrow your search.</p>
        </div>
        {{-- Active filter pills --}}
        <div id="active-filter-pills" class="hidden flex items-center gap-2 flex-wrap">
            <span id="active-cat-pill" class="hidden text-[0.65rem] px-2.5 py-1 bg-[#C67C4E]/10 text-[#C67C4E] border border-[#C67C4E]/25 rounded-full font-semibold"></span>
            <span id="active-halal-pill" class="hidden text-[0.65rem] px-2.5 py-1 bg-blue-50 text-blue-600 border border-blue-200/50 rounded-full font-semibold"></span>
            <span id="active-price-pill" class="hidden text-[0.65rem] px-2.5 py-1 bg-green-50 text-green-600 border border-green-200/50 rounded-full font-semibold"></span>
            <span id="active-rating-pill" class="hidden text-[0.65rem] px-2.5 py-1 bg-amber-50 text-amber-600 border border-amber-200/50 rounded-full font-semibold"></span>
        </div>
    </div>

    {{-- Mobile horizontal category scroll (lg screens use sidebar) --}}
    <div class="lg:hidden flex flex-col gap-3 mb-5">
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            <button class="category-btn px-4 py-2 bg-[#C67C4E] text-white border-none rounded-xl text-xs font-bold cursor-pointer transition-all shadow-sm shrink-0 active" onclick="filterCategory('all', this)">🌟 All</button>
            <div class="h-6 w-px bg-cs-border mx-1 shrink-0"></div>
            <span class="text-[0.65rem] uppercase font-bold text-[#9A7B5A] tracking-wider shrink-0">Asian:</span>
            @foreach(['indonesia', 'malaysian', 'chinese', 'japanese', 'korean', 'thailand', 'indian'] as $c)
                <button class="category-btn px-3.5 py-2 bg-white text-[#7A6248] border border-[#E8DDD2] rounded-xl text-xs font-semibold cursor-pointer transition-all hover:bg-[#F5EFE6] shrink-0" onclick="filterCategory('{{ $c }}', this)">{{ ucfirst($c) }}</button>
            @endforeach
            <div class="h-6 w-px bg-cs-border mx-1 shrink-0"></div>
            <span class="text-[0.65rem] uppercase font-bold text-[#9A7B5A] tracking-wider shrink-0">Western:</span>
            @foreach(['italian', 'american', 'french', 'british'] as $c)
                <button class="category-btn px-3.5 py-2 bg-white text-[#7A6248] border border-[#E8DDD2] rounded-xl text-xs font-semibold cursor-pointer transition-all hover:bg-[#F5EFE6] shrink-0" onclick="filterCategory('{{ $c }}', this)">{{ ucfirst($c) }}</button>
            @endforeach
            <div class="h-6 w-px bg-cs-border mx-1 shrink-0"></div>
            <button class="category-btn px-3.5 py-2 bg-white text-[#7A6248] border border-[#E8DDD2] rounded-xl text-xs font-semibold cursor-pointer transition-all hover:bg-[#F5EFE6] shrink-0" onclick="filterCategory('dessert', this)">🧁 Dessert</button>
        </div>
    </div>

    {{-- Tab Switcher --}}
    <div class="flex border-b border-[#E8DDD2] mb-5">
        <button class="marketplace-tab-btn border-b-2 border-[#C67C4E] text-[#C67C4E] text-sm font-bold px-5 py-3 cursor-pointer transition-all bg-transparent" onclick="switchMarketplaceTab('services-tab', this, null)">
             Cooking Services &amp; Menu ({{ $services->count() }})
        </button>
        <button class="marketplace-tab-btn border-b-2 border-transparent text-[#7A6248] text-sm font-semibold px-5 py-3 cursor-pointer transition-all bg-transparent hover:text-[#2C1810]" onclick="switchMarketplaceTab('recipes-tab', this, null)">
            Secret Recipes ({{ $recipes->count() }})
        </button>
    </div>

    {{-- Jasa Cooking Grid --}}
    <div id="services-grid-container">
        @if($services->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4" id="services-cards-grid">
                @foreach($services as $item)
                    <a href="{{ route('services.order', [$item->cooker, $item]) }}" class="marketplace-card bg-white border border-[#E8DDD2] rounded-xl sm:rounded-2xl overflow-hidden transition-all hover:border-[#C67C4E]/30 hover:shadow-md flex flex-col no-underline text-[#2C1810]"
                        data-tab="services-tab"
                        data-category="{{ $item->category }}"
                        data-halal="{{ $item->is_halal ? 'true' : 'false' }}"
                        data-price="{{ $item->price ?? 0 }}"
                        data-rating="{{ $item->averageRating() }}">
                        <div class="h-20 sm:h-28 bg-cs-bg-primary overflow-hidden relative">
                            <span class="absolute top-1.5 left-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-md shadow-sm border {{ $item->is_halal ? 'bg-green-500/90 text-white border-green-400/20' : 'bg-red-500/90 text-white border-red-400/20' }}">
                                {{ $item->is_halal ? 'Halal 🟢' : 'Non-Halal 🔴' }}
                            </span>
                            @if($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-2xl sm:text-3xl bg-cs-orange/5 text-cs-orange"></div>
                            @endif
                            <x-price-display :item="$item" :viewerCurrency="$viewerCurrency" size="sm" color="text-[#D4A574]" class="absolute bottom-1.5 right-1.5 bg-[#2C1810]/85 backdrop-blur-md px-1.5 py-0.5 rounded-full" />
                        </div>
                        <div class="p-2 sm:p-3 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-1 mb-1 justify-between">
                                    <span class="text-[0.55rem] sm:text-[0.62rem] text-cs-orange font-bold truncate max-w-[60px] sm:max-w-none">Chef {{ $item->cooker->name }}</span>
                                    <span class="text-[0.52rem] sm:text-[0.58rem] text-[#7A6248] bg-[#F5EFE6] border border-slate-100 px-1 py-0.5 rounded capitalize truncate">{{ $item->category }}</span>
                                </div>
                                <h3 class="text-[0.7rem] sm:text-xs font-bold text-[#2C1810] mb-0.5 line-clamp-1">{{ $item->title }}</h3>
                                <p class="hidden sm:block text-[0.68rem] text-[#7A6248] line-clamp-2 mb-2 leading-tight">{{ $item->description }}</p>
                            </div>
                            <div class="pt-1.5 sm:pt-2 border-t border-[#E8DDD2] flex justify-between items-center">
                                <div class="flex items-center gap-0.5">
                                    <span class="text-[0.55rem] sm:text-[0.62rem] text-amber-500">⭐</span>
                                    <span class="text-[0.55rem] sm:text-[0.62rem] font-bold text-[#2C1810]">{{ number_format($item->averageRating(), 1) }}</span>
                                </div>
                                <span class="text-[0.62rem] sm:text-[0.68rem] text-[#C67C4E] font-bold hover:underline">Order →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="text-center mt-6">
                <a href="{{ route('foods.index') }}" class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-white border border-[#C67C4E] text-[#C67C4E] hover:bg-[#C67C4E] hover:text-white text-xs font-bold rounded-xl no-underline transition-all shadow-sm hover:-translate-y-px">View All Menus →</a>
            </div>
        @else
            <div class="text-center py-10 bg-white border border-[#E8DDD2] rounded-2xl text-[#7A6248]">
                <span class="text-3xl mb-1 block"></span>
                <p class="text-sm font-semibold text-[#2C1810]">No food menus found.</p>
            </div>
        @endif
    </div>

    {{-- Resep Rahasia Grid --}}
    <div id="recipes-grid-container" class="hidden">
        @if($recipes->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4" id="recipes-cards-grid">
                @foreach($recipes as $item)
                    <a href="{{ route('cookers.recipe', [$item->cooker, $item]) }}" class="marketplace-card bg-white border border-[#E8DDD2] rounded-xl sm:rounded-2xl overflow-hidden transition-all hover:border-[#C67C4E]/30 hover:shadow-md flex flex-col no-underline text-[#2C1810]"
                        data-tab="recipes-tab"
                        data-category="{{ $item->category }}"
                        data-halal="{{ $item->is_halal ? 'true' : 'false' }}"
                        data-price="{{ $item->price ?? 0 }}"
                        data-rating="{{ $item->averageRating() }}">
                        <div class="h-20 sm:h-28 bg-cs-bg-primary overflow-hidden relative">
                            <span class="absolute top-1.5 left-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-md shadow-sm border {{ $item->is_halal ? 'bg-green-500/90 text-white border-green-400/20' : 'bg-red-500/90 text-white border-red-400/20' }}">
                                {{ $item->is_halal ? 'Halal 🟢' : 'Non-Halal 🔴' }}
                            </span>
                            @if($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-2xl sm:text-3xl bg-cs-orange/5 text-cs-orange"></div>
                            @endif
                            <x-price-display :item="$item" :viewerCurrency="$viewerCurrency" size="sm" color="text-[#D4A574]" class="absolute bottom-1.5 right-1.5 bg-[#2C1810]/85 backdrop-blur-md px-1.5 py-0.5 rounded-full" />
                        </div>
                        <div class="p-2 sm:p-3 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-1 mb-1 justify-between">
                                    <span class="text-[0.55rem] sm:text-[0.62rem] text-cs-orange font-bold truncate max-w-[60px] sm:max-w-none">Chef {{ $item->cooker->name }}</span>
                                    <span class="text-[0.52rem] sm:text-[0.58rem] text-[#7A6248] bg-[#F5EFE6] border border-slate-100 px-1 py-0.5 rounded capitalize truncate">{{ $item->category }}</span>
                                </div>
                                <h3 class="text-[0.7rem] sm:text-xs font-bold text-[#2C1810] mb-0.5 line-clamp-1">{{ $item->title }}</h3>
                                <p class="hidden sm:block text-[0.68rem] text-[#7A6248] line-clamp-2 mb-2 leading-tight">{{ $item->description }}</p>
                            </div>
                            <div class="pt-1.5 sm:pt-2 border-t border-[#E8DDD2] flex justify-between items-center">
                                <div class="flex items-center gap-0.5">
                                    <span class="text-[0.55rem] sm:text-[0.62rem] text-amber-500">⭐</span>
                                    <span class="text-[0.55rem] sm:text-[0.62rem] font-bold text-[#2C1810]">{{ number_format($item->averageRating(), 1) }}</span>
                                </div>
                                <span class="text-[0.62rem] sm:text-[0.68rem] text-[#C67C4E] font-bold hover:underline">Unlock →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="text-center mt-6">
                <a href="{{ route('foods.index') }}" class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-white border border-[#C67C4E] text-[#C67C4E] hover:bg-[#C67C4E] hover:text-white text-xs font-bold rounded-xl no-underline transition-all shadow-sm hover:-translate-y-px">View All Recipes →</a>
            </div>
        @else
            <div class="text-center py-10 bg-white border border-[#E8DDD2] rounded-2xl text-[#7A6248]">
                <span class="text-3xl mb-1 block"></span>
                <p class="text-sm font-semibold text-[#2C1810]">No secret recipes found.</p>
            </div>
        @endif
    </div>

    {{-- Empty state after filtering --}}
    <div id="no-recipes-found" class="text-center py-10 bg-white border border-[#E8DDD2] rounded-2xl text-[#7A6248] hidden">
        <span class="text-3xl mb-1 block">🔍</span>
        <p class="text-sm font-semibold text-[#2C1810]">No items matched this filter.</p>
        <p class="text-xs mt-1">Try changing your filters or <button onclick="resetAllFilters()" class="text-[#C67C4E] font-semibold border-none bg-transparent cursor-pointer underline">reset all filters</button>.</p>
    </div>
</div>

{{-- SECTION 1.5: Resep Rahasia --}}
<div id="recipes-section-container" class="mb-10 animate-fadeInUp [animation-delay:0.09s]">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-[#2C1810] flex items-center gap-2">Selected Cooker Secret Recipes</h2>
            <p class="text-xs text-[#7A6248]">Learn secret steps to cook delicious dishes directly from trusted chefs.</p>
        </div>
        <a href="{{ route('foods.index') }}" class="px-4 py-2 bg-white border border-[#E8DDD2] text-[#2C1810] hover:border-[#C67C4E] text-xs font-semibold rounded-xl no-underline transition-colors shadow-sm shrink-0">View All →</a>
    </div>
    @if($recipes->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
            @foreach($recipes as $item)
                <a href="{{ route('cookers.recipe', [$item->cooker, $item]) }}" class="bg-white border border-[#E8DDD2] rounded-xl sm:rounded-2xl overflow-hidden transition-all hover:border-[#C67C4E]/30 hover:shadow-md flex flex-col no-underline text-[#2C1810]">
                    <div class="h-20 sm:h-28 bg-cs-bg-primary overflow-hidden relative">
                        <span class="absolute top-1.5 left-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-md shadow-sm border {{ $item->is_halal ? 'bg-green-500/90 text-white border-green-400/20' : 'bg-red-500/90 text-white border-red-400/20' }}">
                            {{ $item->is_halal ? 'Halal 🟢' : 'Non-Halal 🔴' }}
                        </span>
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-2xl sm:text-3xl bg-cs-orange/5 text-cs-orange"></div>
                        @endif
                        <x-price-display :item="$item" :viewerCurrency="$viewerCurrency" size="sm" color="text-[#D4A574]" class="absolute bottom-1.5 right-1.5 bg-[#2C1810]/85 backdrop-blur-md px-1.5 py-0.5 rounded-full" />
                    </div>
                    <div class="p-2 sm:p-3 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-1 mb-1 justify-between">
                                <span class="text-[0.55rem] sm:text-[0.62rem] text-cs-orange font-bold truncate max-w-[60px] sm:max-w-none">Chef {{ $item->cooker->name }}</span>
                                <span class="text-[0.52rem] sm:text-[0.58rem] text-[#7A6248] bg-[#F5EFE6] border border-slate-100 px-1 py-0.5 rounded capitalize truncate">{{ $item->category }}</span>
                            </div>
                            <h3 class="text-[0.7rem] sm:text-xs font-bold text-[#2C1810] mb-0.5 line-clamp-1">{{ $item->title }}</h3>
                        </div>
                        <div class="pt-1.5 sm:pt-2 border-t border-[#E8DDD2] flex justify-between items-center mt-1">
                            <div class="flex items-center gap-0.5">
                                <span class="text-[0.55rem] sm:text-[0.62rem] text-amber-500">⭐</span>
                                <span class="text-[0.55rem] sm:text-[0.62rem] font-bold text-[#2C1810]">{{ number_format($item->averageRating(), 1) }}</span>
                            </div>
                            <span class="text-[0.62rem] sm:text-[0.68rem] text-[#C67C4E] font-bold hover:underline">Unlock →</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-10 bg-white border border-[#E8DDD2] rounded-2xl text-[#7A6248]">
            <span class="text-3xl mb-1 block"></span>
            <p class="text-sm font-semibold text-[#2C1810]">No secret recipes found.</p>
        </div>
    @endif
</div>

{{-- SECTION 3: Top Cookers --}}
<div id="cookers-grid-container" class="mb-10 animate-fadeInUp [animation-delay:0.18s]">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-[#2C1810] flex items-center gap-2">Top Cookers</h2>
            <p class="text-xs text-[#7A6248]">Experienced chefs with the best recipe sales and reviews.</p>
        </div>
        <a href="{{ route('cookers.index') }}" class="px-4 py-2 bg-white border border-[#E8DDD2] text-[#2C1810] hover:border-[#C67C4E] text-xs font-semibold rounded-xl no-underline transition-colors shadow-sm shrink-0">View All →</a>
    </div>
    @if($cookers->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
            @foreach($cookers as $cooker)
                <div class="bg-white border border-[#E8DDD2] rounded-xl sm:rounded-2xl p-3 sm:p-5 text-center transition-all hover:border-[#C67C4E]/30 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between relative">
                    <div>
                        <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center font-bold text-white text-xs sm:text-base mx-auto mb-2 sm:mb-3 border border-[#E8DDD2] overflow-hidden">
                            @if($cooker->profile_photo_path)
                                <img src="{{ $cooker->getProfilePhotoUrl() }}" alt="{{ $cooker->name }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($cooker->name, 0, 1)) }}
                            @endif
                        </div>
                        <h3 class="text-xs sm:text-sm font-bold text-[#2C1810] mb-0.5 sm:mb-1 truncate" title="{{ $cooker->name }}">{{ $cooker->name }}</h3>
                        <div class="flex items-center justify-center gap-0.5 sm:gap-1 mb-2 sm:mb-3 flex-wrap text-[#9A7B5A]">
                            <span class="text-[0.62rem] sm:text-xs text-amber-500">⭐</span>
                            <span class="text-[0.62rem] sm:text-xs font-bold text-[#2C1810]">{{ number_format($cooker->calculated_rating, 1) }}</span>
                            <span class="text-[0.55rem] sm:text-[0.65rem]">({{ $cooker->totalSalesCount() }} sold)</span>
                            <span class="text-[0.55rem] sm:text-[0.65rem]">•</span>
                            <span class="text-[0.55rem] sm:text-[0.65rem] font-medium">{{ $cooker->followers_count ?? $cooker->followers()->count() }} followers</span>
                        </div>
                    </div>
                    <div class="mt-2 flex flex-col gap-1.5 w-full">
                        <a href="{{ route('cookers.show', $cooker) }}" class="block w-full text-center py-1 sm:py-1.5 border border-[#C67C4E]/30 hover:bg-[#C67C4E] text-[#C67C4E] hover:text-white text-[0.62rem] sm:text-xs font-bold rounded-lg no-underline transition-colors">View Profile</a>
                        @if(Auth::id() !== $cooker->id)
                            <form action="{{ route('cookers.toggle-follow', $cooker) }}" method="POST" class="m-0 w-full flex">
                                @csrf
                                <button type="submit" class="w-full text-center py-1 sm:py-1.5 border border-solid transition-colors text-[0.62rem] sm:text-xs font-bold rounded-lg cursor-pointer
                                    {{ Auth::user()->isFollowing($cooker)
                                        ? 'bg-[#7A6B5D] text-white border-[#7A6B5D] hover:bg-[#5C4D40] hover:border-[#5C4D40]'
                                        : 'bg-white text-[#C67C4E] border-[#C67C4E] hover:bg-[#C67C4E] hover:text-white' }}">
                                    {{ Auth::user()->isFollowing($cooker) ? '👤 Unfollow' : '👤 Follow' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-10 bg-white border border-[#E8DDD2] rounded-2xl text-[#7A6248]">
            <span class="text-3xl mb-1 block"></span>
            <p class="text-sm font-semibold text-[#2C1810]">No cookers registered yet.</p>
        </div>
    @endif
</div>

{{-- SECTION 4: Transaksi & Aktivitas --}}
<div class="animate-fadeInUp [animation-delay:0.24s]" id="transaction-section">
    <div class="flex items-center justify-between border-b border-[#E8DDD2] mb-6 pb-2">
        <div class="flex gap-2 flex-wrap">
            <button class="tab-btn bg-transparent border-none text-[#7A6248] text-sm font-semibold px-4 py-2 cursor-pointer rounded-lg transition-all hover:text-[#2C1810] hover:bg-cs-bg-primary active" onclick="switchTab(event, 'purchased-recipes')">Purchased Recipes ({{ count($purchasedRecipes) }})</button>
            <button class="tab-btn bg-transparent border-none text-[#7A6248] text-sm font-semibold px-4 py-2 cursor-pointer rounded-lg transition-all hover:text-[#2C1810] hover:bg-cs-bg-primary" onclick="switchTab(event, 'cooking-orders')">Service Orders ({{ count($serviceOrders) }})</button>
        </div>
    </div>

    {{-- Tab 1: Resep Terbeli --}}
    <div id="purchased-recipes" class="tab-content active">
        @if(count($purchasedRecipes) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($purchasedRecipes as $recipe)
                    <a href="{{ route('cookers.recipe', [$recipe->cooker, $recipe]) }}" class="bg-white border border-[#E8DDD2] rounded-2xl overflow-hidden transition-all hover:border-cs-orange/30 hover:-translate-y-1 hover:shadow-md no-underline block">
                        @if($recipe->image_path)
                            <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->title }}" class="w-full h-32 object-cover bg-cs-bg-primary">
                        @else
                            <div class="w-full h-32 flex items-center justify-center text-3xl bg-cs-orange/4 text-cs-orange"></div>
                        @endif
                        <div class="p-4">
                            <div class="text-[0.7rem] text-cs-orange mb-1 font-semibold">Chef {{ $recipe->cooker->name }}</div>
                            <div class="text-xs font-bold text-[#2C1810] mb-1 truncate">{{ $recipe->title }}</div>
                            <div class="text-[0.7rem] text-[#7A6248] mb-3 line-clamp-2">{{ $recipe->description }}</div>
                            <div class="text-[0.68rem] text-cs-green font-bold flex items-center gap-1">🔓 Steps Unlocked</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white border border-[#E8DDD2] rounded-2xl text-[#7A6248] shadow-sm">
                <div class="text-4xl mb-3"></div>
                <div class="font-bold text-sm text-[#2C1810]">No purchased recipes yet.</div>
                <p class="mt-1 text-xs max-w-sm mx-auto">Buy recipes from our popular cookers to see complete cooking steps!</p>
            </div>
        @endif
    </div>

    {{-- Tab 2: Pesanan Jasa --}}
    <div id="cooking-orders" class="tab-content">
        @if(count($serviceOrders) > 0)
            <div class="overflow-x-auto bg-white border border-[#E8DDD2] rounded-2xl shadow-sm">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="border-b border-[#E8DDD2] bg-[#F5EFE6]">
                            <th class="p-4 text-[#7A6248] font-bold text-[0.7rem] uppercase tracking-wider">Order ID</th>
                            <th class="p-4 text-[#7A6248] font-bold text-[0.7rem] uppercase tracking-wider">Cooker / Chef</th>
                            <th class="p-4 text-[#7A6248] font-bold text-[0.7rem] uppercase tracking-wider">Cooking Service</th>
                            <th class="p-4 text-[#7A6248] font-bold text-[0.7rem] uppercase tracking-wider">Total Price</th>
                            <th class="p-4 text-[#7A6248] font-bold text-[0.7rem] uppercase tracking-wider">Status</th>
                            <th class="p-4 text-[#7A6248] font-bold text-[0.7rem] uppercase tracking-wider">Rating &amp; Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($serviceOrders as $order)
                            <tr class="border-b border-[#E8DDD2] last:border-b-0 hover:bg-[#F5EFE6]">
                                <td class="p-4 text-[#2C1810] font-mono text-xs">#{{ $order->id }}</td>
                                <td class="p-4 text-[#2C1810]">
                                    <strong class="text-xs">{{ $order->cooker->name }}</strong>
                                    <div class="text-[0.68rem] text-[#7A6248]">{{ $order->cooker->email }}</div>
                                </td>
                                <td class="p-4 text-[#2C1810]">
                                    <div class="text-xs font-bold">{{ $order->service->title }}</div>
                                    @if($order->notes)
                                        <div class="text-[0.68rem] text-[#7A6248] mt-0.5"> "{{ $order->notes }}"</div>
                                    @endif
                                </td>
                                <td class="p-4 font-bold text-xs text-cs-orange">{{ $order->formatted_price }}</td>
                                <td class="p-4">
                                    <span class="text-[0.68rem] px-2 py-0.5 rounded font-semibold inline-flex items-center gap-1
                                        {{ $order->status === 'pending' ? 'bg-amber-500/12 text-amber-600 border border-amber-500/20' : '' }}
                                        {{ $order->status === 'confirmed' ? 'bg-blue-500/12 text-blue-600 border border-blue-500/20' : '' }}
                                        {{ $order->status === 'completed' ? 'bg-cs-green/12 text-cs-green border border-cs-green/20' : '' }}
                                        {{ $order->status === 'cancelled' ? 'bg-red-500/12 text-red-600 border border-red-500/20' : '' }}">
                                        {{ $order->status_badge }} {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @if($order->status === 'completed')
                                        @if($order->rating)
                                            <div class="flex flex-col gap-0.5">
                                                <div class="text-xs text-amber-500 font-bold">⭐ {{ $order->rating }}/5</div>
                                                <div class="text-[0.65rem] text-[#9A7B5A] italic truncate max-w-[120px]">"{{ $order->review }}"</div>
                                            </div>
                                        @else
                                            <a href="{{ route('cookers.service', [$order->cooker, $order->service]) }}" class="inline-flex px-2.5 py-1 bg-amber-500 text-white rounded-lg text-[0.68rem] font-bold no-underline hover:bg-amber-600 transition-colors">⭐ Give Rating</a>
                                        @endif
                                    @else
                                        <span class="text-[0.68rem] text-[#9A7B5A] italic">Awaiting Completion</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12 bg-white border border-[#E8DDD2] rounded-2xl text-[#7A6248] shadow-sm">
                <div class="text-4xl mb-3"></div>
                <div class="font-bold text-sm text-[#2C1810]">No cooking services ordered yet.</div>
                <p class="mt-1 text-xs max-w-sm mx-auto">Order the best chef services from your favorite cooker profiles!</p>
            </div>
        @endif
    </div>
</div>

</div>
{{-- ═══════ END MAIN CONTENT ═══════ --}}

</div>
{{-- ═══════ END 2-COLUMN LAYOUT ═══════ --}}

@endsection

@section('scripts')
<script>
    // ── Filter State ──────────────────────────────────────────────
    let activeCategory    = 'all';
    let activeHalalFilter = 'all';
    let activeMarketplaceTab = 'services-tab';
    let activeMinRating   = 0;

    // ── Search bar ────────────────────────────────────────────────
    document.addEventListener("DOMContentLoaded", function () {
        // Check URL parameter to switch to cooking-orders tab
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'orders') {
            const ordersTabBtn = document.querySelector('[onclick*="switchTab"][onclick*="cooking-orders"]');
            if (ordersTabBtn) {
                // Simulate click on orders tab button
                ordersTabBtn.click();
            }
        }

        const searchInput = document.getElementById('global-search');
        const clearBtn    = document.getElementById('clear-search-btn');
        const searchForm  = document.getElementById('search-form');
        let debounceTimer;

        function toggleClearButton() {
            if (searchInput && clearBtn) {
                clearBtn.classList.toggle('hidden', searchInput.value.length === 0);
            }
        }

        function performLiveSearch(query) {
            const url = new URL(window.location.href);
            query ? url.searchParams.set('search', query) : url.searchParams.delete('search');
            window.history.replaceState({}, '', url.toString());

            fetch(url.toString())
                .then(r => r.text())
                .then(html => {
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const swap = (id) => {
                        const n = doc.getElementById(id), c = document.getElementById(id);
                        if (n && c) c.outerHTML = n.outerHTML;
                    };
                    swap('search-results-container');
                    swap('hot-recommendations-container');
                    swap('services-grid-container');
                    swap('recipes-grid-container');
                    swap('recipes-section-container');
                    swap('cookers-grid-container');
                    applyFilters();
                })
                .catch(err => console.error('Live search error:', err));
        }

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                toggleClearButton();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => performLiveSearch(searchInput.value.trim()), 300);
            });
            toggleClearButton();
        }
        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                if (searchInput) searchInput.value = '';
                toggleClearButton();
                performLiveSearch('');
            });
        }
        if (searchForm) {
            searchForm.addEventListener('submit', function (e) {
                e.preventDefault();
                if (searchInput) performLiveSearch(searchInput.value.trim());
            });
        }
    });

    // ── Mobile filter drawer toggle ───────────────────────────────
    function toggleMobileFilter() {
        const drawer   = document.getElementById('mobile-filter-drawer');
        const chevron  = document.getElementById('mobile-filter-chevron');
        const isHidden = drawer.classList.contains('hidden');
        drawer.classList.toggle('hidden', !isHidden);
        chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
    }

    // ── Tab switching (marketplace) ───────────────────────────────
    function switchMarketplaceTab(tabId, topBtn, sidebarBtn) {
        activeMarketplaceTab = tabId;

        // Show/hide grids
        const showServices = tabId === 'services-tab';
        document.getElementById('services-grid-container').classList.toggle('hidden', !showServices);
        document.getElementById('recipes-grid-container').classList.toggle('hidden', showServices);

        // Sync TOP tab buttons
        document.querySelectorAll('.marketplace-tab-btn').forEach(btn => {
            btn.classList.remove('border-[#C67C4E]', 'text-[#C67C4E]', 'font-bold');
            btn.classList.add('border-transparent', 'text-[#7A6248]', 'font-semibold');
        });
        if (topBtn) {
            topBtn.classList.remove('border-transparent', 'text-[#7A6248]', 'font-semibold');
            topBtn.classList.add('border-[#C67C4E]', 'text-[#C67C4E]', 'font-bold');
        } else {
            // Sync visually without a ref btn
            const tabBtns = document.querySelectorAll('.marketplace-tab-btn');
            if (tabBtns.length >= 2) {
                const activeIdx = showServices ? 0 : 1;
                tabBtns[activeIdx].classList.remove('border-transparent', 'text-[#7A6248]', 'font-semibold');
                tabBtns[activeIdx].classList.add('border-[#C67C4E]', 'text-[#C67C4E]', 'font-bold');
            }
        }

        // Sync SIDEBAR tab buttons
        const sidebarServices = document.getElementById('sidebar-tab-services');
        const sidebarRecipes  = document.getElementById('sidebar-tab-recipes');
        const mobileServices  = document.getElementById('mobile-tab-services');
        const mobileRecipes   = document.getElementById('mobile-tab-recipes');
        if (showServices) {
            [sidebarServices, mobileServices].forEach(b => { if(b) { b.classList.add('bg-[#C67C4E]','text-white'); b.classList.remove('bg-transparent','text-[#7A6248]','hover:bg-[#F5EFE6]','border','border-[#E8DDD2]'); } });
            [sidebarRecipes, mobileRecipes].forEach(b => { if(b) { b.classList.remove('bg-[#C67C4E]','text-white'); b.classList.add('bg-transparent','text-[#7A6248]','hover:bg-[#F5EFE6]'); } });
        } else {
            [sidebarRecipes, mobileRecipes].forEach(b => { if(b) { b.classList.add('bg-[#C67C4E]','text-white'); b.classList.remove('bg-transparent','text-[#7A6248]','hover:bg-[#F5EFE6]','border','border-[#E8DDD2]'); } });
            [sidebarServices, mobileServices].forEach(b => { if(b) { b.classList.remove('bg-[#C67C4E]','text-white'); b.classList.add('bg-transparent','text-[#7A6248]','hover:bg-[#F5EFE6]'); } });
        }

        applyFilters();
    }

    // ── Category filter ───────────────────────────────────────────
    function filterCategory(category, clickedBtn) {
        activeCategory = category;

        // Update ALL category buttons (sidebar + mobile horizontal)
        document.querySelectorAll('.category-btn, .sidebar-cat-btn').forEach(btn => {
            btn.classList.remove('bg-[#C67C4E]', 'text-white', 'font-bold');
            btn.classList.add('bg-transparent', 'text-[#7A6248]', 'font-semibold');
        });
        if (clickedBtn) {
            clickedBtn.classList.remove('bg-transparent', 'text-[#7A6248]', 'font-semibold');
            clickedBtn.classList.add('bg-[#C67C4E]', 'text-white', 'font-bold');
        }
        // Sync sibling buttons with same category value
        document.querySelectorAll(`.category-btn, .sidebar-cat-btn`).forEach(btn => {
            if (btn.getAttribute('onclick') && btn.getAttribute('onclick').includes(`'${category}'`)) {
                btn.classList.remove('bg-transparent','text-[#7A6248]','font-semibold');
                btn.classList.add('bg-[#C67C4E]','text-white','font-bold');
            }
        });

        applyFilters();
    }

    // ── Halal filter ──────────────────────────────────────────────
    function filterHalal(filterType, el) {
        activeHalalFilter = filterType;

        // Sync mobile halal buttons
        document.querySelectorAll('.mobile-halal-btn').forEach(btn => {
            const val = btn.getAttribute('onclick').includes("'all'") ? 'all' :
                        btn.getAttribute('onclick').includes("'halal'") ? 'halal' : 'nonhalal';
            const isActive = val === filterType;
            btn.classList.toggle('bg-white', isActive);
            btn.classList.toggle('text-white', isActive);
            btn.classList.toggle('bg-transparent', !isActive);
            btn.classList.toggle('text-[#7A6248]', !isActive);
        });
        // Sync radio buttons
        document.querySelectorAll('input[name="halal-sidebar"]').forEach(r => {
            r.checked = (r.value === filterType);
        });

        applyFilters();
    }

    // ── Price range helpers ───────────────────────────────────────
    function setPriceRange(min, max) {
        const pMin = document.getElementById('price-min');
        const pMax = document.getElementById('price-max');
        if (pMin) pMin.value = min;
        if (pMax) pMax.value = max === 99999999 ? '' : max;

        // Highlight active preset
        document.querySelectorAll('.price-preset-btn').forEach(btn => {
            btn.classList.remove('bg-[#C67C4E]/10','text-[#C67C4E]','border-[#C67C4E]/40');
            btn.classList.add('bg-[#F5EFE6]','text-[#7A6248]','border-[#E8DDD2]');
        });

        applyFilters();
    }

    function syncPriceFromMobile() {
        const mMin = document.getElementById('price-min-mobile');
        const mMax = document.getElementById('price-max-mobile');
        const dMin = document.getElementById('price-min');
        const dMax = document.getElementById('price-max');
        if (dMin && mMin) dMin.value = mMin.value;
        if (dMax && mMax) dMax.value = mMax.value;
        applyFilters();
    }

    // ── Sort helper ───────────────────────────────────────────────
    function syncSortFromMobile() {
        const mSort = document.getElementById('sort-select-mobile');
        const dSort = document.getElementById('sort-select');
        if (dSort && mSort) dSort.value = mSort.value;
        applyFilters();
    }

    // ── Rating filter ─────────────────────────────────────────────
    function filterByRating(minRating, btn) {
        activeMinRating = minRating;
        document.querySelectorAll('.rating-filter-btn').forEach(b => {
            b.classList.remove('bg-white', 'text-white', 'font-semibold');
            b.classList.add('bg-transparent', 'text-[#7A6248]', 'font-medium');
        });
        if (btn) {
            btn.classList.remove('bg-transparent', 'text-[#7A6248]', 'font-medium');
            btn.classList.add('bg-white', 'text-white', 'font-semibold');
        }
        applyFilters();
    }

    // ── APPLY ALL FILTERS ─────────────────────────────────────────
    function applyFilters() {
        const priceMinEl = document.getElementById('price-min');
        const priceMaxEl = document.getElementById('price-max');
        const sortEl     = document.getElementById('sort-select');

        const priceMin = priceMinEl && priceMinEl.value !== '' ? parseFloat(priceMinEl.value) : 0;
        const priceMax = priceMaxEl && priceMaxEl.value !== '' ? parseFloat(priceMaxEl.value) : Infinity;
        const sortBy   = sortEl ? sortEl.value : 'default';

        const cards = document.querySelectorAll('.marketplace-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const isActiveTab  = card.getAttribute('data-tab') === activeMarketplaceTab;
            const catMatch     = (activeCategory === 'all' || card.getAttribute('data-category') === activeCategory);
            const isHalal      = card.getAttribute('data-halal') === 'true';
            const halalMatch   = activeHalalFilter === 'all' || (activeHalalFilter === 'halal' ? isHalal : !isHalal);
            const cardPrice    = parseFloat(card.getAttribute('data-price') || 0);
            const priceMatch   = cardPrice >= priceMin && cardPrice <= priceMax;
            const cardRating   = parseFloat(card.getAttribute('data-rating') || 0);
            const ratingMatch  = cardRating >= activeMinRating;

            const visible = isActiveTab && catMatch && halalMatch && priceMatch && ratingMatch;
            card.style.display = visible ? 'flex' : 'none';
            if (visible) visibleCount++;
        });

        // Sort visible cards
        if (sortBy !== 'default') {
            const grids = ['services-cards-grid', 'recipes-cards-grid'];
            grids.forEach(gridId => {
                const grid = document.getElementById(gridId);
                if (!grid) return;
                const visibleCards = [...grid.querySelectorAll('.marketplace-card')].filter(c => c.style.display !== 'none');
                visibleCards.sort((a, b) => {
                    const aPrice  = parseFloat(a.getAttribute('data-price') || 0);
                    const bPrice  = parseFloat(b.getAttribute('data-price') || 0);
                    const aRating = parseFloat(a.getAttribute('data-rating') || 0);
                    const bRating = parseFloat(b.getAttribute('data-rating') || 0);
                    if (sortBy === 'price-asc')    return aPrice - bPrice;
                    if (sortBy === 'price-desc')   return bPrice - aPrice;
                    if (sortBy === 'rating-desc')  return bRating - aRating;
                    return 0;
                });
                visibleCards.forEach(c => grid.appendChild(c));
            });
        }

        // Empty state
        const noFound = document.getElementById('no-recipes-found');
        if (noFound) noFound.classList.toggle('hidden', visibleCount > 0);

        // Update sidebar filter status
        updateFilterStatus();
    }

    function updateFilterStatus() {
        const priceMinEl = document.getElementById('price-min');
        const priceMaxEl = document.getElementById('price-max');
        const sortEl     = document.getElementById('sort-select');

        const hasCategory = activeCategory !== 'all';
        const hasHalal    = activeHalalFilter !== 'all';
        const hasPrice    = (priceMinEl && priceMinEl.value !== '') || (priceMaxEl && priceMaxEl.value !== '');
        const hasRating   = activeMinRating > 0;
        const hasSort     = sortEl && sortEl.value !== 'default';

        const activeFilters = [hasCategory, hasHalal, hasPrice, hasRating, hasSort].filter(Boolean).length;
        const isFiltered = activeFilters > 0;

        // Sidebar status box
        const statusBox  = document.getElementById('filter-status-box');
        const statusText = document.getElementById('filter-status-text');
        if (statusBox && statusText) {
            statusBox.classList.toggle('hidden', !isFiltered);
            if (isFiltered) statusText.textContent = `${activeFilters} filter aktif`;
        }

        // Sidebar header indicator
        const sidebarActive = document.getElementById('sidebar-filter-active');
        if (sidebarActive) {
            sidebarActive.classList.toggle('hidden', !isFiltered);
            sidebarActive.textContent = isFiltered ? `${activeFilters} filter aktif` : '';
        }

        // Mobile filter badge
        const mobileBadge = document.getElementById('mobile-filter-badge');
        if (mobileBadge) mobileBadge.classList.toggle('hidden', !isFiltered);

        // Active filter pills
        const pillsContainer = document.getElementById('active-filter-pills');
        if (pillsContainer) pillsContainer.classList.toggle('hidden', !isFiltered);

        const catPill    = document.getElementById('active-cat-pill');
        const halalPill  = document.getElementById('active-halal-pill');
        const pricePill  = document.getElementById('active-price-pill');
        const ratingPill = document.getElementById('active-rating-pill');

        if (catPill)    { catPill.classList.toggle('hidden',    !hasCategory); if (hasCategory) catPill.textContent = `🌍 ${activeCategory}`; }
        if (halalPill)  { halalPill.classList.toggle('hidden',  !hasHalal);   if (hasHalal) halalPill.textContent = activeHalalFilter === 'halal' ? '🟢 Halal' : '🔴 Non-Halal'; }
        if (pricePill)  { pricePill.classList.toggle('hidden',  !hasPrice);   if (hasPrice) { const min = priceMinEl?.value || '0'; const max = priceMaxEl?.value || '∞'; pricePill.textContent = `💰 ${parseInt(min/1000)||0}rb – ${priceMaxEl?.value ? parseInt(priceMaxEl.value/1000)+'rb' : '∞'}`; } }
        if (ratingPill) { ratingPill.classList.toggle('hidden', !hasRating);  if (hasRating) ratingPill.textContent = `⭐ ${activeMinRating}+`; }
    }

    // ── Reset ALL filters ─────────────────────────────────────────
    function resetAllFilters() {
        activeCategory    = 'all';
        activeHalalFilter = 'all';
        activeMinRating   = 0;

        // Reset category buttons
        document.querySelectorAll('.category-btn, .sidebar-cat-btn').forEach(btn => {
            const isAll = btn.getAttribute('onclick') && btn.getAttribute('onclick').includes("'all'");
            btn.classList.toggle('bg-[#C67C4E]', isAll);
            btn.classList.toggle('text-white', isAll);
            btn.classList.toggle('font-bold', isAll);
            btn.classList.toggle('bg-transparent', !isAll);
            btn.classList.toggle('text-[#7A6248]', !isAll);
            btn.classList.toggle('font-semibold', !isAll);
        });

        // Reset halal radio
        document.querySelectorAll('input[name="halal-sidebar"]').forEach(r => r.checked = r.value === 'all');
        document.querySelectorAll('.mobile-halal-btn').forEach((btn, i) => {
            btn.classList.toggle('bg-white', i === 0);
            btn.classList.toggle('text-white', i === 0);
            btn.classList.toggle('bg-transparent', i !== 0);
            btn.classList.toggle('text-[#7A6248]', i !== 0);
        });

        // Reset price
        const pMin = document.getElementById('price-min'); if (pMin) pMin.value = '';
        const pMax = document.getElementById('price-max'); if (pMax) pMax.value = '';
        const pMinM = document.getElementById('price-min-mobile'); if (pMinM) pMinM.value = '';
        const pMaxM = document.getElementById('price-max-mobile'); if (pMaxM) pMaxM.value = '';

        // Reset rating
        document.querySelectorAll('.rating-filter-btn').forEach((btn, i) => {
            btn.classList.toggle('bg-white', i === 0);
            btn.classList.toggle('text-white', i === 0);
            btn.classList.toggle('font-semibold', i === 0);
            btn.classList.toggle('bg-transparent', i !== 0);
            btn.classList.toggle('text-[#7A6248]', i !== 0);
            btn.classList.toggle('font-medium', i !== 0);
        });

        // Reset sort
        const sortEl = document.getElementById('sort-select'); if (sortEl) sortEl.value = 'default';
        const sortMob = document.getElementById('sort-select-mobile'); if (sortMob) sortMob.value = 'default';

        applyFilters();
    }

    // ── Transaction tabs ──────────────────────────────────────────
    function switchTab(evt, tabId) {
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        evt.currentTarget.classList.add('active');
    }

    // ── Live Order Tracker (Shopee Food Style) ──────────────────────
    const trackerEl = document.getElementById('shopee-food-tracker');
    if (trackerEl) {
        const orderId = trackerEl.dataset.orderId;
        let lastStatus = null;
        let pollInterval;

        function updateStepStatus(status) {
            const progressLine = document.getElementById('tracker-progress-line');
            const statusText = document.getElementById('tracker-status-text');
            const bannerTitle = document.getElementById('tracker-banner-title');
            const bannerDesc = document.getElementById('tracker-banner-desc');
            const bannerIcon = document.getElementById('tracker-banner-icon');

            const step1 = document.getElementById('step-1-icon');
            const step2 = document.getElementById('step-2-icon');
            const step3 = document.getElementById('step-3-icon');

            // Default reset classes
            const setInactive = (el) => {
                el.className = 'w-10 h-10 rounded-full bg-white border-2 border-[#EDE5DA] flex items-center justify-center font-bold text-sm text-[#9A7B5A] transition-all duration-300';
            };
            const setActive = (el) => {
                el.className = 'w-10 h-10 rounded-full bg-gradient-to-br from-cs-orange to-orange-400 text-white border-cs-orange shadow-md shadow-orange-500/20 scale-110 flex items-center justify-center font-bold text-sm transition-all duration-300';
            };

            setInactive(step1);
            setInactive(step2);
            setInactive(step3);

            if (status === 'pending') {
                progressLine.style.width = '15%';
                setActive(step1);
                statusText.textContent = 'Awaiting Chef Confirmation';
                bannerTitle.textContent = 'Awaiting Chef Confirmation 👩‍🍳';
                bannerDesc.textContent = 'Chef is reviewing your order. Please wait.';
                bannerIcon.textContent = '⏳';
                bannerIcon.className = 'text-2xl animate-pulse';
            } else if (status === 'confirmed') {
                progressLine.style.width = '50%';
                setActive(step1);
                setActive(step2);
                statusText.textContent = 'Preparing 🍳';
                bannerTitle.textContent = 'Being Prepared by Chef!';
                bannerDesc.textContent = 'Chef has confirmed and is cooking your delicious meal.';
                bannerIcon.textContent = '🍳';
                bannerIcon.className = 'text-2xl animate-bounce';
            } else if (status === 'completed') {
                progressLine.style.width = '100%';
                setActive(step1);
                setActive(step2);
                setActive(step3);
                statusText.textContent = 'Ready to Serve! 🎉';
                statusText.className = 'text-cs-green font-bold';
                bannerTitle.textContent = 'Your Food is Ready! 😋';
                bannerDesc.textContent = 'Your order is ready. Please pick it up or enjoy your meal!';
                bannerIcon.textContent = '🎉';
                bannerIcon.className = 'text-2xl animate-bounce';

                if (lastStatus && lastStatus !== 'completed') {
                    showDesktopNotification("Food is Ready! 🍲", "Your food order is ready to be served by Chef.");
                    // Stop polling on terminal state
                    clearInterval(pollInterval);
                    // Refresh after a few seconds to update order tables
                    setTimeout(() => { window.location.reload(); }, 5000);
                }
            } else if (status === 'cancelled') {
                progressLine.style.width = '100%';
                progressLine.className = 'h-full bg-red-500 transition-all duration-700';
                
                step1.className = 'w-10 h-10 rounded-full bg-red-100 text-red-500 border-red-300 flex items-center justify-center font-bold text-sm';
                step2.className = 'w-10 h-10 rounded-full bg-red-100 text-red-500 border-red-300 flex items-center justify-center font-bold text-sm';
                step3.className = 'w-10 h-10 rounded-full bg-red-100 text-red-500 border-red-300 flex items-center justify-center font-bold text-sm';
                
                statusText.textContent = 'Cancelled ❌';
                statusText.className = 'text-red-500';
                bannerTitle.textContent = 'Order Cancelled/Rejected 😔';
                bannerDesc.textContent = 'Sorry, your order was rejected. Your virtual wallet balance has been fully refunded.';
                bannerIcon.textContent = '❌';
                bannerIcon.className = 'text-2xl';

                if (lastStatus && lastStatus !== 'cancelled') {
                    showDesktopNotification("Order Cancelled ❌", "Your food order has been cancelled. Your wallet balance has been refunded.");
                    clearInterval(pollInterval);
                    setTimeout(() => { window.location.reload(); }, 5000);
                }
            }

            // Notification on change
            if (lastStatus && lastStatus !== status) {
                if (status === 'confirmed') {
                    showDesktopNotification("Order Accepted by Chef! 🍳", "Chef is preparing your meal now.");
                }
            }
            lastStatus = status;
        }

        function pollOrderStatus() {
            fetch(`/api/orders/${orderId}/status`)
                .then(r => r.json())
                .then(data => {
                    if (data && data.status) {
                        updateStepStatus(data.status);
                    }
                })
                .catch(e => console.error('Poller error:', e));
        }

        // Run immediately
        pollOrderStatus();
        // Poll every 3 seconds
        pollInterval = setInterval(pollOrderStatus, 3000);
    }

    // HTML5 Web Notifications
    function requestNotificationPermission() {
        if ("Notification" in window) {
            Notification.requestPermission().then(permission => {
                if (permission === "granted") {
                    alert("Desktop notifications enabled! 🔔 We will notify you when the food is ready.");
                }
            });
        } else {
            alert("Your browser does not support desktop notifications.");
        }
    }

    // Show desktop notification
    function showDesktopNotification(title, body) {
        if ("Notification" in window && Notification.permission === "granted") {
            new Notification(title, {
                body: body,
                icon: 'https://cdn-icons-png.flaticon.com/512/3565/3565418.png'
            });
        }
    }
</script>
@endsection
