@extends('layouts.app')

@section('title', 'Cooker Dashboard — Yumz')
@section('body-class', 'cs-bg')
@section('main-class', 'w-full px-4 sm:px-6 xl:px-8 py-6')

@section('styles')
<style>
    /* Cooker Panel Premium Styles */
    .cooker-dash-stat { background:#ffffff; border:1px solid #EDE5DA; border-radius:16px; padding:1.25rem; box-shadow:0 2px 12px rgba(44,24,16,0.06); transition:all 0.25s ease; position:relative; overflow:hidden; }
    .cooker-dash-stat::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; border-radius:16px 16px 0 0; }
    .cooker-dash-stat:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(44,24,16,0.10); border-color:rgba(198,124,78,0.35); }
    .cooker-dash-stat.accent-orange::before { background:linear-gradient(90deg,#C67C4E,#e8a87c); }
    .cooker-dash-stat.accent-green::before  { background:linear-gradient(90deg,#22c55e,#4ade80); }
    .cooker-dash-stat.accent-blue::before   { background:linear-gradient(90deg,#3b82f6,#60a5fa); }
    .cooker-dash-stat.accent-purple::before { background:linear-gradient(90deg,#8b5cf6,#a78bfa); }
    .cooker-dash-stat .stat-label { font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#9A7B5A; margin-bottom:0.4rem; }
    .cooker-dash-stat .stat-value { font-size:1.7rem; font-weight:800; color:#2C1810; line-height:1; }
    .cooker-dash-stat .stat-sub   { font-size:0.7rem; color:#B0906A; font-weight:500; margin-top:0.2rem; }
    .cooker-dash-stat .stat-icon  { font-size:2.2rem; position:absolute; top:1rem; right:1.1rem; opacity:0.15; }
    /* Section title with accent bar */
    .panel-section-title { font-size:1rem; font-weight:700; color:#2C1810; display:flex; align-items:center; gap:0.5rem; }
    .panel-section-title::before { content:''; display:inline-block; width:4px; height:1.15em; background:linear-gradient(180deg,#C67C4E,#e8a87c); border-radius:4px; }
    /* Cards */
    .panel-card { background:#ffffff; border:1px solid #EDE5DA; border-radius:18px; box-shadow:0 2px 12px rgba(44,24,16,0.05); overflow:hidden; }
    .panel-card-header { padding:0.9rem 1.2rem; border-bottom:1px solid #F0E8E0; display:flex; align-items:center; justify-content:space-between; background:linear-gradient(135deg,#FFFAF7,#FBF6F0); }
    /* Service / Recipe mini cards */
    .mini-card { background:#ffffff; border:1px solid #EDE5DA; border-radius:14px; overflow:hidden; transition:all 0.22s ease; }
    .mini-card:hover { border-color:rgba(198,124,78,0.4); transform:translateY(-3px); box-shadow:0 6px 18px rgba(44,24,16,0.09); }
    .mini-card-title { font-size:0.82rem; font-weight:700; color:#2C1810; line-height:1.3; }
    .mini-card-sub   { font-size:0.7rem; color:#9A7B5A; }
    .mini-card-price { font-size:0.78rem; font-weight:700; color:#C67C4E; }
    /* Order rows */
    .panel-row { display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1.2rem; border-bottom:1px solid #F5EDE4; transition:background 0.15s ease; }
    .panel-row:hover { background:#FFFAF6; }
    .panel-row:last-child { border-bottom:none; }
    .panel-row-title  { font-size:0.84rem; font-weight:600; color:#2C1810; }
    .panel-row-meta   { font-size:0.7rem; color:#9A7B5A; }
    .panel-row-amount { font-size:0.86rem; font-weight:700; color:#C67C4E; }
    /* Status badges */
    .status-badge { font-size:0.62rem; font-weight:700; padding:0.18rem 0.6rem; border-radius:99px; text-transform:uppercase; letter-spacing:0.05em; }
    .badge-pending  { background:rgba(251,146,60,0.12); color:#c2410c; border:1px solid rgba(251,146,60,0.2); }
    .badge-done     { background:rgba(34,197,94,0.10);  color:#15803d; border:1px solid rgba(34,197,94,0.2); }
    .badge-canceled { background:rgba(239,68,68,0.10);  color:#b91c1c; border:1px solid rgba(239,68,68,0.2); }
    .badge-process  { background:rgba(59,130,246,0.10); color:#1d4ed8; border:1px solid rgba(59,130,246,0.2); }
    .badge-available { background:rgba(34,197,94,0.10); color:#15803d; border:1px solid rgba(34,197,94,0.2); }
    .badge-unavailable { background:rgba(100,116,139,0.10); color:#475569; border:1px solid rgba(100,116,139,0.2); }
    /* Welcome banner for cooker */
    .cooker-welcome { background:linear-gradient(135deg,#2C1810,#3D2B1F); border-radius:20px; padding:2rem; position:relative; overflow:hidden; }
    .cooker-welcome::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse 70% 60% at 80% 20%,rgba(198,124,78,0.25),transparent); }
    /* Action button */
    .panel-btn-primary { background:linear-gradient(135deg,#D4895A,#C67C4E); color:white; border:none; border-radius:10px; padding:0.5rem 1.1rem; font-size:0.82rem; font-weight:600; cursor:pointer; transition:all 0.2s ease; display:inline-flex; align-items:center; gap:0.4rem; text-decoration:none; box-shadow:0 2px 8px rgba(198,124,78,0.25); }
    .panel-btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 16px rgba(198,124,78,0.35); }
    .panel-btn-secondary { background:#ffffff; color:#5C4232; border:1px solid #DDD0C0; border-radius:10px; padding:0.5rem 1.1rem; font-size:0.82rem; font-weight:600; cursor:pointer; transition:all 0.2s ease; display:inline-flex; align-items:center; gap:0.4rem; text-decoration:none; }
    .panel-btn-secondary:hover { background:#F5EFE6; border-color:#C67C4E; color:#C67C4E; }
</style>
@endsection

@section('content')
@if(session('success'))
    <div class="bg-cs-green/10 border border-cs-green/20 text-cs-green px-4 py-3 rounded-xl text-sm mb-6 animate-fadeInUp mt-20">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="bg-red-500/10 border border-red-500/20 text-red-600 px-4 py-3 rounded-xl text-sm mb-6 animate-fadeInUp mt-20">❌ {{ session('error') }}</div>
@endif

<div class="mt-20 mb-12">
    {{-- Header --}}
    <div class="mb-8 animate-fadeInUp">
    {{-- Header --}}
    <div class="mb-8 animate-fadeInUp">
        <div class="text-2xl font-bold text-[#7A6248] mb-1 tracking-wider uppercase">Cooker Dashboard</div>
        <h1 class="text-3xl font-bold panel-section-title mb-2">Hello, Chef {{ $user->name }}! </h1>
        <p class="text-[#7A6248] text-sm font-normal">Manage your menu items, monitor incoming orders, and grow your culinary business.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="cooker-dash-stat accent-orange animate-fadeInUp [animation-delay:0.05s] opacity-0 bg-white border border-[#E8DDD2] rounded-2xl p-5">
            
            <div class="text-[0.72rem] text-[#7A6248] uppercase tracking-wider mb-0.5">Total Menu Items</div>
            <div class="text-2xl font-bold panel-section-title">{{ $totalServices }}</div>
        </div>
        <div class="cooker-dash-stat accent-orange animate-fadeInUp [animation-delay:0.08s] opacity-0 bg-white border border-[#E8DDD2] rounded-2xl p-5">
            
            <div class="text-[0.72rem] text-[#7A6248] uppercase tracking-wider mb-0.5">Total Secret Recipes</div>
            <div class="text-2xl font-bold panel-section-title">{{ $totalRecipes }}</div>
        </div>
        <div class="cooker-dash-stat accent-orange animate-fadeInUp [animation-delay:0.1s] opacity-0 bg-white border border-[#E8DDD2] rounded-2xl p-5">
            
            <div class="text-[0.72rem] text-[#7A6248] uppercase tracking-wider mb-0.5">Total Orders</div>
            <div class="text-2xl font-bold panel-section-title">{{ $orders->count() }}</div>
        </div>
        <div class="cooker-dash-stat accent-orange animate-fadeInUp [animation-delay:0.15s] opacity-0 bg-white border border-[#E8DDD2] rounded-2xl p-5">

            <div class="text-[0.72rem] text-[#7A6248] uppercase tracking-wider mb-0.5">Pending Orders</div>
            <div class="text-2xl font-bold panel-section-title">{{ $pendingOrders }}</div>
        </div>
        <div class="cooker-dash-stat accent-orange animate-fadeInUp [animation-delay:0.2s] opacity-0 bg-white border border-[#E8DDD2] rounded-2xl p-5">
            
            <div class="text-[0.72rem] text-[#7A6248] uppercase tracking-wider mb-0.5">Total Earnings</div>
            <div class="text-2xl font-bold panel-section-title">{{ $user->formatPrice((float)$totalEarnings) }}</div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex gap-3 mb-8 flex-wrap animate-fadeInUp [animation-delay:0.1s] opacity-0">
        <a href="{{ route('cooker.services.create') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 bg-gradient-to-br from-cs-orange to-[#ff7337] text-white shadow-[0_4px_12px_rgba(198,124,78,0.2)] hover:-translate-y-px hover:shadow-[0_6px_16px_rgba(198,124,78,0.3)] no-underline">
            ➕ Create New Menu Item
        </a>
        <a href="{{ route('cooker.recipes.create') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 bg-gradient-to-br from-[#2C1810] to-[#3D2B1F] text-white shadow-[0_4px_12px_rgba(44,24,16,0.15)] hover:-translate-y-px hover:shadow-[0_6px_16px_rgba(44,24,16,0.25)] no-underline border border-[#E8DDD2]">
            ➕ Upload New Recipe
        </a>
    </div>

    <!-- Incoming Orders (Cooking Services) -->
    <div class="panel-card mb-8 overflow-hidden animate-fadeInUp [animation-delay:0.15s] opacity-0 bg-white border border-[#E8DDD2] rounded-2xl">
        <div class="panel-card-header px-6 py-4 border-b border-[#E8DDD2] flex justify-between items-center">
            <h2 class="text-base font-semibold panel-section-title m-0"> Incoming Food Orders</h2>
            <span class="text-xs text-[#B0906A]">{{ $orders->count() }} orders</span>
        </div>
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="bg-[#F5EFE6]">
                            <th class="cs-th p-4 text-xs font-semibold text-[#7A6248] uppercase">ID</th>
                            <th class="cs-th p-4 text-xs font-semibold text-[#7A6248] uppercase">Customer</th>
                            <th class="cs-th p-4 text-xs font-semibold text-[#7A6248] uppercase">Menu Item</th>
                            <th class="cs-th p-4 text-xs font-semibold text-[#7A6248] uppercase">Total Price</th>
                            <th class="cs-th p-4 text-xs font-semibold text-[#7A6248] uppercase">Date</th>
                            <th class="cs-th p-4 text-xs font-semibold text-[#7A6248] uppercase">Status</th>
                            <th class="cs-th p-4 text-xs font-semibold text-[#7A6248] uppercase text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr class="cs-row hover:bg-[#F5EFE6] border-b border-[#E8DDD2] last:border-0">
                                <td class="cs-td p-4 font-mono text-xs">#{{ $order->id }}</td>
                                <td class="cs-td p-4">
                                    <div class="font-bold panel-section-title">{{ $order->customer->name }}</div>
                                    <div class="text-xs text-[#B0906A]">{{ $order->customer->email }}</div>
                                    @if($order->notes)
                                        <div class="text-xs text-[#7A6248] italic bg-cs-orange-glow border-l-2 border-cs-orange p-1.5 mt-1 rounded-r-md">
                                            📝 "{{ $order->notes }}"
                                        </div>
                                    @endif
                                </td>
                                <td class="cs-td p-4">
                                    <div class="font-semibold">{{ $order->service->title }}</div>
                                </td>
                                <td class="cs-td p-4 font-bold text-[#C67C4E]">{{ $order->formatted_price }}</td>
                                <td class="cs-td p-4 text-[#7A6248] text-xs">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="cs-td p-4">
                                    <span class="text-[0.68rem] px-2 py-0.5 rounded font-semibold inline-flex items-center gap-1
                                        {{ $order->status === 'pending'   ? 'bg-yellow-500/12 text-yellow-600 border border-yellow-500/20' : '' }}
                                        {{ $order->status === 'confirmed' ? 'bg-blue-500/12 text-blue-600 border border-blue-500/20'   : '' }}
                                        {{ $order->status === 'completed' ? 'bg-cs-green/12 text-cs-green border border-cs-green/20'   : '' }}
                                        {{ $order->status === 'cancelled' ? 'bg-red-500/12 text-red-600 border border-red-500/20'     : '' }}">
                                        {{ $order->status_badge }} {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="cs-td p-4">
                                    <div class="flex gap-2 justify-center flex-wrap">
                                        @if($order->status === 'pending')
                                            <form action="{{ route('cooker.orders.update-status', $order) }}" method="POST" class="inline m-0">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="px-3 py-1.5 text-xs rounded-xl font-bold cursor-pointer transition-all border-none bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white shadow-sm hover:shadow-md" onclick="return confirm('Accept this order to start cooking?')"> Accept &amp; Cook</button>
                                            </form>
                                            <form action="{{ route('cooker.orders.update-status', $order) }}" method="POST" class="inline m-0">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="px-3 py-1.5 text-xs rounded-xl font-bold cursor-pointer transition-all border border-[#fca5a5] bg-[#fef2f2] text-red-600 hover:bg-[#fee2e2]" onclick="return confirm('Reject/Cancel this order?')"> Reject</button>
                                            </form>
                                        @elseif($order->status === 'confirmed')
                                            <form action="{{ route('cooker.orders.update-status', $order) }}" method="POST" class="inline m-0">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="px-3.5 py-2 text-xs rounded-xl font-extrabold cursor-pointer transition-all border-none bg-gradient-to-r from-[#22c55e] to-[#16a34a] hover:from-[#16a34a] hover:to-[#15803d] text-white shadow-md hover:shadow-lg animate-pulse" onclick="return confirm('Mark this food ready to be served to the customer?')"> Ready to Serve!</button>
                                            </form>
                                            <form action="{{ route('cooker.orders.update-status', $order) }}" method="POST" class="inline m-0">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="px-3 py-1.5 text-xs rounded-xl font-bold cursor-pointer transition-all border border-[#fca5a5] bg-[#fef2f2] text-red-500 hover:bg-[#fee2e2]" onclick="return confirm('Cancel this order? Customer will be fully refunded.')"> Cancel</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-[#B0906A] font-medium bg-[#F5EFE6] px-2.5 py-1 rounded-lg">Completed / Cancelled</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center p-12 text-[#B0906A] text-sm">
                <div class="text-4xl mb-2"></div>
                <div>No incoming orders at the moment.</div>
            </div>
        @endif
    </div>

    {{-- Menu Makanan Saya (Cooking Services) --}}
    <div class="panel-card mb-6 overflow-hidden animate-fadeInUp [animation-delay:0.2s] opacity-0 bg-white border border-[#E8DDD2] rounded-2xl">
        <div class="panel-card-header px-6 py-4 border-b border-[#E8DDD2] flex justify-between items-center">
            <h2 class="text-base font-semibold panel-section-title m-0"> My Menu Items (Services)</h2>
            <span class="text-xs text-[#B0906A]">{{ $totalServices }} Menus</span>
        </div>
        
        @if($services->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4 p-4">
                @foreach($services as $service)
                    <div class="bg-white border border-[#E8DDD2] rounded-xl sm:rounded-2xl overflow-hidden transition-all hover:border-[#C67C4E]/30 hover:shadow-md flex flex-col relative group">
                        <!-- Halal/Non-halal badge -->
                        <span class="absolute top-1.5 left-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-sm border
                            @if($service->is_halal)
                                bg-green-500/90 text-white border-green-400/20
                            @else
                                bg-red-500/90 text-white border-red-400/20
                            @endif">
                            {{ $service->is_halal ? 'Halal 🟢' : 'Non-Halal 🔴' }}
                        </span>

                        <!-- Availability Badge -->
                        <span class="absolute top-1.5 right-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-sm border
                            @if($service->is_available)
                                bg-blue-500/90 text-white border-blue-400/20
                            @else
                                bg-slate-500/90 text-white border-slate-400/20
                            @endif">
                            {{ $service->is_available ? 'Available' : 'Out of Stock' }}
                        </span>

                        <div class="h-20 sm:h-28 bg-cs-bg-primary overflow-hidden relative">
                            @if($service->image_path)
                                <img src="{{ $service->getImageUrl() }}" alt="{{ $service->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl bg-cs-orange/5 text-cs-orange"></div>
                            @endif
                            <x-price-display :item="$service" :viewerCurrency="$viewerCurrency" size="sm" color="text-[#D4A574]" class="absolute bottom-1.5 right-1.5 bg-[#2C1810]/85 backdrop-blur-md px-1.5 py-0.5 rounded-full" />
                        </div>

                        <div class="p-2 sm:p-3 flex-1 flex flex-col justify-between">
                            <div>
                                <span class="text-[0.52rem] sm:text-[0.62rem] text-cs-orange font-bold uppercase tracking-wider block mb-1">
                                     {{ $service->category }}
                                </span>
                                <h3 class="text-[0.7rem] sm:text-xs font-bold text-[#2C1810] mb-0.5 line-clamp-1" title="{{ $service->title }}">{{ $service->title }}</h3>
                                <p class="hidden sm:block text-[0.68rem] text-[#7A6248] line-clamp-2 mb-2 leading-tight">{{ $service->description }}</p>
                            </div>

                            <div class="pt-1.5 sm:pt-2 border-t border-[#E8DDD2] flex justify-between items-center">
                                <div class="flex items-center gap-0.5">
                                    <span class="text-[0.55rem] sm:text-[0.62rem] text-amber-500">⭐</span>
                                    <span class="text-[0.55rem] sm:text-[0.62rem] font-bold text-[#2C1810]">{{ number_format($service->averageRating(), 1) }}</span>
                                </div>
                               <div class="flex items-center gap-1 shrink-0">
    <a href="{{ route('cooker.services.edit', $service) }}"
       class="inline-flex items-center justify-center h-6 px-2 text-[0.6rem] font-semibold rounded-lg transition-all bg-blue-500/10 text-blue-600 hover:bg-blue-500/20 no-underline">
        Edit
    </a>

    <form action="{{ route('cooker.services.destroy', $service) }}" method="POST"
          onsubmit="return confirm('Delete this menu item?')" class="inline-flex m-0">
        @csrf @method('DELETE')
        <button type="submit"
                class="inline-flex items-center justify-center h-6 px-2 text-[0.6rem] font-semibold rounded-lg transition-all border-none bg-red-500/10 text-red-600 hover:bg-red-500/20 cursor-pointer">
            🗑️
        </button>
    </form>
</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center p-12 text-[#B0906A] text-sm">
                <div class="text-4xl mb-2"></div>
                <div>No menu items registered yet. <a href="{{ route('cooker.services.create') }}" class="text-[#C67C4E] hover:underline font-semibold no-underline font-bold">Create your first menu item now!</a></div>
            </div>
        @endif
    </div>

    {{-- Resep Saya (Recipes Marketplace) --}}
    <div class="panel-card mb-6 overflow-hidden animate-fadeInUp [animation-delay:0.25s] opacity-0 bg-white border border-[#E8DDD2] rounded-2xl">
        <div class="panel-card-header px-6 py-4 border-b border-[#E8DDD2] flex justify-between items-center">
            <h2 class="text-base font-semibold panel-section-title m-0"> My Secret Recipes (Marketplace)</h2>
            <span class="text-xs text-[#B0906A]">{{ $totalRecipes }} Recipes</span>
        </div>
        
        @if($recipes->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4 p-4">
                @foreach($recipes as $recipe)
                    <div class="bg-white border border-[#E8DDD2] rounded-xl sm:rounded-2xl overflow-hidden transition-all hover:border-[#C67C4E]/30 hover:shadow-md flex flex-col relative group">
                        <!-- Halal/Non-halal badge -->
                        <span class="absolute top-1.5 left-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-sm border
                            @if($recipe->is_halal)
                                bg-green-500/90 text-white border-green-400/20
                            @else
                                bg-red-500/90 text-white border-red-400/20
                            @endif">
                            {{ $recipe->is_halal ? 'Halal 🟢' : 'Non-Halal 🔴' }}
                        </span>

                        <!-- Publish Badge -->
                        <span class="absolute top-1.5 right-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-sm border
                            @if($recipe->is_published)
                                bg-blue-500/90 text-white border-blue-400/20
                            @else
                                bg-slate-500/90 text-white border-slate-400/20
                            @endif">
                            {{ $recipe->is_published ? 'Published' : 'Draft' }}
                        </span>

                        <div class="h-20 sm:h-28 bg-cs-bg-primary overflow-hidden relative">
                            @if($recipe->image_path)
                                <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl bg-cs-orange/5 text-cs-orange"></div>
                            @endif
                            <x-price-display :item="$recipe" :viewerCurrency="$viewerCurrency" size="sm" color="text-[#D4A574]" class="absolute bottom-1.5 right-1.5 bg-[#2C1810]/85 backdrop-blur-md px-1.5 py-0.5 rounded-full" />
                        </div>

                        <div class="p-2 sm:p-3 flex-1 flex flex-col justify-between">
                            <div>
                                <span class="text-[0.52rem] sm:text-[0.62rem] text-cs-orange font-bold uppercase tracking-wider block mb-1">
                                     {{ $recipe->category }}
                                </span>
                                <h3 class="text-[0.7rem] sm:text-xs font-bold text-[#2C1810] mb-0.5 line-clamp-1" title="{{ $recipe->title }}">{{ $recipe->title }}</h3>
                                <p class="hidden sm:block text-[0.68rem] text-[#7A6248] line-clamp-2 mb-2 leading-tight">{{ $recipe->description }}</p>
                            </div>

                            <div class="pt-1.5 sm:pt-2 border-t border-[#E8DDD2] flex justify-between items-center">
                                <div class="flex items-center gap-0.5">
                                    <span class="text-[0.55rem] sm:text-[0.62rem] text-amber-500">⭐</span>
                                    <span class="text-[0.55rem] sm:text-[0.62rem] font-bold text-[#2C1810]">{{ number_format($recipe->averageRating(), 1) }}</span>
                                </div>
                                <div class="flex gap-1 shrink-0">
                                    <a href="{{ route('cooker.recipes.edit', $recipe) }}" class="px-1.5 py-0.5 text-[0.6rem] font-semibold rounded-lg transition-all bg-blue-500/10 text-blue-600 hover:bg-blue-500/20 no-underline">Edit</a>
                                    <form action="{{ route('cooker.recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('Delete this recipe?')" class="inline m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-1.5 py-0.5 text-[0.6rem] font-semibold rounded-lg transition-all border-none bg-red-500/10 text-red-600 hover:bg-red-500/20 cursor-pointer">🗑️</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center p-12 text-[#B0906A] text-sm">
                <div class="text-4xl mb-2"></div>
                <div>No recipes registered yet. <a href="{{ route('cooker.recipes.create') }}" class="text-[#C67C4E] hover:underline font-semibold no-underline font-bold">Create your first recipe now!</a></div>
            </div>
        @endif
    </div>
</div>
@endsection