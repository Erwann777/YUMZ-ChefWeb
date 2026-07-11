@extends('layouts.app')

@section('title', 'Explore Foods & Recipes — Yumz')

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

<div class="mb-8 mt-20 animate-fadeInUp flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-[#2C1810] mb-1">Explore Foods & Recipes</h1>
        <p class="text-[#7A6248] text-sm">Discover delicious local dishes and family secret recipes from the best chefs.</p>
    </div>
    <div class="w-full sm:max-w-xs">
        <form action="{{ route('foods.index') }}" method="GET" class="m-0" id="search-form">
            <div class="flex items-center bg-white border border-[#E8DDD2] rounded-2xl p-1.5 focus-within:border-[#C67C4E] focus-within:ring-2 focus-within:ring-[#C67C4E]/10 transition-all shadow-sm relative">
                <input type="text" name="search" id="global-search" value="{{ $search ?? '' }}" 
                    placeholder="Search foods or recipes..." 
                    class="w-full bg-transparent border-none px-3 py-1.5 text-[#2C1810] placeholder-cs-text-muted text-xs outline-none">
                
                <!-- Clear Button 'X' -->
                <button type="button" id="clear-search-btn" class="p-1.5 mr-1 text-[#9A7B5A] hover:text-[#2C1810] bg-transparent border-none cursor-pointer flex items-center justify-center hidden">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <button type="submit" class="px-4 py-1.5 bg-[#C67C4E] hover:bg-[#B06A3E] text-white text-xs font-semibold rounded-xl border-none cursor-pointer transition-colors shrink-0">
                    Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tab Switcher for View All -->
<div class="flex border-b border-[#E8DDD2] mb-6 animate-fadeInUp [animation-delay:0.05s]" id="tabs-switcher-container">
    <button class="marketplace-tab-btn border-b-2 border-[#C67C4E] text-[#C67C4E] text-sm font-bold px-5 py-3 cursor-pointer transition-all bg-transparent" onclick="switchMarketplaceTab('services-tab', this)">
         Cooking Services & Menus ({{ $services->total() }})
    </button>
    <button class="marketplace-tab-btn border-b-2 border-transparent text-[#7A6248] text-sm font-semibold px-5 py-3 cursor-pointer transition-all bg-transparent hover:text-[#2C1810]" onclick="switchMarketplaceTab('recipes-tab', this)">
         Family Secret Recipes ({{ $recipes->total() }})
    </button>
</div>

<!-- Jasa Cooking Grid -->
<div id="services-grid-container" class="animate-fadeInUp [animation-delay:0.1s]">
    @if($services->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
            @foreach($services as $item)
                <div class="marketplace-card bg-white border border-[#E8DDD2] rounded-xl sm:rounded-2xl overflow-hidden transition-all hover:border-[#C67C4E]/30 hover:shadow-md flex flex-col">
                    <div class="h-20 sm:h-28 bg-cs-bg-primary overflow-hidden relative">
                        <!-- Halal/Non-halal badge -->
                        <span class="absolute top-1.5 left-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-md shadow-sm border
                            @if($item->is_halal)
                                bg-green-500/90 text-white border-green-400/20
                            @else
                                bg-red-500/90 text-white border-red-400/20
                            @endif">
                            {{ $item->is_halal ? 'Halal ' : 'Non-Halal ' }}
                        </span>

                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-2xl sm:text-3xl bg-cs-orange/5 text-cs-orange"></div>
                        @endif
                        <div class="absolute bottom-1.5 right-1.5 bg-[#2C1810]/85 backdrop-blur-md px-1.5 py-0.5 rounded-full flex items-center gap-0.5">
                            <span class="opacity-75 leading-none text-[0.6rem]">{{ $item->cooker->getCountryFlag() }}</span>
                            <x-price-display :item="$item" :viewerCurrency="$viewerCurrency" size="sm" color="text-[#D4A574]" />
                        </div>
                    </div>

                    <div class="p-2 sm:p-3 flex-1 flex flex-col justify-between">
                        <div class="min-w-0">
                            <div class="flex items-center gap-1 mb-1 justify-between">
                                <span class="text-[0.55rem] sm:text-[0.62rem] text-cs-orange font-bold truncate max-w-[60px] sm:max-w-none">Chef {{ $item->cooker->name }}</span>
                                <span class="text-[0.52rem] sm:text-[0.58rem] text-[#7A6248] bg-[#F5EFE6] border border-slate-100 px-1 py-0.5 rounded capitalize truncate">
                                    {{ $item->category }}
                                </span>
                            </div>
                            <h3 class="text-[0.7rem] sm:text-xs font-bold text-[#2C1810] mb-0.5 line-clamp-1" title="{{ $item->title }}">{{ $item->title }}</h3>
                            <p class="hidden sm:block text-[0.68rem] text-[#7A6248] line-clamp-2 mb-2 leading-tight">{{ $item->description }}</p>
                        </div>

                        <div class="pt-1.5 sm:pt-2 border-t border-[#E8DDD2] flex justify-between items-center">
                            <div class="flex items-center gap-0.5">
                                <span class="text-[0.55rem] sm:text-[0.62rem] text-amber-500">⭐</span>
                                <span class="text-[0.55rem] sm:text-[0.62rem] font-bold text-[#2C1810]">{{ number_format($item->averageRating(), 1) }}</span>
                            </div>
                            <a href="{{ route('cookers.service', [$item->cooker, $item]) }}" class="text-[0.62rem] sm:text-[0.68rem] text-[#C67C4E] font-bold no-underline hover:underline">
                                Detail &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($services->hasPages())
            <div class="flex justify-center mt-8 gap-1">
                @if($services->onFirstPage())
                    <span class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] bg-white border border-[#E8DDD2] text-[#9A7B5A] cursor-default">←</span>
                @else
                    <a href="{{ $services->appends(['search' => $search, 'recipes_page' => request('recipes_page')])->previousPageUrl() }}" class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] bg-white border border-[#E8DDD2] text-[#7A6248] hover:bg-[#F5EFE6] hover:text-[#2C1810] hover:border-slate-300">←</a>
                @endif

                @foreach($services->getUrlRange(1, $services->lastPage()) as $page => $url)
                    @if($page == $services->currentPage())
                        <span class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] bg-cs-orange/10 border border-cs-orange/30 text-cs-orange font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $services->appends(['search' => $search, 'recipes_page' => request('recipes_page')])->url($page) }}" class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] bg-white border border-[#E8DDD2] text-[#7A6248] hover:bg-[#F5EFE6] hover:text-[#2C1810] hover:border-slate-300">{{ $page }}</a>
                    @endif
                @endforeach

                @if($services->hasMorePages())
                    <a href="{{ $services->appends(['search' => $search, 'recipes_page' => request('recipes_page')])->nextPageUrl() }}" class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] bg-white border border-[#E8DDD2] text-[#7A6248] hover:bg-[#F5EFE6] hover:text-[#2C1810] hover:border-slate-300">→</a>
                @else
                    <span class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] bg-white border border-[#E8DDD2] text-[#9A7B5A] cursor-default">→</span>
                @endif
            </div>
        @endif
    @else
        <div class="text-center py-12 bg-[#FBF7F3] border border-[#EDE5DA] rounded-2xl text-[#7A6248]">
            <span class="text-3xl mb-1 block"></span>
            <p class="text-sm font-semibold text-[#2C1810]">No food menus found.</p>
        </div>
    @endif
</div>

<!-- Resep Rahasia Grid -->
<div id="recipes-grid-container" class="hidden animate-fadeInUp [animation-delay:0.1s]">
    @if($recipes->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
            @foreach($recipes as $item)
                <div class="marketplace-card bg-white border border-[#E8DDD2] rounded-xl sm:rounded-2xl overflow-hidden transition-all hover:border-[#C67C4E]/30 hover:shadow-md flex flex-col">
                    <div class="h-20 sm:h-28 bg-cs-bg-primary overflow-hidden relative">
                        <!-- Halal/Non-halal badge -->
                        <span class="absolute top-1.5 left-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-md shadow-sm border
                            @if($item->is_halal)
                                bg-green-500/90 text-white border-green-400/20
                            @else
                                bg-red-500/90 text-white border-red-400/20
                            @endif">
                            {{ $item->is_halal ? 'Halal ' : 'Non-Halal ' }}
                        </span>

                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-2xl sm:text-3xl bg-cs-orange/5 text-cs-orange"></div>
                        @endif
                        <div class="absolute bottom-1.5 right-1.5 bg-[#2C1810]/85 backdrop-blur-md px-1.5 py-0.5 rounded-full flex items-center gap-0.5">
                            <span class="opacity-75 leading-none text-[0.6rem]">{{ $item->cooker->getCountryFlag() }}</span>
                            <x-price-display :item="$item" :viewerCurrency="$viewerCurrency" size="sm" color="text-[#D4A574]" />
                        </div>
                    </div>

                    <div class="p-2 sm:p-3 flex-1 flex flex-col justify-between">
                        <div class="min-w-0">
                            <div class="flex items-center gap-1 mb-1 justify-between">
                                <span class="text-[0.55rem] sm:text-[0.62rem] text-cs-orange font-bold truncate max-w-[60px] sm:max-w-none">Chef {{ $item->cooker->name }}</span>
                                <span class="text-[0.52rem] sm:text-[0.58rem] text-[#7A6248] bg-[#F5EFE6] border border-slate-100 px-1 py-0.5 rounded capitalize truncate">
                                    {{ $item->category }}
                                </span>
                            </div>
                            <h3 class="text-[0.7rem] sm:text-xs font-bold text-[#2C1810] mb-0.5 line-clamp-1" title="{{ $item->title }}">{{ $item->title }}</h3>
                            <p class="hidden sm:block text-[0.68rem] text-[#7A6248] line-clamp-2 mb-2 leading-tight">{{ $item->description }}</p>
                        </div>

                        <div class="pt-1.5 sm:pt-2 border-t border-[#E8DDD2] flex justify-between items-center">
                            <div class="flex items-center gap-0.5">
                                <span class="text-[0.55rem] sm:text-[0.62rem] text-amber-500">⭐</span>
                                <span class="text-[0.55rem] sm:text-[0.62rem] font-bold text-[#2C1810]">{{ number_format($item->averageRating(), 1) }}</span>
                            </div>
                            <a href="{{ route('cookers.recipe', [$item->cooker, $item]) }}" class="text-[0.62rem] sm:text-[0.68rem] text-[#C67C4E] font-bold no-underline hover:underline">
                                Unlock &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($recipes->hasPages())
            <div class="flex justify-center mt-8 gap-1">
                @if($recipes->onFirstPage())
                    <span class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] bg-white border border-[#E8DDD2] text-[#9A7B5A] cursor-default">←</span>
                @else
                    <a href="{{ $recipes->appends(['search' => $search, 'services_page' => request('services_page')])->previousPageUrl() }}" class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] bg-white border border-[#E8DDD2] text-[#7A6248] hover:bg-[#F5EFE6] hover:text-[#2C1810] hover:border-slate-300">←</a>
                @endif

                @foreach($recipes->getUrlRange(1, $recipes->lastPage()) as $page => $url)
                    @if($page == $recipes->currentPage())
                        <span class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] bg-cs-orange/10 border border-cs-orange/30 text-cs-orange font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $recipes->appends(['search' => $search, 'services_page' => request('services_page')])->url($page) }}" class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] bg-white border border-[#E8DDD2] text-[#7A6248] hover:bg-[#F5EFE6] hover:text-[#2C1810] hover:border-slate-300">{{ $page }}</a>
                    @endif
                @endforeach

                @if($recipes->hasMorePages())
                    <a href="{{ $recipes->appends(['search' => $search, 'services_page' => request('services_page')])->nextPageUrl() }}" class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] bg-white border border-[#E8DDD2] text-[#7A6248] hover:bg-[#F5EFE6] hover:text-[#2C1810] hover:border-slate-300">→</a>
                @else
                    <span class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] bg-white border border-[#E8DDD2] text-[#9A7B5A] cursor-default">→</span>
                @endif
            </div>
        @endif
    @else
        <div class="text-center py-12 bg-[#FBF7F3] border border-[#EDE5DA] rounded-2xl text-[#7A6248]">
            <span class="text-3xl mb-1 block"></span>
            <p class="text-sm font-semibold text-[#2C1810]">No secret recipes found.</p>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    let activeMarketplaceTab = 'services-tab';

    // Auto-switch tabs if pagination page is present in URL
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('recipes_page')) {
            const recipeTabBtn = document.querySelectorAll('.marketplace-tab-btn')[1];
            switchMarketplaceTab('recipes-tab', recipeTabBtn);
        }

        // Search clear and auto-reset logic
        const searchInput = document.getElementById('global-search');
        const clearBtn = document.getElementById('clear-search-btn');
        const searchForm = document.getElementById('search-form');
        let debounceTimer;

        function toggleClearButton() {
            if (searchInput && clearBtn) {
                if (searchInput.value.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }
        }

        function performLiveSearch(query) {
            const url = new URL(window.location.href);
            if (query) {
                url.searchParams.set('search', query);
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.delete('services_page');
            url.searchParams.delete('recipes_page');

            window.history.replaceState({}, '', url.toString());

            fetch(url.toString())
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // 1. Swap tab switcher
                    const newSwitcher = doc.getElementById('tabs-switcher-container');
                    const currentSwitcher = document.getElementById('tabs-switcher-container');
                    if (currentSwitcher && newSwitcher) {
                        currentSwitcher.innerHTML = newSwitcher.innerHTML;
                    }

                    // 2. Swap services grid
                    const newServices = doc.getElementById('services-grid-container');
                    const currentServices = document.getElementById('services-grid-container');
                    if (currentServices && newServices) {
                        currentServices.outerHTML = newServices.outerHTML;
                    }

                    // 3. Swap recipes grid
                    const newRecipes = doc.getElementById('recipes-grid-container');
                    const currentRecipes = document.getElementById('recipes-grid-container');
                    if (currentRecipes && newRecipes) {
                        currentRecipes.outerHTML = newRecipes.outerHTML;
                    }

                    // Restore tab visual states and visibility
                    if (activeMarketplaceTab === 'services-tab') {
                        document.getElementById('services-grid-container').classList.remove('hidden');
                        document.getElementById('recipes-grid-container').classList.add('hidden');
                        const buttons = document.querySelectorAll('.marketplace-tab-btn');
                        if (buttons[0]) {
                            buttons[0].classList.remove('border-transparent', 'text-[#7A6248]', 'font-semibold');
                            buttons[0].classList.add('border-[#C67C4E]', 'text-[#C67C4E]', 'font-bold');
                        }
                        if (buttons[1]) {
                            buttons[1].classList.remove('border-[#C67C4E]', 'text-[#C67C4E]', 'font-bold');
                            buttons[1].classList.add('border-transparent', 'text-[#7A6248]', 'font-semibold');
                        }
                    } else {
                        document.getElementById('services-grid-container').classList.add('hidden');
                        document.getElementById('recipes-grid-container').classList.remove('hidden');
                        const buttons = document.querySelectorAll('.marketplace-tab-btn');
                        if (buttons[0]) {
                            buttons[0].classList.remove('border-[#C67C4E]', 'text-[#C67C4E]', 'font-bold');
                            buttons[0].classList.add('border-transparent', 'text-[#7A6248]', 'font-semibold');
                        }
                        if (buttons[1]) {
                            buttons[1].classList.remove('border-transparent', 'text-[#7A6248]', 'font-semibold');
                            buttons[1].classList.add('border-[#C67C4E]', 'text-[#C67C4E]', 'font-bold');
                        }
                    }
                })
                .catch(err => console.error('Error fetching live search:', err));
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                toggleClearButton();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    performLiveSearch(searchInput.value.trim());
                }, 250);
            });
            toggleClearButton();
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                if (searchInput) {
                    searchInput.value = '';
                }
                toggleClearButton();
                performLiveSearch('');
            });
        }

        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                if (searchInput) {
                    performLiveSearch(searchInput.value.trim());
                }
            });
        }
    });

    function switchMarketplaceTab(tabId, button) {
        activeMarketplaceTab = tabId;
        
        // Show/hide grid containers
        if (tabId === 'services-tab') {
            document.getElementById('services-grid-container').classList.remove('hidden');
            document.getElementById('recipes-grid-container').classList.add('hidden');
        } else {
            document.getElementById('services-grid-container').classList.add('hidden');
            document.getElementById('recipes-grid-container').classList.remove('hidden');
        }
        
        // Update button styles
        const buttons = document.querySelectorAll('.marketplace-tab-btn');
        buttons.forEach(btn => {
            btn.classList.remove('border-[#C67C4E]', 'text-[#C67C4E]', 'font-bold');
            btn.classList.add('border-transparent', 'text-[#7A6248]', 'font-semibold');
        });
        
        button.classList.remove('border-transparent', 'text-[#7A6248]', 'font-semibold');
        button.classList.add('border-[#C67C4E]', 'text-[#C67C4E]', 'font-bold');
    }
</script>
@endsection
