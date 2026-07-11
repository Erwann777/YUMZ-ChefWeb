@extends('layouts.app')

@section('title', 'Order Service — Yumz')

@section('styles')
<style>
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
</style>
@endsection

@section('body-class', 'cs-bg')

@section('content')
<div class="max-w-[600px] mx-auto mt-20 pb-12">
    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/20 text-red-600 px-4 py-3 rounded-xl text-[0.85rem] mb-6 anim-in">❌ {{ session('error') }}</div>
    @endif

    <a href="{{ route('cookers.show', $cooker) }}" class="inline-flex items-center gap-[0.4rem] text-[0.82rem] text-[#7A6248] hover:text-cs-orange transition-colors duration-200 mb-6 no-underline anim-in">← Chef Profile {{ $cooker->name }}</a>

    <div class="mb-6 anim-in">
        <h1 class="text-[1.5rem] font-bold text-[#2C1810] mb-[0.3rem]">Order Cooking Service</h1>
    </div>

    {{-- Service summary card --}}
    <div class="bg-white border border-[#E8DDD2] rounded-[14px] p-6 mb-4 shadow-[0_2px_8px_rgba(49,53,59,0.04)] anim-in anim-d1">
        <div class="text-[1.1rem] font-semibold text-[#2C1810] mb-[0.3rem]">{{ $service->title }}</div>
        <div class="text-[0.82rem] text-[#7A6248] mb-3">
            by {{ $cooker->name }}
            <span class="ml-2 text-[0.65rem] bg-[#F5EFE6] text-[#8B5E3C] px-2 py-0.5 rounded-full">
                {{ $cooker->getCountryFlag() }} {{ $cooker->getCountryName() }}
            </span>
        </div>
        <div class="text-[0.85rem] text-[#7A6248] leading-[1.5] mb-4 pb-4 border-b border-[#E8DDD2]">{{ $service->description }}</div>

        {{-- Virtual money disclaimer --}}
        <div class="flex items-center gap-2 mb-4 p-3 bg-amber-50 border border-amber-200 rounded-xl">
            <span class="text-sm shrink-0">⚠️</span>
            <span class="text-xs text-amber-700">Transactions use <strong>Virtual Wallet</strong> — this is a project simulation, not real money.</span>
        </div>

        {{-- Price breakdown --}}
        <div class="space-y-2.5">
            <div class="flex items-center justify-between">
                <span class="text-[0.85rem] text-[#7A6248]">Price</span>
                <x-price-display :item="$service" :viewerCurrency="$viewerCurrency" size="md" />
            </div>



            <div class="flex items-center justify-between border-t border-[#E8DDD2] pt-2.5">
                <span class="text-[0.85rem] text-[#7A6248]">
                    {{ auth()->user()->getCountryFlag() }} Your wallet balance
                </span>
                <span class="text-[0.95rem] font-bold
                    @if($conversionInfo)
                        {{ (float)auth()->user()->wallet_balance >= $conversionInfo['converted_amount'] ? 'text-green-600' : 'text-red-500' }}
                    @else
                        text-green-600
                    @endif">
                    {{ auth()->user()->getFormattedWalletBalance() }}
                </span>
            </div>

            @if($conversionInfo && (float)auth()->user()->wallet_balance < $conversionInfo['converted_amount'])
            <div class="text-xs text-red-500 bg-red-50 border border-red-100 rounded-xl px-4 py-3 flex items-center gap-2">
                
                <span>Insufficient balance. <a href="{{ route('wallet') }}" class="font-semibold text-red-600 hover:underline">Top-up wallet →</a></span>
            </div>
            @endif
        </div>
    </div>

    <div class="bg-white border border-[#E8DDD2] rounded-[14px] p-6 shadow-[0_2px_8px_rgba(49,53,59,0.04)] anim-in anim-d2">
        <form method="POST" action="{{ route('services.order.store', $service) }}">
            @csrf

            <div class="mb-5">
                <label for="notes" class="block text-[0.8rem] font-medium text-[#7A6248] mb-[0.4rem]">Notes for Cooker (optional)</label>
                <textarea name="notes" id="notes"
                    class="w-full px-4 py-3 bg-white border border-[#E8DDD2] rounded-lg text-[#2C1810] text-[0.9rem] font-sans outline-none resize-y min-h-[100px] transition-all duration-200 focus:border-cs-orange focus:ring-3 focus:ring-cs-orange/10"
                    placeholder="Example: For a birthday event, 10 people, on June 15th...">{{ old('notes') }}</textarea>
                <div class="text-[0.72rem] text-[#9A7B5A] mt-[0.3rem]">Write order details, date, number of servings, or special requests</div>
            </div>

            @php
                $priceBlock   = $cs->formatWithConversion((float)$service->price, $service->currency ?? 'IDR', $viewerCurrency);
                $confirmPrice = $priceBlock['primary'];
                $confirmBalance = auth()->user()->getFormattedWalletBalance();
                $insufficient   = $conversionInfo && (float)auth()->user()->wallet_balance < $conversionInfo['converted_amount'];
            @endphp

            <button type="submit"
                class="w-full p-3.5 bg-gradient-to-br from-cs-orange to-[#ff7337] text-white border-none rounded-xl text-[1rem] font-semibold cursor-pointer transition-all duration-300 mt-2 shadow-[0_4px_12px_rgba(238,77,45,0.15)] hover:-translate-y-0.5 hover:shadow-[0_8px_25px_rgba(238,77,45,0.3)]"
                {{ $insufficient ? 'disabled' : '' }}
                style="{{ $insufficient ? 'opacity:0.5;cursor:not-allowed;' : '' }}"
                onclick="return confirm('Confirm Order:\n\nService: {{ addslashes($service->title) }}\nPrice: {{ addslashes($confirmPrice) }}\nYour Balance: {{ addslashes($confirmBalance) }}\n\nVirtual Wallet Transaction (Simulation)\nContinue?')">
                 Confirm Order — {{ $confirmPrice }}
            </button>
        </form>
    </div>
</div>
@endsection
