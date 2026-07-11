@extends('layouts.app')

@section('title', 'Cooker Dashboard — Yumz')
@section('body-class', 'cs-bg')
@section('main-class', 'w-full px-4 sm:px-6 xl:px-8 py-6')

@section('styles')
<style>
    /* Synced Cooker Panel Styles matching Customer Dashboard */
    .dash-stat { background:#ffffff; border:1px solid #EDE5DA; border-radius:16px; padding:1.25rem; box-shadow:0 2px 12px rgba(44,24,16,0.06); transition:all 0.25s ease; position:relative; overflow:hidden; }
    .dash-stat::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; border-radius:16px 16px 0 0; }
    .dash-stat:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(44,24,16,0.10); border-color:rgba(198,124,78,0.35); }
    .dash-stat.accent-orange::before { background:linear-gradient(90deg,#C67C4E,#e8a87c); }
    .dash-stat.accent-green::before  { background:linear-gradient(90deg,#22c55e,#4ade80); }
    .dash-stat.accent-blue::before   { background:linear-gradient(90deg,#3b82f6,#60a5fa); }
    .dash-stat.accent-purple::before { background:linear-gradient(90deg,#8b5cf6,#a78bfa); }
    .dash-stat-label { font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#9A7B5A; margin-bottom:0.4rem; }
    .dash-stat-value { font-size:1.7rem; font-weight:800; color:#2C1810; line-height:1; }
    .dash-stat-sub   { font-size:0.7rem; color:#B0906A; font-weight:500; margin-top:0.2rem; }
    .dash-stat-icon  { font-size:2.2rem; position:absolute; top:1rem; right:1.1rem; opacity:0.15; }

    .dash-section-title { font-size:1rem; font-weight:700; color:#2C1810; display:flex; align-items:center; gap:0.5rem; }
    .dash-section-title::before { content:''; display:inline-block; width:4px; height:1.15em; background:linear-gradient(180deg,#C67C4E,#e8a87c); border-radius:4px; }
    
    .dash-card { background:#ffffff; border:1px solid #EDE5DA; border-radius:18px; box-shadow:0 2px 12px rgba(44,24,16,0.05); overflow:hidden; }
    .dash-card-header { padding:0.9rem 1.2rem; border-bottom:1px solid #F0E80; display:flex; align-items:center; justify-content:space-between; background:linear-gradient(135deg,#FFFAF7,#FBF6F0); }
    
    .marketplace-card { background:#ffffff; border:1px solid #EDE5DA; border-radius:14px; overflow:hidden; transition:all 0.22s ease; }
    .marketplace-card:hover { border-color:rgba(198,124,78,0.4); transform:translateY(-3px); box-shadow:0 8px 20px rgba(44,24,16,0.09); }
    
    .status-badge { font-size:0.62rem; font-weight:700; padding:0.18rem 0.6rem; border-radius:99px; text-transform:uppercase; letter-spacing:0.05em; }
    .badge-pending  { background:rgba(251,146,60,0.12); color:#c2410c; border:1px solid rgba(251,146,60,0.2); }
    .badge-done     { background:rgba(34,197,94,0.10);  color:#15803d; border:1px solid rgba(34,197,94,0.2); }
    .badge-canceled { background:rgba(239,68,68,0.10);  color:#b91c1c; border:1px solid rgba(239,68,68,0.2); }
    .badge-process  { background:rgba(59,130,246,0.10); color:#1d4ed8; border:1px solid rgba(59,130,246,0.2); }
</style>
@endsection

@section('content')
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm mb-6 animate-fadeInUp mt-20"> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm mb-6 animate-fadeInUp mt-20"> {{ session('error') }}</div>
@endif

<div class="mt-20 mb-12">
    {{-- Banner Header --}}
    <div class="mb-8 animate-fadeInUp">
        <div class="bg-gradient-to-br from-[#2C1810] to-[#3D2B1F] border border-[#E8DDD2] rounded-3xl p-6 sm:p-10 shadow-xl relative overflow-hidden">
            <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-[#C67C4E]/10 blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full bg-[#D4A574]/5 blur-3xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="text-[#D4A574] text-xs sm:text-sm font-bold uppercase tracking-widest mb-1.5 flex items-center gap-2">
                        <span>Cooker Panel Dashboard</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-['Playfair_Display'] font-bold text-white mb-2 leading-tight">
                        Hello, Chef <span class="text-[#C67C4E]">{{ $user->name }}</span>!
                    </h1>
                    <p class="text-white/70 text-sm leading-relaxed">
                        Manage your menu items, monitor incoming customer orders, and grow your culinary brand.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3 shrink-0">
                    <a href="{{ route('cooker.services.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-[#C67C4E] hover:bg-[#B06A3E] text-white text-xs font-bold rounded-xl border-none cursor-pointer transition-all shadow-sm hover:shadow-md no-underline">
                        ➕ New Menu Item
                    </a>
                    <a href="{{ route('cooker.recipes.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gradient-to-br from-[#2C1810] to-[#3D2B1F] hover:from-[#3D2B1F] hover:to-[#4A382A] text-white text-xs font-bold rounded-xl border border-[#E8DDD2]/30 cursor-pointer transition-all shadow-sm hover:shadow-md no-underline">
                        ➕ Upload Recipe
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        {{-- Stat Card 1 --}}
        <div class="dash-stat accent-orange animate-fadeInUp [animation-delay:0.05s]">
            <div class="dash-stat-label">Total Menu Items</div>
            <div class="dash-stat-value">{{ $totalServices }}</div>
            <div class="dash-stat-sub">Active Services</div>
            <div class="dash-stat-icon"></div>
        </div>
        
        {{-- Stat Card 2 --}}
        <div class="dash-stat accent-blue animate-fadeInUp [animation-delay:0.08s]">
            <div class="dash-stat-label">Secret Recipes</div>
            <div class="dash-stat-value">{{ $totalRecipes }}</div>
            <div class="dash-stat-sub">Digital PDF Recipes</div>
            <div class="dash-stat-icon"></div>
        </div>

        {{-- Stat Card 3 --}}
        <div class="dash-stat accent-green animate-fadeInUp [animation-delay:0.1s]">
            <div class="dash-stat-label">Total Orders</div>
            <div class="dash-stat-value">{{ $orders->count() }}</div>
            <div class="dash-stat-sub">Incoming requests</div>
            <div class="dash-stat-icon"></div>
        </div>

        {{-- Stat Card 4 --}}
        <div class="dash-stat accent-purple animate-fadeInUp [animation-delay:0.13s]">
            <div class="dash-stat-label">Pending Orders</div>
            <div class="dash-stat-value">{{ $pendingOrders }}</div>
            <div class="dash-stat-sub">Needs confirmation</div>
            <div class="dash-stat-icon"></div>
        </div>

        {{-- Stat Card 5 --}}
        <div class="dash-stat accent-orange animate-fadeInUp [animation-delay:0.16s]">
            <div class="dash-stat-label">Total Earnings</div>
            <div class="dash-stat-value text-xl sm:text-2xl truncate" title="{{ $user->formatPrice((float)$totalEarnings) }}">
                {{ $user->formatPrice((float)$totalEarnings) }}
            </div>
            <div class="dash-stat-sub">Virtual balance</div>
            <div class="dash-stat-icon"></div>
        </div>
    </div>

    {{-- Incoming Orders (Cooking Services) --}}
    <div class="dash-card mb-8 overflow-hidden animate-fadeInUp [animation-delay:0.2s]">
        <div class="dash-card-header px-6 py-4 border-b border-[#E8DDD2] flex justify-between items-center bg-gradient-to-r from-[#FFFAF7] to-[#FBF6F0]">
            <h2 class="dash-section-title m-0">Incoming Food Orders</h2>
            <span class="text-xs text-[#9A7B5A] font-medium bg-white/60 border border-[#E8DDD2]/60 px-2 py-0.5 rounded-full">{{ $orders->count() }} orders</span>
        </div>
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="bg-[#F5EFE6]">
                            <th class="p-4 text-xs font-bold text-[#7A6248] uppercase tracking-wider">ID</th>
                            <th class="p-4 text-xs font-bold text-[#7A6248] uppercase tracking-wider">Customer</th>
                            <th class="p-4 text-xs font-bold text-[#7A6248] uppercase tracking-wider">Menu Item</th>
                            <th class="p-4 text-xs font-bold text-[#7A6248] uppercase tracking-wider">Total Price</th>
                            <th class="p-4 text-xs font-bold text-[#7A6248] uppercase tracking-wider">Date</th>
                            <th class="p-4 text-xs font-bold text-[#7A6248] uppercase tracking-wider">Status</th>
                            <th class="p-4 text-xs font-bold text-[#7A6248] uppercase tracking-wider text-right pr-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr class="border-b border-[#E8DDD2] hover:bg-[#FFFAF6] last:border-0 transition-colors">
                                <td class="p-4 font-mono text-xs text-[#7A6248]">#{{ $order->id }}</td>
                                <td class="p-4">
                                    <div class="font-bold text-[#2C1810]">{{ $order->customer->name }}</div>
                                    <div class="text-xs text-[#9A7B5A]">{{ $order->customer->email }}</div>
                                    @if($order->notes)
                                        <div class="text-[0.75rem] text-[#8B5A2B] bg-[#FFFBF0] border-l-2 border-[#C67C4E] p-2 mt-1 rounded-r-lg shadow-sm max-w-md font-medium">
                                             "{{ $order->notes }}"
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="font-semibold text-[#2C1810]">{{ $order->service->title }}</div>
                                </td>
                                <td class="p-4 font-bold text-[#C67C4E]">{{ $order->formatted_price }}</td>
                                <td class="p-4 text-[#7A6248] text-xs">
                                    {{ $order->created_at->format('d M Y') }}
                                    <div class="text-[0.68rem] text-gray-400 mt-0.5">{{ $order->created_at->format('H:i') }}</div>
                                </td>
                                <td class="p-4">
                                    <span class="status-badge 
                                        {{ $order->status === 'pending'   ? 'badge-pending' : '' }}
                                        {{ $order->status === 'confirmed' ? 'badge-process' : '' }}
                                        {{ $order->status === 'completed' ? 'badge-done' : '' }}
                                        {{ $order->status === 'cancelled' ? 'badge-canceled' : '' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="p-4 text-right pr-6">
                                    <div class="flex gap-2 justify-end items-center flex-wrap">
                                        @if($order->status === 'pending')
                                            <form action="{{ route('cooker.orders.update-status', $order) }}" method="POST" class="inline m-0">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="px-3 py-1.5 text-xs font-bold rounded-lg cursor-pointer transition-all border-none bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white shadow-sm hover:shadow-md" onclick="return confirm('Accept this order to start cooking?')"> Accept &amp; Cook</button>
                                            </form>
                                            <form action="{{ route('cooker.orders.update-status', $order) }}" method="POST" class="inline m-0">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="px-3 py-1.5 text-xs font-bold rounded-lg cursor-pointer transition-all border border-rose-200 bg-rose-50/50 text-rose-600 hover:bg-rose-100/50" onclick="return confirm('Reject/Cancel this order?')"> Reject</button>
                                            </form>
                                        @elseif($order->status === 'confirmed')
                                            <form action="{{ route('cooker.orders.update-status', $order) }}" method="POST" class="inline m-0">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="px-3.5 py-2 text-xs font-bold rounded-lg cursor-pointer transition-all border-none bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white shadow-md hover:shadow-lg pulse-glow" onclick="return confirm('Mark this food ready to be served to the customer?')"> Ready to Serve! </button>
                                            </form>
                                            <form action="{{ route('cooker.orders.update-status', $order) }}" method="POST" class="inline m-0">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="px-3 py-1.5 text-xs font-bold rounded-lg cursor-pointer transition-all border border-rose-200 bg-rose-50/50 text-rose-500 hover:bg-rose-100/50" onclick="return confirm('Cancel this order? Customer will be fully refunded.')"> Cancel</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-[#B0906A] font-semibold bg-[#F5EFE6] px-2.5 py-1 rounded-lg">Order Complete</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-16 px-6 text-[#B0906A]">
                <div class="text-5xl mb-4"></div>
                <p class="text-sm font-semibold m-0">No incoming orders at the moment.</p>
                <p class="text-xs text-gray-400 mt-1">When customers order your cooking services, they will appear here.</p>
            </div>
        @endif
    </div>

    {{-- Menu Makanan Saya (Cooking Services) --}}
    <div class="dash-card mb-8 overflow-hidden animate-fadeInUp [animation-delay:0.25s]">
        <div class="dash-card-header px-6 py-4 border-b border-[#E8DDD2] flex justify-between items-center bg-gradient-to-r from-[#FFFAF7] to-[#FBF6F0]">
            <h2 class="dash-section-title m-0">My Menu Items (Services)</h2>
            <span class="text-xs text-[#9A7B5A] font-medium bg-white/60 border border-[#E8DDD2]/60 px-2 py-0.5 rounded-full">{{ $totalServices }} Menu Items</span>
        </div>
        
        @if($services->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 p-4">
                @foreach($services as $service)
                    <div class="marketplace-card flex flex-col relative bg-white">
                        <!-- Halal/Non-halal badge -->
                        <span class="absolute top-1.5 left-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-md shadow-sm border
                            @if($service->is_halal)
                                bg-green-500/90 text-white border-green-400/20
                            @else
                                bg-red-500/90 text-white border-red-400/20
                            @endif">
                            {{ $service->is_halal ? 'Halal ' : 'Non-Halal ' }}
                        </span>

                        <!-- Availability Badge -->
                        <span class="absolute top-1.5 right-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-md shadow-sm border
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
                                <div class="w-full h-full flex items-center justify-center text-2xl sm:text-3xl bg-cs-orange/5 text-cs-orange"></div>
                            @endif
                            <x-price-display :item="$service" :viewerCurrency="$viewerCurrency" size="sm" color="text-[#D4A574]" class="absolute bottom-1.5 right-1.5 bg-[#2C1810]/85 backdrop-blur-md px-1.5 py-0.5 rounded-full" />
                        </div>

                        <div class="p-2 sm:p-3 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-1 mb-1 justify-between">
                                    <span class="text-[0.52rem] sm:text-[0.58rem] text-[#7A6248] bg-[#F5EFE6] border border-slate-100 px-1 py-0.5 rounded capitalize truncate">{{ $service->category }}</span>
                                </div>
                                <h3 class="text-[0.7rem] sm:text-xs font-bold text-[#2C1810] mb-0.5 line-clamp-1" title="{{ $service->title }}">{{ $service->title }}</h3>
                                <p class="hidden sm:block text-[0.68rem] text-[#7A6248] line-clamp-2 mb-2 leading-tight">{{ $service->description }}</p>
                            </div>

                            <div class="pt-1.5 sm:pt-2 border-t border-[#E8DDD2] flex justify-between items-center mt-1">
                                <div class="flex items-center gap-0.5">
                                    <span class="text-[0.55rem] sm:text-[0.62rem] text-amber-500">⭐</span>
                                    <span class="text-[0.55rem] sm:text-[0.62rem] font-bold text-[#2C1810]">{{ number_format($service->averageRating(), 1) }}</span>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    <a href="{{ route('cooker.services.edit', $service) }}" class="text-[0.62rem] sm:text-[0.68rem] text-blue-600 font-bold no-underline hover:underline">Edit</a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('cooker.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Delete this menu item?')" class="inline m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-[0.62rem] sm:text-[0.68rem] text-red-600 font-bold no-underline hover:underline border-none bg-transparent cursor-pointer p-0">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 px-6 text-[#B0906A]">
                <div class="text-5xl mb-4"></div>
                <p class="text-sm font-semibold m-0">No menu items registered yet.</p>
                <a href="{{ route('cooker.services.create') }}" class="text-[#C67C4E] hover:underline font-bold text-xs block mt-2">Create your first menu item now!</a>
            </div>
        @endif
    </div>

    {{-- Resep Saya (Recipes Marketplace) --}}
    <div class="dash-card mb-8 overflow-hidden animate-fadeInUp [animation-delay:0.3s]">
        <div class="dash-card-header px-6 py-4 border-b border-[#E8DDD2] flex justify-between items-center bg-gradient-to-r from-[#FFFAF7] to-[#FBF6F0]">
            <h2 class="dash-section-title m-0">My Secret Recipes (Marketplace)</h2>
            <span class="text-xs text-[#9A7B5A] font-medium bg-white/60 border border-[#E8DDD2]/60 px-2 py-0.5 rounded-full">{{ $totalRecipes }} Recipes</span>
        </div>
        
        @if($recipes->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 p-4">
                @foreach($recipes as $recipe)
                    <div class="marketplace-card flex flex-col relative bg-white">
                        <!-- Halal/Non-halal badge -->
                        <span class="absolute top-1.5 left-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-md shadow-sm border
                            @if($recipe->is_halal)
                                bg-green-500/90 text-white border-green-400/20
                            @else
                                bg-red-500/90 text-white border-red-400/20
                            @endif">
                            {{ $recipe->is_halal ? 'Halal ' : 'Non-Halal ' }}
                        </span>

                        <!-- Publish Badge -->
                        <span class="absolute top-1.5 right-1.5 z-10 text-[0.5rem] sm:text-[0.58rem] font-bold px-1.5 py-0.5 rounded-full backdrop-blur-md shadow-sm border
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
                                <div class="w-full h-full flex items-center justify-center text-2xl sm:text-3xl bg-cs-orange/5 text-cs-orange"></div>
                            @endif
                            <x-price-display :item="$recipe" :viewerCurrency="$viewerCurrency" size="sm" color="text-[#D4A574]" class="absolute bottom-1.5 right-1.5 bg-[#2C1810]/85 backdrop-blur-md px-1.5 py-0.5 rounded-full" />
                        </div>

                        <div class="p-2 sm:p-3 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-1 mb-1 justify-between">
                                    <span class="text-[0.52rem] sm:text-[0.58rem] text-[#7A6248] bg-[#F5EFE6] border border-slate-100 px-1 py-0.5 rounded capitalize truncate">{{ $recipe->category }}</span>
                                </div>
                                <h3 class="text-[0.7rem] sm:text-xs font-bold text-[#2C1810] mb-0.5 line-clamp-1" title="{{ $recipe->title }}">{{ $recipe->title }}</h3>
                                <p class="hidden sm:block text-[0.68rem] text-[#7A6248] line-clamp-2 mb-2 leading-tight">{{ $recipe->description }}</p>
                            </div>

                            <div class="pt-1.5 sm:pt-2 border-t border-[#E8DDD2] flex justify-between items-center mt-1">
                                <div class="flex items-center gap-0.5">
                                    <span class="text-[0.55rem] sm:text-[0.62rem] text-amber-500">⭐</span>
                                    <span class="text-[0.55rem] sm:text-[0.62rem] font-bold text-[#2C1810]">{{ number_format($recipe->averageRating(), 1) }}</span>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    <a href="{{ route('cooker.recipes.edit', $recipe) }}" class="text-[0.62rem] sm:text-[0.68rem] text-blue-600 font-bold no-underline hover:underline">Edit</a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('cooker.recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('Delete this recipe?')" class="inline m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-[0.62rem] sm:text-[0.68rem] text-red-600 font-bold no-underline hover:underline border-none bg-transparent cursor-pointer p-0">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 px-6 text-[#B0906A]">
                <div class="text-5xl mb-4"></div>
                <p class="text-sm font-semibold m-0">No recipes registered yet.</p>
                <a href="{{ route('cooker.recipes.create') }}" class="text-[#C67C4E] hover:underline font-bold text-xs block mt-2">Create your first recipe now!</a>
            </div>
        @endif
    </div>
</div>
@endsection