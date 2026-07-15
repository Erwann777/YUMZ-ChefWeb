@extends('layouts.app')

@section('title', 'Wallet — Yumz')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#FDF6EE] via-[#FFF9F4] to-[#FFFDF9] pt-8 pb-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">        {{-- Virtual Money Disclaimer --}}
        <div class="mb-6 p-4 rounded-2xl border border-amber-200 bg-gradient-to-r from-amber-50 to-orange-50 flex gap-3">
            <span class="text-xl shrink-0"></span>
            <div>
                <div class="text-sm font-semibold text-amber-800">Virtual Wallet — Project Simulation</div>
                <div class="text-xs text-amber-700 mt-0.5 leading-relaxed">
                    Balances and all transactions on this page use <strong>virtual money for simulation</strong>.
                    This is not a real financial transaction and does not represent real money.
                </div>
            </div>
        </div>

        {{-- Wallet Card --}}
        <div class="relative overflow-hidden rounded-3xl mb-8 p-7 text-white shadow-2xl"
             style="background: linear-gradient(135deg, #3cdd41ff 0%, #13c531ff 50%, #18a539ff 100%)">
            <!-- Background pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -translate-y-32 translate-x-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full translate-y-24 -translate-x-24"></div>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <div class="text-sm font-medium opacity-75 mb-1">
                            {{ $user->getCountryFlag() }} {{ $user->getCountryName() }} · {{ $user->currency ?? 'IDR' }}
                        </div>
                        <div class="text-3xl font-bold tracking-tight">
                            {{ $user->getFormattedWalletBalance() }}
                        </div>
                        <div class="text-sm opacity-60 mt-1">Your Virtual Balance</div>
                    </div>
                    
                </div>
                <div class="flex gap-4 text-sm">
                    <div class="bg-white/15 rounded-xl px-4 py-2">
                        <div class="opacity-70 text-xs mb-0.5">Total Income</div>
                        <div class="font-semibold">
                            @php
                                $cs = new \App\Services\CurrencyService();
                                echo $cs->formatAmount((float)$totalCredits, $user->currency ?? 'IDR');
                            @endphp
                        </div>
                    </div>
                    <div class="bg-white/15 rounded-xl px-4 py-2">
                        <div class="opacity-70 text-xs mb-0.5">Total Expense</div>
                        <div class="font-semibold">
                            @php echo $cs->formatAmount((float)$totalDebits, $user->currency ?? 'IDR'); @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6 mb-8">
            {{-- Top-Up Simulation --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-[#F0E8DE] p-6 h-full">
                    <div class="flex items-center gap-2 mb-4">
                        <h3 class="font-semibold text-[#2C1810]">Simulate Top-Up</h3>
                    </div>
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl px-4 py-3 mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form action="{{ route('wallet.topup') }}" method="POST" id="topup-form">
                        @csrf
                        <label class="block text-xs font-semibold text-[#64748B] uppercase tracking-wide mb-2">
                            Amount ({{ $currency }})
                        </label>

                        {{-- Quick amounts --}}
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            @php
                                $quickAmounts = match($currency) {
                                    'SGD' => [50, 100, 200, 500],
                                    'MYR' => [100, 200, 500, 1000],
                                    default => [100000, 500000, 1000000, 5000000],
                                };
                            @endphp
                            @foreach($quickAmounts as $qa)
                            <button type="button" onclick="setAmount({{ $qa }})"
                                class="quick-amount-btn text-xs py-2 px-3 border border-[#E0D5C8] rounded-lg hover:border-[#C67C4E] hover:bg-[#FDF5EE] transition-all font-medium text-[#5D3A1A]">
                                {{ $cs->formatAmount($qa, $currency) }}
                            </button>
                            @endforeach
                        </div>

                        <input type="number" name="amount" id="topup-amount"
                            class="w-full border border-[#E0D5C8] rounded-xl px-4 py-3 text-sm text-[#2C1810] focus:outline-none focus:border-[#C67C4E] focus:ring-2 focus:ring-[#C67C4E]/10 mb-3"
                            placeholder="Enter amount" min="1" step="any" required>

                        <button type="submit"
                            class="w-full py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl text-sm font-semibold hover:shadow-md transition-all hover:-translate-y-0.5">
                            Top-Up Now
                        </button>
                        <p class="text-[0.65rem] text-[#94a3b8] mt-2 text-center">Simulation only — not a real transaction</p>
                    </form>
                </div>
            </div>

            {{-- Exchange Rates --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-[#F0E8DE] p-6 h-full">
                    <div class="flex items-center gap-2 mb-4">
                        <h3 class="font-semibold text-[#2C1810]">Current Exchange Rates</h3>
                        <span class="ml-auto text-[0.65rem] text-[#94a3b8] bg-[#F8F4F0] px-2 py-1 rounded-full">1h Cache</span>
                    </div>
                    <div class="grid grid-cols-1 gap-3">
                        @php
                            $rateDisplay = [
                                ['from' => ' IDR', 'to' => ' SGD', 'rate' => number_format($rates['SGD'] ?? 0.000087, 6)],
                                ['from' => ' IDR', 'to' => ' MYR', 'rate' => number_format($rates['MYR'] ?? 0.000294, 6)],
                                ['from' => ' SGD', 'to' => ' IDR', 'rate' => number_format(1 / ($rates['SGD'] ?? 0.000087), 2)],
                                ['from' => ' MYR', 'to' => ' IDR', 'rate' => number_format(1 / ($rates['MYR'] ?? 0.000294), 2)],
                                ['from' => ' SGD', 'to' => ' MYR', 'rate' => number_format(($rates['MYR'] ?? 0.000294) / ($rates['SGD'] ?? 0.000087), 4)],
                                ['from' => ' MYR', 'to' => ' SGD', 'rate' => number_format(($rates['SGD'] ?? 0.000087) / ($rates['MYR'] ?? 0.000294), 4)],
                            ];
                        @endphp
                        @foreach($rateDisplay as $r)
                        <div class="flex items-center justify-between py-2 px-3 bg-[#FAFAF9] rounded-xl border border-[#F0EAE3]">
                            <div class="text-sm text-[#5D3A1A]">{{ $r['from'] }} <span class="text-[#C67C4E] mx-1">→</span> {{ $r['to'] }}</div>
                            <div class="text-sm font-bold text-[#2C1810]">{{ $r['rate'] }}</div>
                        </div>
                        @endforeach
                    </div>
                    <div id="rates-source" class="mt-3 text-[0.65rem] text-[#94a3b8] text-center">
                        Source: Frankfurter API · with automatic fallback
                    </div>
                </div>
            </div>
        </div>

        {{-- Transaction History --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[#F0E8DE] overflow-hidden">
            <div class="px-6 py-5 border-b border-[#F0E8DE] flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h3 class="font-semibold text-[#2C1810]">Transaction History</h3>
                </div>
                <span class="text-xs text-[#94a3b8]">{{ $transactions->total() }} transactions</span>
            </div>

            @if($transactions->isEmpty())
            <div class="py-16 text-center">
                <div class="text-5xl mb-3"></div>
                <div class="text-[#5D3A1A] font-medium">No transactions yet</div>
                <div class="text-sm text-[#94a3b8] mt-1">Start buying recipes or ordering cooker services!</div>
                <a href="{{ route('foods.index') }}"
                    class="inline-block mt-4 px-5 py-2.5 bg-gradient-to-r from-[#C67C4E] to-[#A0522D] text-white text-sm font-semibold rounded-xl hover:shadow-md transition-all">
                    Explore Foods
                </a>
            </div>
            @else
            <div class="divide-y divide-[#F8F4F0]">
                @foreach($transactions as $tx)
                <div class="px-6 py-4 hover:bg-[#FAFAF9] transition-colors">
                    <div class="flex items-start gap-4">
                        {{-- Details --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="text-sm font-semibold text-[#2C1810] truncate">{{ $tx->description }}</span>
                                <span class="shrink-0 text-[0.65rem] bg-[#F8F4F0] text-[#8B5E3C] px-2 py-0.5 rounded-full">
                                    {{ $tx->reference_label }}
                                </span>
                            </div>
                            @if($tx->hadConversion())
                            <div class="text-xs text-[#94a3b8] mt-1 flex items-center gap-1.5 flex-wrap">
                                <span class="bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded text-[0.65rem] font-medium">
                                    Conversion: {{ $cs->formatAmount((float)$tx->original_amount, $tx->original_currency) }}
                                    →
                                    {{ $cs->formatAmount((float)$tx->amount, $tx->currency) }}
                                </span>
                                <span class="text-[0.6rem] text-[#b4a89b]">
                                    (rate: {{ number_format($tx->exchange_rate, 6) }})
                                </span>
                            </div>
                            @endif
                            <div class="text-[0.7rem] text-[#b4a89b] mt-1">
                                {{ $tx->created_at->diffForHumans() }} · {{ $tx->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                        {{-- Amount --}}
                        <div class="text-right shrink-0">
                            <div class="text-base font-bold {{ $tx->type === 'credit' ? 'text-green-600' : 'text-red-500' }}">
                                {{ $tx->type === 'credit' ? '+' : '-' }}{{ $cs->formatAmount((float)$tx->amount, $tx->currency) }}
                            </div>
                            <div class="text-[0.7rem] text-[#94a3b8] mt-0.5">{{ $tx->currency }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-[#F0E8DE]">
                {{ $transactions->links() }}
            </div>
            @endif
            @endif
        </div>

    </div>
</div>

<script>
function setAmount(val) {
    document.getElementById('topup-amount').value = val;
    document.querySelectorAll('.quick-amount-btn').forEach(b => b.classList.remove('border-[#C67C4E]', 'bg-[#FDF5EE]'));
    event.target.classList.add('border-[#C67C4E]', 'bg-[#FDF5EE]');
}
</script>
@endsection
