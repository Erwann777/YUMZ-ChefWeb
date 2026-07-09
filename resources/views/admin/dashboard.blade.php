@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard — Yumz')
@section('page-title', 'Overview')
@section('content')
<div class="mb-7 anim-in">
    <h1 class="text-2xl font-bold text-cs-text-primary mb-1">Dashboard Overview 🛡️</h1>
    <p class="text-cs-text-secondary text-sm">Monitor Yumz platform statistics and activity in real-time.</p>
</div>

{{-- User Stats --}}
<div class="grid grid-cols-[repeat(auto-fit,minmax(160px,1fr))] gap-4 mb-4">
    <div class="admin-card p-5 hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 anim-in anim-d1">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-2.5 bg-red-500/10">👥</div>
        <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Total Users</div>
        <div class="text-2xl font-bold text-cs-text-primary">{{ $totalUsers }}</div>
    </div>
    <div class="admin-card p-5 hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 anim-in anim-d2">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-2.5 bg-blue-500/10">🛒</div>
        <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Customers</div>
        <div class="text-2xl font-bold text-cs-text-primary">{{ $totalCustomers }}</div>
    </div>
    <div class="admin-card p-5 hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 anim-in anim-d3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-2.5 bg-cs-orange/10">👨‍🍳</div>
        <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Cookers</div>
        <div class="text-2xl font-bold text-cs-text-primary">{{ $totalCookers }}</div>
    </div>
    <div class="admin-card p-5 hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 anim-in anim-d4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-2.5 bg-cs-green/10">🛡️</div>
        <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Admins</div>
        <div class="text-2xl font-bold text-cs-text-primary">{{ $totalAdmins }}</div>
    </div>
</div>

{{-- Marketplace Stats --}}
<div class="grid grid-cols-[repeat(auto-fit,minmax(160px,1fr))] gap-4 mb-7">
    <div class="admin-card p-5 hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 anim-in anim-d1">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-2.5 bg-purple-500/10">🥘</div>
        <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Recipes</div>
        <div class="text-2xl font-bold text-cs-text-primary">{{ $totalRecipes }}</div>
    </div>
    <div class="admin-card p-5 hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 anim-in anim-d2">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-2.5 bg-indigo-500/10">🍽️</div>
        <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Services</div>
        <div class="text-2xl font-bold text-cs-text-primary">{{ $totalServices }}</div>
    </div>
    <div class="admin-card p-5 hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 anim-in anim-d3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-2.5 bg-amber-500/10">📦</div>
        <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Orders</div>
        <div class="text-2xl font-bold text-cs-text-primary">{{ $totalOrders }}</div>
        @if($pendingOrders > 0)
            <div class="text-[0.68rem] text-amber-600 mt-1 font-medium">{{ $pendingOrders }} pending</div>
        @endif
    </div>
    <div class="admin-card p-5 hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 anim-in anim-d4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-2.5 bg-emerald-500/10">💳</div>
        <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Purchases</div>
        <div class="text-2xl font-bold text-cs-text-primary">{{ $totalPurchases }}</div>
    </div>
    <div class="admin-card p-5 hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 anim-in anim-d5">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-2.5 bg-teal-500/10">💰</div>
        <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Total Revenue</div>
        <div class="text-xl font-bold text-cs-text-primary">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
    @if($suspendedUsers > 0)
    <div class="admin-card p-5 hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 anim-in">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-2.5 bg-red-500/10">🚫</div>
        <div class="text-[0.72rem] text-cs-text-secondary uppercase tracking-wider mb-1">Suspended</div>
        <div class="text-2xl font-bold text-red-600">{{ $suspendedUsers }}</div>
    </div>
    @endif
</div>

<div class="grid grid-cols-2 gap-5 max-[900px]:grid-cols-1 mb-5">
    {{-- Registration Chart --}}
    <div class="admin-card anim-in anim-d3">
        <div class="flex items-center justify-between px-5 py-4 border-b border-admin-border">
            <h3 class="text-sm font-semibold text-cs-text-primary">📈 Registrations — Last 7 Days</h3>
        </div>
        <div class="p-5">
            @php $maxCount = max(1, ...array_column($chartData, 'count')); @endphp
            <div class="flex items-end gap-2 h-[120px]">
                @foreach($chartData as $day)
                    <div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
                        <div class="text-[0.68rem] font-semibold text-cs-text-secondary">{{ $day['count'] }}</div>
                        <div class="w-full max-w-[40px] bg-gradient-to-b from-admin-accent to-[#00b159] rounded-t min-h-[4px] transition-[height] duration-500" style="height: {{ ($day['count'] / $maxCount) * 90 }}px;"></div>
                        <div class="text-[0.62rem] text-cs-text-secondary">{{ $day['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Revenue Chart --}}
    <div class="admin-card anim-in anim-d3">
        <div class="flex items-center justify-between px-5 py-4 border-b border-admin-border">
            <h3 class="text-sm font-semibold text-cs-text-primary">💰 Revenue — Last 7 Days</h3>
        </div>
        <div class="p-5">
            @php $maxRevenue = max(1, ...array_column($revenueChartData, 'amount')); @endphp
            <div class="flex items-end gap-2 h-[120px]">
                @foreach($revenueChartData as $day)
                    <div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
                        <div class="text-[0.62rem] font-semibold text-cs-text-secondary">{{ number_format($day['amount'], 0) }}</div>
                        <div class="w-full max-w-[40px] bg-gradient-to-b from-cs-orange to-amber-600 rounded-t min-h-[4px] transition-[height] duration-500" style="height: {{ ($day['amount'] / $maxRevenue) * 90 }}px;"></div>
                        <div class="text-[0.62rem] text-cs-text-secondary">{{ $day['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-3 gap-5 max-[1100px]:grid-cols-1 mb-5">
    {{-- Order Status Breakdown --}}
    <div class="admin-card anim-in anim-d4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-admin-border">
            <h3 class="text-sm font-semibold text-cs-text-primary">📦 Order Status</h3>
            <a href="{{ route('admin.orders') }}" class="text-xs text-admin-accent font-medium hover:underline">View all →</a>
        </div>
        <div class="p-5 space-y-3">
            @foreach([
                'pending' => ['⏳', 'bg-amber-500'],
                'confirmed' => ['✅', 'bg-blue-500'],
                'completed' => ['🎉', 'bg-emerald-500'],
                'cancelled' => ['❌', 'bg-red-500'],
            ] as $status => [$icon, $barColor])
                @php $count = $orderStatusCounts[$status]; $total = max(1, array_sum($orderStatusCounts)); @endphp
                <div>
                    <div class="flex items-center justify-between text-[0.78rem] mb-1">
                        <span class="text-cs-text-secondary">{{ $icon }} {{ ucfirst($status) }}</span>
                        <span class="font-semibold text-cs-text-primary">{{ $count }}</span>
                    </div>
                    <div class="h-1.5 bg-admin-bg rounded-full overflow-hidden">
                        <div class="h-full {{ $barColor }} rounded-full" style="width: {{ ($count / $total) * 100 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Top Cookers --}}
    <div class="admin-card anim-in anim-d4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-admin-border">
            <h3 class="text-sm font-semibold text-cs-text-primary">🏆 Top Cookers</h3>
        </div>
        <ul class="list-none">
            @forelse($topCookers as $cooker)
                <li class="flex items-center gap-3 px-5 py-3 border-b border-admin-border last:border-b-0 hover:bg-slate-50/50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-xs font-semibold text-white flex-shrink-0">
                        {{ strtoupper(substr($cooker->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-[0.82rem] font-medium text-cs-text-primary truncate">{{ $cooker->name }}</div>
                        <div class="text-[0.68rem] text-cs-text-muted">{{ $cooker->recipes_count }} recipes · {{ $cooker->cooking_services_count }} services</div>
                    </div>
                    <div class="text-[0.78rem] font-semibold text-admin-accent">{{ $cooker->cooker_orders_count }} orders</div>
                </li>
            @empty
                <li class="text-center p-6 text-cs-text-secondary text-[0.85rem]">No cookers yet</li>
            @endforelse
        </ul>
    </div>

    {{-- Recent Activity --}}
    <div class="admin-card anim-in anim-d4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-admin-border">
            <h3 class="text-sm font-semibold text-cs-text-primary">🔔 Recent Activity</h3>
            <a href="{{ route('admin.activity-log') }}" class="text-xs text-admin-accent font-medium hover:underline">View all →</a>
        </div>
        <ul class="list-none">
            @forelse($recentActivities as $activity)
                <li class="flex items-start gap-3 px-5 py-3 border-b border-admin-border hover:bg-slate-50/50 transition-colors last:border-b-0">
                    <div class="w-[30px] h-[30px] rounded-lg flex items-center justify-center text-sm flex-shrink-0 bg-admin-bg border border-admin-border text-cs-text-primary">{{ $activity->icon }}</div>
                    <div class="flex-1 min-w-0">
                        <div class="text-[0.8rem] text-cs-text-secondary leading-[1.4] truncate">{{ $activity->description }}</div>
                        <div class="text-[0.68rem] text-cs-text-muted mt-[0.15rem]">{{ $activity->created_at->diffForHumans() }}</div>
                    </div>
                </li>
            @empty
                <li class="text-center p-8 text-cs-text-secondary text-[0.85rem]">No activity recorded yet</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
