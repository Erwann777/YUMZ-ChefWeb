@extends('layouts.app')

@section('title', 'Explore Cookers — Yumz')

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
        <h1 class="text-2xl font-bold text-[#2C1810] mb-1">Explore Cookers</h1>
        <p class="text-[#7A6248] text-sm">Find the best cookers and order recipes or cooking services</p>
    </div>
    <div class="w-full sm:max-w-xs">
        <form action="{{ route('cookers.index') }}" method="GET" class="m-0" id="search-form">
            <div class="flex items-center bg-white border border-[#E8DDD2] rounded-2xl p-1.5 focus-within:border-[#C67C4E] focus-within:ring-2 focus-within:ring-[#C67C4E]/10 transition-all shadow-sm relative">
                <input type="text" name="search" id="global-search" value="{{ $search ?? '' }}" 
                    placeholder="Search cooker name..." 
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

<div id="cookers-list-container">
    @if($cookers->count() > 0)
        <div class="grid grid-cols-[repeat(auto-fill,minmax(280px,1fr))] gap-3 sm:gap-5 max-sm:grid-cols-2">
            @foreach($cookers as $cooker)
                <a href="{{ route('cookers.show', $cooker) }}" class="bg-white border border-[#E8DDD2] rounded-xl sm:rounded-2xl p-3 sm:p-6 transition-all duration-300 shadow-sm block hover:border-cs-orange/30 hover:-translate-y-1 hover:shadow-md no-underline animate-fadeInUp">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-3 sm:mb-4">
                        <div class="w-10 h-10 sm:w-[52px] sm:h-[52px] rounded-full bg-gradient-to-br from-cs-orange to-[#ff7337] flex items-center justify-center text-sm sm:text-xl font-bold text-white flex-shrink-0 overflow-hidden border border-[#E8DDD2]">
                            @if($cooker->profile_photo_path)
                                <img src="{{ $cooker->getProfilePhotoUrl() }}" alt="{{ $cooker->name }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($cooker->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="text-xs sm:text-[1.05rem] font-semibold text-[#2C1810] mb-0.5 truncate max-w-[120px] sm:max-w-none" title="{{ $cooker->name }}">{{ $cooker->name }}</div>
                            <div class="text-[0.55rem] sm:text-[0.72rem] text-cs-orange font-medium">Cooker</div>
                        </div>
                    </div>
                    <div class="flex gap-4 sm:gap-6 mb-3 sm:mb-0">
                        <div>
                            <div class="text-xs sm:text-lg font-bold text-[#2C1810]">⭐ {{ number_format($cooker->averageCookerRating(), 1) }}</div>
                            <div class="text-[0.55rem] sm:text-[0.68rem] text-[#7A6248] uppercase tracking-wider">Rating</div>
                        </div>
                        <div>
                            <div class="text-xs sm:text-lg font-bold text-[#2C1810]">{{ $cooker->recipes_count + $cooker->cooking_services_count }}</div>
                            <div class="text-[0.55rem] sm:text-[0.68rem] text-[#7A6248] uppercase tracking-wider font-semibold">Posts</div>
                            <div class="text-[0.45rem] sm:text-[0.55rem] text-[#9A7B5A] mt-0.5 font-medium">({{ $cooker->cooking_services_count }} Services, {{ $cooker->recipes_count }} Recipes)</div>
                        </div>
                        <div>
                            <div class="text-xs sm:text-lg font-bold text-[#2C1810]">{{ $cooker->followers_count ?? $cooker->followers()->count() }}</div>
                            <div class="text-[0.55rem] sm:text-[0.68rem] text-[#7A6248] uppercase tracking-wider">Followers</div>
                        </div>
                    </div>
                    <div class="inline-flex items-center gap-1 mt-3 sm:mt-4 px-2 sm:px-4 py-1.5 sm:py-2 bg-cs-orange-glow border border-cs-orange/20 rounded-lg text-cs-orange text-[0.62rem] sm:text-xs font-semibold hover:bg-cs-orange/10 transition-all w-full sm:w-auto justify-center">View Profile →</div>
                </a>
            @endforeach
        </div>

        @if($cookers->hasPages())
            <div class="flex justify-center mt-8 gap-1">
                @if($cookers->onFirstPage())
                    <span class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] transition-all bg-white border border-[#E8DDD2] text-[#9A7B5A] cursor-default">←</span>
                @else
                    <a href="{{ $cookers->previousPageUrl() }}" class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] transition-all bg-white border border-[#E8DDD2] text-[#7A6248] hover:bg-[#F5EFE6] hover:text-[#2C1810] hover:border-slate-300">←</a>
                @endif

                @foreach($cookers->getUrlRange(1, $cookers->lastPage()) as $page => $url)
                    @if($page == $cookers->currentPage())
                        <span class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] transition-all bg-cs-orange/10 border border-cs-orange/30 text-cs-orange font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] transition-all bg-white border border-[#E8DDD2] text-[#7A6248] hover:bg-[#F5EFE6] hover:text-[#2C1810] hover:border-slate-300">{{ $page }}</a>
                    @endif
                @endforeach

                @if($cookers->hasMorePages())
                    <a href="{{ $cookers->nextPageUrl() }}" class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] transition-all bg-white border border-[#E8DDD2] text-[#7A6248] hover:bg-[#F5EFE6] hover:text-[#2C1810] hover:border-slate-300">→</a>
                @else
                    <span class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-lg text-[0.82rem] transition-all bg-white border border-[#E8DDD2] text-[#9A7B5A] cursor-default">→</span>
                @endif
            </div>
        @endif
    @else
        <div class="text-center py-16 text-[#7A6248] animate-fadeInUp">
            <div class="text-5xl mb-4"></div>
            <div>No cookers have joined yet</div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
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
            url.searchParams.delete('page');

            window.history.replaceState({}, '', url.toString());

            fetch(url.toString())
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Swap list container
                    const newList = doc.getElementById('cookers-list-container');
                    const currentList = document.getElementById('cookers-list-container');
                    if (currentList && newList) {
                        currentList.outerHTML = newList.outerHTML;
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
</script>
@endsection
