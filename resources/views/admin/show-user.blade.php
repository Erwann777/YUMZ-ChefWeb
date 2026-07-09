@extends('admin.layouts.admin')

@section('title', $targetUser->name . ' — Yumz Admin')
@section('page-title', 'User Detail')

@section('topbar-actions')
    <a href="{{ route('admin.users.edit', $targetUser) }}" class="btn btn-ghost btn-sm">✏️ Edit</a>
    @if($targetUser->id !== $user->id && !($targetUser->isAdmin() && !$targetUser->is_suspended))
        <form action="{{ route('admin.users.toggle-suspend', $targetUser) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn btn-sm {{ $targetUser->is_suspended ? 'btn-primary' : 'btn-danger' }}" onclick="return confirm('{{ $targetUser->is_suspended ? 'Unsuspend' : 'Suspend' }} {{ $targetUser->name }}?')">
                {{ $targetUser->is_suspended ? '✅ Unsuspend' : '🚫 Suspend' }}
            </button>
        </form>
    @endif
@endsection

@section('content')
<div class="mb-7 anim-in">
    <div class="flex items-center gap-2 text-sm text-cs-text-secondary mb-3">
        <a href="{{ route('admin.users') }}" class="hover:text-admin-accent transition-colors">Users</a>
        <span>/</span>
        <span class="text-cs-text-primary">{{ $targetUser->name }}</span>
    </div>
</div>

<div class="grid grid-cols-[1fr_2fr] gap-5 max-[900px]:grid-cols-1 mb-7">
    {{-- Profile Card --}}
    <div class="admin-card p-6 anim-in anim-d1">
        <div class="flex flex-col items-center text-center mb-5">
            <div class="w-16 h-16 rounded-full flex items-center justify-center text-xl font-bold text-white mb-3
                {{ $targetUser->role === 'customer' ? 'bg-gradient-to-br from-blue-400 to-blue-600' : '' }}
                {{ $targetUser->role === 'cooker' ? 'bg-gradient-to-br from-orange-400 to-orange-600' : '' }}
                {{ $targetUser->role === 'admin' ? 'bg-gradient-to-br from-admin-accent to-[#00b159]' : '' }}">
                {{ strtoupper(substr($targetUser->name, 0, 1)) }}
            </div>
            <h2 class="text-lg font-bold text-cs-text-primary">{{ $targetUser->name }}</h2>
            <p class="text-sm text-cs-text-secondary">{{ $targetUser->email }}</p>
            <div class="flex items-center gap-2 mt-2">
                <span class="role-badge role-badge-{{ $targetUser->role }}">{{ $targetUser->role }}</span>
                @if($targetUser->is_suspended)
                    <span class="text-[0.68rem] font-semibold px-2 py-0.5 rounded-full bg-red-500/12 text-red-600">🚫 Suspended</span>
                @endif
            </div>
        </div>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between py-2 border-b border-admin-border">
                <span class="text-cs-text-secondary">Country</span>
                <span class="font-medium text-cs-text-primary">{{ $targetUser->getCountryFlag() }} {{ $targetUser->getCountryName() }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-admin-border">
                <span class="text-cs-text-secondary">Wallet Balance</span>
                <span class="font-medium text-cs-text-primary">{{ $targetUser->getFormattedWalletBalance() }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-admin-border">
                <span class="text-cs-text-secondary">Joined</span>
                <span class="font-medium text-cs-text-primary">{{ $targetUser->created_at->format('d M Y') }}</span>
            </div>
            @if($targetUser->phone)
            <div class="flex justify-between py-2 border-b border-admin-border">
                <span class="text-cs-text-secondary">Phone</span>
                <span class="font-medium text-cs-text-primary">{{ $targetUser->phone }}</span>
            </div>
            @endif
            @if($targetUser->bio)
            <div class="pt-2">
                <span class="text-cs-text-secondary text-xs uppercase tracking-wider">Bio</span>
                <p class="text-cs-text-primary mt-1">{{ $targetUser->bio }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-4 max-sm:grid-cols-1 anim-in anim-d2">
        <div class="admin-card p-4">
            <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Recipes</div>
            <div class="text-2xl font-bold text-cs-text-primary">{{ $targetUser->recipes_count }}</div>
        </div>
        <div class="admin-card p-4">
            <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Services</div>
            <div class="text-2xl font-bold text-cs-text-primary">{{ $targetUser->cooking_services_count }}</div>
        </div>
        <div class="admin-card p-4">
            <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Purchases</div>
            <div class="text-2xl font-bold text-cs-text-primary">{{ $targetUser->recipe_purchases_count }}</div>
        </div>
        <div class="admin-card p-4">
            <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Orders Placed</div>
            <div class="text-2xl font-bold text-cs-text-primary">{{ $targetUser->service_orders_count }}</div>
        </div>
        @if($targetUser->isCooker())
        <div class="admin-card p-4">
            <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Orders Received</div>
            <div class="text-2xl font-bold text-cs-text-primary">{{ $targetUser->cooker_orders_count }}</div>
        </div>
        <div class="admin-card p-4">
            <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Avg Rating</div>
            <div class="text-2xl font-bold text-cs-text-primary">⭐ {{ number_format($targetUser->averageCookerRating(), 1) }}</div>
        </div>
        @endif
        <div class="admin-card p-4">
            <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Transactions</div>
            <div class="text-2xl font-bold text-cs-text-primary">{{ $targetUser->wallet_transactions_count }}</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-3 gap-5 max-[1100px]:grid-cols-1">
    {{-- Recent Transactions --}}
    <div class="admin-card anim-in anim-d3">
        <div class="px-5 py-4 border-b border-admin-border">
            <h3 class="text-sm font-semibold text-cs-text-primary">💰 Recent Transactions</h3>
        </div>
        <ul class="list-none">
            @forelse($recentTransactions as $tx)
                <li class="px-5 py-3 border-b border-admin-border last:border-b-0 hover:bg-slate-50/50">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-cs-text-secondary">{{ $tx->reference_label }}</span>
                        <span class="font-medium {{ $tx->type === 'credit' ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $tx->type === 'credit' ? '+' : '-' }}{{ $targetUser->formatPrice((float) $tx->amount) }}
                        </span>
                    </div>
                    <div class="text-[0.68rem] text-cs-text-muted mt-0.5">{{ $tx->created_at->format('d M Y, H:i') }}</div>
                </li>
            @empty
                <li class="px-5 py-6 text-center text-cs-text-secondary text-sm">No transactions</li>
            @endforelse
        </ul>
    </div>

    {{-- Recent Purchases --}}
    <div class="admin-card anim-in anim-d3">
        <div class="px-5 py-4 border-b border-admin-border">
            <h3 class="text-sm font-semibold text-cs-text-primary">🥘 Recent Purchases</h3>
        </div>
        <ul class="list-none">
            @forelse($recentPurchases as $purchase)
                <li class="px-5 py-3 border-b border-admin-border last:border-b-0 hover:bg-slate-50/50">
                    <div class="text-sm font-medium text-cs-text-primary">{{ $purchase->recipe->title ?? '—' }}</div>
                    <div class="flex items-center justify-between text-[0.68rem] text-cs-text-muted mt-0.5">
                        <span>{{ $purchase->created_at->format('d M Y') }}</span>
                        <span>Rp {{ number_format($purchase->amount_paid, 0, ',', '.') }}</span>
                    </div>
                </li>
            @empty
                <li class="px-5 py-6 text-center text-cs-text-secondary text-sm">No purchases</li>
            @endforelse
        </ul>
    </div>

    {{-- Recent Orders --}}
    <div class="admin-card anim-in anim-d3">
        <div class="px-5 py-4 border-b border-admin-border">
            <h3 class="text-sm font-semibold text-cs-text-primary">📦 Recent Orders</h3>
        </div>
        <ul class="list-none">
            @forelse($recentOrders as $order)
                <li class="px-5 py-3 border-b border-admin-border last:border-b-0 hover:bg-slate-50/50">
                    <div class="text-sm font-medium text-cs-text-primary">{{ $order->service->title ?? '—' }}</div>
                    <div class="flex items-center justify-between text-[0.68rem] mt-0.5">
                        <span class="text-cs-text-muted">{{ $order->created_at->format('d M Y') }}</span>
                        <span class="font-semibold
                            {{ $order->status === 'completed' ? 'text-emerald-600' : '' }}
                            {{ $order->status === 'pending' ? 'text-amber-600' : '' }}
                            {{ $order->status === 'cancelled' ? 'text-red-600' : '' }}
                            {{ $order->status === 'confirmed' ? 'text-blue-600' : '' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </li>
            @empty
                <li class="px-5 py-6 text-center text-cs-text-secondary text-sm">No orders</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
