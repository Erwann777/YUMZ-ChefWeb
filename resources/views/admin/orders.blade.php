@extends('admin.layouts.admin')

@section('title', 'Manage Orders — Yumz Admin')
@section('page-title', 'Orders')
@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4 anim-in">
    <div>
        <h1 class="text-2xl font-bold text-cs-text-primary">Order Management</h1>
        <p class="text-cs-text-secondary text-sm">Monitor and manage all service orders across the platform</p>
    </div>
</div>

<form method="GET" action="{{ route('admin.orders') }}" class="flex items-center gap-2.5 flex-wrap mb-5 anim-in anim-d1">
    <input
        type="text"
        name="search"
        class="flex-1 min-w-[200px] px-4 py-2.5 bg-white border border-admin-border rounded-lg text-cs-text-primary text-sm font-sans outline-none transition-all focus:border-admin-accent focus:ring-3 focus:ring-admin-accent-glow placeholder:text-cs-text-muted"
        placeholder="🔍 Search customer, cooker, or service..."
        value="{{ $search ?? '' }}"
    >
    <select name="status" class="px-3 py-2.5 bg-white border border-admin-border rounded-lg text-cs-text-primary text-sm font-sans outline-none cursor-pointer transition-all focus:border-admin-accent" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="pending" {{ ($statusFilter ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="confirmed" {{ ($statusFilter ?? '') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
        <option value="completed" {{ ($statusFilter ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
        <option value="cancelled" {{ ($statusFilter ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Search</button>
    @if($search || $statusFilter)
        <a href="{{ route('admin.orders') }}" class="btn btn-ghost btn-sm">Reset</a>
    @endif
</form>

<div class="admin-card anim-in anim-d2">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Order</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Service</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border max-sm:hidden">Customer</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border max-sm:hidden">Cooker</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Amount</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Status</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3 text-sm border-b border-admin-border">
                            <div class="font-medium text-cs-text-primary">#{{ $order->id }}</div>
                            <div class="text-xs text-cs-text-muted">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </td>
                        <td class="px-5 py-3 text-sm text-cs-text-secondary border-b border-admin-border">
                            <div class="font-medium text-cs-text-primary">{{ $order->service->title ?? '—' }}</div>
                        </td>
                        <td class="px-5 py-3 text-sm text-cs-text-secondary border-b border-admin-border max-sm:hidden">
                            <a href="{{ route('admin.users.show', $order->customer) }}" class="hover:text-admin-accent transition-colors">{{ $order->customer->name ?? '—' }}</a>
                        </td>
                        <td class="px-5 py-3 text-sm text-cs-text-secondary border-b border-admin-border max-sm:hidden">
                            <a href="{{ route('admin.users.show', $order->cooker) }}" class="hover:text-admin-accent transition-colors">{{ $order->cooker->name ?? '—' }}</a>
                        </td>
                        <td class="px-5 py-3 text-sm font-medium text-cs-text-primary border-b border-admin-border">{{ $order->formatted_price }}</td>
                        <td class="px-5 py-3 text-sm border-b border-admin-border">
                            <span class="inline-flex items-center gap-1 text-[0.68rem] font-semibold px-2 py-0.5 rounded-full
                                {{ $order->status === 'pending' ? 'bg-amber-500/12 text-amber-600' : '' }}
                                {{ $order->status === 'confirmed' ? 'bg-blue-500/12 text-blue-600' : '' }}
                                {{ $order->status === 'completed' ? 'bg-emerald-500/12 text-emerald-600' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-500/12 text-red-600' : '' }}">
                                {{ $order->status_badge }} {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-sm border-b border-admin-border">
                            @if($order->status !== 'cancelled' && $order->status !== 'completed')
                                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline-flex items-center gap-1">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="text-[0.72rem] px-2 py-1 bg-white border border-admin-border rounded-md outline-none cursor-pointer" onchange="if(confirm('Update order #{{ $order->id }} status?')) this.form.submit(); else this.value='{{ $order->status }}'">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </form>
                            @else
                                <span class="text-xs text-cs-text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-cs-text-secondary">
                            <div>No orders found</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="px-5 py-4 border-t border-admin-border flex items-center justify-between flex-wrap gap-2">
            <div class="text-[0.78rem] text-cs-text-secondary">
                Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }} orders
            </div>
            <div class="flex gap-1">
                @if($orders->onFirstPage())
                    <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-transparent border border-admin-border text-cs-text-muted">←</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50">←</a>
                @endif
                @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                    @if($page == $orders->currentPage())
                        <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-admin-accent/10 border border-admin-accent/30 text-admin-accent font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50">{{ $page }}</a>
                    @endif
                @endforeach
                @if($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50">→</a>
                @else
                    <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-transparent border border-admin-border text-cs-text-muted">→</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
