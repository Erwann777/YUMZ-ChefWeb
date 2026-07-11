@extends('layouts.app')

@section('title', $service->title . ' — Yumz')
@section('body-class', 'cs-bg')

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
<div class="max-w-[800px] mx-auto mt-20 mb-12 animate-fadeInUp">
    <!-- Success/Error alert -->
    @if(session('success'))
        <div class="bg-cs-green/10 border border-cs-green/20 text-cs-green px-4 py-3 rounded-xl text-[0.85rem] mb-6 animate-fadeIn"> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-[0.85rem] mb-6 animate-fadeIn"> {{ session('error') }}</div>
    @endif

    <a href="{{ route('cookers.show', $cooker) }}" class="inline-flex items-center gap-[0.4rem] text-[0.82rem] cs-label hover:text-cs-orange transition-colors duration-200 mb-6 no-underline">← Chef Profile {{ $cooker->name }}</a>

    @if($service->image_path)
        <div class="rounded-2xl overflow-hidden mb-6 shadow-[0_4px_15px_rgba(0,0,0,0.05)]">
            <img src="{{ $service->getImageUrl() }}" alt="{{ $service->title }}" class="w-full max-h-[400px] max-sm:max-h-[250px] object-cover">
        </div>
    @endif

    <!-- Service Title & Badges -->
    <div class="flex items-start justify-between gap-4 flex-wrap mb-4">
        <div>
            <h1 class="text-[1.8rem] max-sm:text-[1.4rem] font-bold cs-title mb-[0.3rem] leading-tight">{{ $service->title }}</h1>
            
            <div class="flex items-center gap-2 flex-wrap mt-2">
                <!-- Halal Status Badge -->
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full border shadow-sm
                    @if($service->is_halal)
                        bg-green-50 text-green-700 border-green-200
                    @else
                        bg-red-50 text-red-700 border-red-200
                    @endif">
                    {{ $service->is_halal ? 'HALAL ' : 'NON-HALAL ' }}
                </span>
                
                <!-- Category Badge -->
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-white text-slate-700 border border-slate-200 capitalize">
                    {{ $service->category }}
                </span>

                <!-- Avg Rating -->
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1">
                    {{ number_format($service->averageRating(), 1) }} ({{ $reviews->count() }} Reviews)
                </span>
            </div>
        </div>
    </div>

    <!-- Chef Info & Date -->
    <div class="flex items-center gap-4 mb-6 flex-wrap">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-cs-orange to-cs-orange-hover flex items-center justify-center text-[0.7rem] font-semibold text-white">
                @if($cooker->profile_photo_path)
                    <img src="{{ $cooker->getProfilePhotoUrl() }}" alt="{{ $cooker->name }}" class="w-full h-full rounded-full object-cover">
                @else
                    {{ strtoupper(substr($cooker->name, 0, 1)) }}
                @endif
            </div>
            <span class="text-[0.82rem] cs-label">by <a href="{{ route('cookers.show', $cooker) }}" class="text-cs-orange no-underline font-medium hover:underline">{{ $cooker->name }}</a></span>
        </div>
        <span class="text-[0.78rem] cs-muted">Updated {{ $service->updated_at->format('d M Y') }}</span>
    </div>

    <hr class="border-t border-[#E8DDD2] my-6">

    <!-- Description & Booking CTA Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
        <!-- Left details (span 2) -->
        <div class="md:col-span-2">
            <h3 class="text-base font-bold text-[#2C1810] mb-3">Food / Service Description</h3>
            <div class="desc-block">
                {{ $service->description }}
            </div>
        </div>

        <!-- Right pricing / order card (span 1) -->
        <div class="md:col-span-1">
            <div class="price-card sticky top-24">
                {{-- Virtual money notice --}}
                <div class="flex items-center gap-1.5 mb-3 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 text-left">
                    <span class="text-xs shrink-0"></span>
                    <span class="text-[0.62rem] text-amber-700 leading-snug">Payment using <strong>Virtual Wallet</strong> (simulation)</span>
                </div>

                <div class="text-xs text-[#7A6248] font-medium uppercase tracking-wider mb-1">Price</div>
                <x-price-display :item="$service" :viewerCurrency="$viewerCurrency" size="lg" class="mb-3" />

                @php
                    $priceBlock = $cs->formatWithConversion((float)$service->price, $service->currency ?? 'IDR', $viewerCurrency);
                    $convertedAmt = $cs->convert((float)$service->price, $service->currency ?? 'IDR', $viewerCurrency);
                @endphp
                <div class="flex items-center justify-between mb-3 px-1">
                    <span class="text-xs text-[#9A7B5A]">Your Balance</span>
                    <span class="text-xs font-bold {{ auth()->user() && (float)auth()->user()->wallet_balance >= $convertedAmt ? 'text-green-600' : 'text-red-500' }}">
                        {{ auth()->user()?->getFormattedWalletBalance() ?? '—' }}
                    </span>
                </div>

                @if(auth()->user() && (float)auth()->user()->wallet_balance < $convertedAmt)
                <div class="text-[0.65rem] text-red-500 bg-red-50 border border-red-100 rounded-lg px-3 py-2 mb-3 text-center">
                    Insufficient balance. <a href="{{ route('wallet') }}" class="font-semibold text-red-600 hover:underline">Top-up →</a>
                </div>
                @endif


                @if(Auth::id() !== $service->cooker_id)
                    <a href="{{ route('services.order', [$cooker, $service]) }}"
                        class="block w-full text-center py-3 rounded-xl text-sm font-bold text-white cursor-pointer transition-all duration-300 no-underline shadow-[0_4px_14px_rgba(198,124,78,0.25)] hover:-translate-y-0.5 hover:shadow-[0_8px_25px_rgba(198,124,78,0.35)]"
                        style="background: linear-gradient(135deg, #C67C4E, #B06A3E);">
                         Order Service Now
                    </a>
                @else
                    <div class="text-xs text-[#9A7B5A] italic bg-[#F5EFE6] border border-slate-100 rounded-xl p-3">
                        This service belongs to you. Manage it via cooker dashboard.
                    </div>
                @endif
            </div>
        </div>
    </div>


    <!-- Rating Submission Form for Ordered Customers -->
    @if($userOrder)
        <div class="cs-card p-6 bg-[#C67C4E]/5 border border-[#C67C4E]/10 mb-8 animate-fadeIn">
            <h3 class="text-sm font-bold text-[#2C1810] mb-1">⭐ Rate This Food / Service</h3>
            <p class="text-[0.7rem] text-[#7A6248] mb-4">You have ordered this service before. Provide a review to help other customers.</p>

            <form action="{{ route('services.rate', $userOrder) }}" method="POST" class="m-0 flex flex-col gap-4">
                @csrf
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-[#7A6248] uppercase tracking-wider">Star Rating</label>
                    <div class="flex gap-1.5 text-2xl" id="stars-container">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star-item cursor-pointer text-slate-300 transition-colors" data-value="{{ $i }}" onclick="selectStars({{ $i }})"></span>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" required>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="review" class="text-xs font-bold text-[#7A6248] uppercase tracking-wider">Short Review</label>
                    <textarea name="review" id="review" rows="2" placeholder="How was the food? Was the portion right? Review the cooker's service..." 
                        class="w-full px-3 py-2 border border-[#E8DDD2] rounded-xl text-xs bg-white text-[#2C1810] outline-none focus:border-[#C67C4E] focus:ring-1 focus:ring-[#C67C4E] resize-none"></textarea>
                </div>

                <button type="submit" class="self-start px-5 py-2 bg-[#C67C4E] text-white border-none rounded-xl text-xs font-semibold cursor-pointer hover:bg-[#B06A3E] transition-colors">
                    Submit Review
                </button>
            </form>
        </div>
    @endif

    <!-- SECTION: Review Pelanggan Lain -->
    <div>
        <h3 class="text-base font-bold text-[#2C1810] mb-4 flex items-center gap-1.5">
             Customer Reviews ({{ $reviews->count() }})
        </h3>

        @if($reviews->count() > 0)
            <div class="flex flex-col gap-4">
                @foreach($reviews as $rev)
                    <div class="review-card">
                        <!-- Customer Avatar -->
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center font-bold text-sm text-slate-600 overflow-hidden shrink-0 border border-slate-100">
                            @if($rev->customer->profile_photo_path)
                                <img src="{{ $rev->customer->getProfilePhotoUrl() }}" alt="{{ $rev->customer->name }}" class="w-full h-full object-cover">
                            @else
                                {{ $rev->customer->getInitials() }}
                            @endif
                        </div>

                        <!-- Review Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 flex-wrap mb-1">
                                <h4 class="text-sm font-bold text-[#2C1810] truncate">{{ $rev->customer->name }}</h4>
                                <span class="text-[0.7rem] text-[#9A7B5A]">
                                    {{ $rev->rated_at ? $rev->rated_at->format('d M Y') : '' }}
                                </span>
                            </div>

                            <!-- Rating stars -->
                            <div class="flex gap-0.5 text-xs text-amber-500 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $rev->rating ? '' : '' }}</span>
                                @endfor
                            </div>

                            <p class="text-xs text-[#7A6248] leading-relaxed font-normal">
                                "{{ $rev->review ?? 'Buyer rated without writing a review.' }}"
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 bg-[#FBF7F3] border border-[#EDE5DA] rounded-2xl text-[#7A6248] shadow-sm">
                <span class="text-2xl mb-1 block"></span>
                <p class="text-xs">No reviews for this service yet. Be the first to leave a review!</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function selectStars(value) {
        document.getElementById('rating-input').value = value;
        const stars = document.querySelectorAll('#stars-container .star-item');
        stars.forEach(star => {
            const starVal = parseInt(star.getAttribute('data-value'));
            if (starVal <= value) {
                star.classList.remove('text-slate-300');
                star.classList.add('text-amber-500');
            } else {
                star.classList.remove('text-amber-500');
                star.classList.add('text-slate-300');
            }
        });
    }
</script>
@endsection
