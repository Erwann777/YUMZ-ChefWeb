@extends('admin.layouts.admin')

@section('title', 'Transactions — Yumz Admin')
@section('page-title', 'Transactions')
@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4 anim-in">
    <div>
        <h1 class="text-2xl font-bold text-cs-text-primary">💰 Wallet Transactions</h1>
        <p class="text-cs-text-secondary text-sm">Audit all wallet activity across the platform</p>
    </div>
</div>

<div class="grid grid-cols-[repeat(auto-fit,minmax(200px,1fr))] gap-4 mb-5 anim-in anim-d1">
    <div class="admin-card p-4">
        <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Total Credits</div>
        <div class="text-xl font-bold text-emerald-600">Rp {{ number_format($totalCredits, 0, ',', '.') }}</div>
    </div>
    <div class="admin-card p-4">
        <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Total Debits</div>
        <div class="text-xl font-bold text-red-600">Rp {{ number_format($totalDebits, 0, ',', '.') }}</div>
    </div>
</div>

<form method="GET" action="{{ route('admin.transactions') }}" class="flex items-center gap-2.5 flex-wrap mb-5 anim-in anim-d1">
    <input
        type="text"
        name="search"
        class="flex-1 min-w-[200px] px-4 py-2.5 bg-white border border-admin-border rounded-lg text-cs-text-primary text-sm outline-none focus:border-admin-accent focus:ring-3 focus:ring-admin-accent-glow placeholder:text-cs-text-muted"
        placeholder="🔍 Search user or description..."
        value="{{ $search ?? '' }}"
    >
    <select name="type" class="px-3 py-2.5 bg-white border border-admin-border rounded-lg text-sm outline-none cursor-pointer" onchange="this.form.submit()">
        <option value="">All Types</option>
        <option value="credit" {{ ($typeFilter ?? '') === 'credit' ? 'selected' : '' }}>💚 Credit</option>
        <option value="debit" {{ ($typeFilter ?? '') === 'debit' ? 'selected' : '' }}>🔴 Debit</option>
    </select>
    <select name="reference" class="px-3 py-2.5 bg-white border border-admin-border rounded-lg text-sm outline-none cursor-pointer" onchange="this.form.submit()">
        <option value="">All References</option>
        <option value="topup" {{ ($referenceFilter ?? '') === 'topup' ? 'selected' : '' }}>Top-Up</option>
        <option value="recipe_purchase" {{ ($referenceFilter ?? '') === 'recipe_purchase' ? 'selected' : '' }}>Recipe Purchase</option>
        <option value="service_order" {{ ($referenceFilter ?? '') === 'service_order' ? 'selected' : '' }}>Service Order</option>
        <option value="sale_credit" {{ ($referenceFilter ?? '') === 'sale_credit' ? 'selected' : '' }}>Sale Credit</option>
        <option value="refund" {{ ($referenceFilter ?? '') === 'refund' ? 'selected' : '' }}>Refund</option>
        <option value="initial_credit" {{ ($referenceFilter ?? '') === 'initial_credit' ? 'selected' : '' }}>Initial Credit</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Search</button>
    @if($search || $typeFilter || $referenceFilter)
        <a href="{{ route('admin.transactions') }}" class="btn btn-ghost btn-sm">Reset</a>
    @endif
</form>

<div class="admin-card anim-in anim-d2">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">User</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Type</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Reference</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Amount</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border max-sm:hidden">Description</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3 text-sm border-b border-admin-border">
                            @if($tx->user)
                                <a href="{{ route('admin.users.show', $tx->user) }}" class="font-medium text-cs-text-primary hover:text-admin-accent">{{ $tx->user->name }}</a>
                            @else
                                <span class="text-cs-text-muted">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-sm border-b border-admin-border">
                            <span class="text-[0.68rem] font-semibold px-2 py-0.5 rounded-full {{ $tx->type === 'credit' ? 'bg-emerald-500/12 text-emerald-600' : 'bg-red-500/12 text-red-600' }}">
                                {{ $tx->type === 'credit' ? '↑ Credit' : '↓ Debit' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-sm text-cs-text-secondary border-b border-admin-border">{{ $tx->reference_label }}</td>
                        <td class="px-5 py-3 text-sm font-medium border-b border-admin-border {{ $tx->type === 'credit' ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $tx->type === 'credit' ? '+' : '-' }}{{ $tx->user ? $tx->user->formatPrice((float) $tx->amount) : number_format($tx->amount, 0) }}
                        </td>
                        <td class="px-5 py-3 text-sm text-cs-text-secondary border-b border-admin-border max-sm:hidden truncate max-w-[200px]">{{ $tx->description ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-cs-text-muted border-b border-admin-border">{{ $tx->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-cs-text-secondary">
                            <div class="text-4xl mb-3">💰</div>
                            <div>No transactions found</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
        <div class="px-5 py-4 border-t border-admin-border flex items-center justify-between flex-wrap gap-2">
            <div class="text-[0.78rem] text-cs-text-secondary">
                Showing {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} of {{ $transactions->total() }} transactions
            </div>
            <div class="flex gap-1">
                @if($transactions->onFirstPage())
                    <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-transparent border border-admin-border text-cs-text-muted">←</span>
                @else
                    <a href="{{ $transactions->previousPageUrl() }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50">←</a>
                @endif
                @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                    @if($page == $transactions->currentPage())
                        <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-admin-accent/10 border border-admin-accent/30 text-admin-accent font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50">{{ $page }}</a>
                    @endif
                @endforeach
                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50">→</a>
                @else
                    <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-transparent border border-admin-border text-cs-text-muted">→</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
