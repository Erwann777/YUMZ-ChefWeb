@extends('admin.layouts.admin')

@section('title', 'Content Management — Yumz Admin')
@section('page-title', 'Content')
@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4 anim-in">
    <div>
        <h1 class="text-2xl font-bold text-cs-text-primary">🍽️ Content Management</h1>
        <p class="text-cs-text-secondary text-sm">Moderate recipes and cooking services across the platform</p>
    </div>
</div>

<div class="flex items-center gap-2 mb-5 anim-in anim-d1">
    <a href="{{ route('admin.content', ['tab' => 'recipes']) }}" class="px-4 py-2 rounded-lg text-sm font-medium border transition-all {{ $tab === 'recipes' ? 'bg-admin-accent/10 border-admin-accent/30 text-admin-accent' : 'bg-white border-admin-border text-cs-text-secondary hover:bg-slate-50/50' }}">🥘 Recipes</a>
    <a href="{{ route('admin.content', ['tab' => 'services']) }}" class="px-4 py-2 rounded-lg text-sm font-medium border transition-all {{ $tab === 'services' ? 'bg-admin-accent/10 border-admin-accent/30 text-admin-accent' : 'bg-white border-admin-border text-cs-text-secondary hover:bg-slate-50/50' }}">🍽️ Services</a>
</div>

<form method="GET" action="{{ route('admin.content') }}" class="flex items-center gap-2.5 flex-wrap mb-5 anim-in anim-d1">
    <input type="hidden" name="tab" value="{{ $tab }}">
    <input
        type="text"
        name="search"
        class="flex-1 min-w-[200px] px-4 py-2.5 bg-white border border-admin-border rounded-lg text-cs-text-primary text-sm outline-none focus:border-admin-accent focus:ring-3 focus:ring-admin-accent-glow placeholder:text-cs-text-muted"
        placeholder="🔍 Search title or cooker..."
        value="{{ $search ?? '' }}"
    >
    @if($tab === 'recipes')
        <select name="filter" class="px-3 py-2.5 bg-white border border-admin-border rounded-lg text-sm outline-none cursor-pointer" onchange="this.form.submit()">
            <option value="">All Recipes</option>
            <option value="published" {{ ($contentFilter ?? '') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="draft" {{ ($contentFilter ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
        </select>
    @else
        <select name="filter" class="px-3 py-2.5 bg-white border border-admin-border rounded-lg text-sm outline-none cursor-pointer" onchange="this.form.submit()">
            <option value="">All Services</option>
            <option value="available" {{ ($contentFilter ?? '') === 'available' ? 'selected' : '' }}>Available</option>
            <option value="unavailable" {{ ($contentFilter ?? '') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
        </select>
    @endif
    <button type="submit" class="btn btn-primary btn-sm">Search</button>
    @if($search || $contentFilter)
        <a href="{{ route('admin.content', ['tab' => $tab]) }}" class="btn btn-ghost btn-sm">Reset</a>
    @endif
</form>

<div class="admin-card anim-in anim-d2">
    <div class="overflow-x-auto">
        @if($tab === 'recipes')
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Recipe</th>
                        <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border max-sm:hidden">Cooker</th>
                        <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Price</th>
                        <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border max-sm:hidden">Sales</th>
                        <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Status</th>
                        <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recipes as $recipe)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-3 text-sm border-b border-admin-border">
                                <div class="font-medium text-cs-text-primary">{{ $recipe->title }}</div>
                                <div class="text-xs text-cs-text-muted">{{ $recipe->category ?? 'Uncategorized' }} · {{ $recipe->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-admin-border max-sm:hidden">
                                <a href="{{ route('admin.users.show', $recipe->cooker) }}" class="text-cs-text-secondary hover:text-admin-accent">{{ $recipe->cooker->name }}</a>
                            </td>
                            <td class="px-5 py-3 text-sm font-medium text-cs-text-primary border-b border-admin-border">{{ $recipe->formatted_price }}</td>
                            <td class="px-5 py-3 text-sm text-cs-text-secondary border-b border-admin-border max-sm:hidden">{{ $recipe->purchases_count }}</td>
                            <td class="px-5 py-3 text-sm border-b border-admin-border">
                                <span class="text-[0.68rem] font-semibold px-2 py-0.5 rounded-full {{ $recipe->is_published ? 'bg-emerald-500/12 text-emerald-600' : 'bg-slate-500/12 text-slate-600' }}">
                                    {{ $recipe->is_published ? '✅ Published' : '📝 Draft' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-admin-border">
                                <div class="flex items-center gap-1.5">
                                    <form action="{{ route('admin.recipes.toggle-publish', $recipe) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-ghost btn-sm">{{ $recipe->is_published ? 'Unpublish' : 'Publish' }}</button>
                                    </form>
                                    <form action="{{ route('admin.recipes.delete', $recipe) }}" method="POST" class="inline" onsubmit="return confirm('Delete recipe \"{{ $recipe->title }}\"?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-cs-text-secondary"><div class="text-4xl mb-3">🥘</div>No recipes found</td></tr>
                    @endforelse
                </tbody>
            </table>
            @php $paginator = $recipes; @endphp
        @else
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Service</th>
                        <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border max-sm:hidden">Cooker</th>
                        <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Price</th>
                        <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border max-sm:hidden">Orders</th>
                        <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Status</th>
                        <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-3 text-sm border-b border-admin-border">
                                <div class="font-medium text-cs-text-primary">{{ $service->title }}</div>
                                <div class="text-xs text-cs-text-muted">{{ $service->category ?? 'Uncategorized' }} · {{ $service->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-admin-border max-sm:hidden">
                                <a href="{{ route('admin.users.show', $service->cooker) }}" class="text-cs-text-secondary hover:text-admin-accent">{{ $service->cooker->name }}</a>
                            </td>
                            <td class="px-5 py-3 text-sm font-medium text-cs-text-primary border-b border-admin-border">{{ $service->formatted_price }}</td>
                            <td class="px-5 py-3 text-sm text-cs-text-secondary border-b border-admin-border max-sm:hidden">{{ $service->orders_count }}</td>
                            <td class="px-5 py-3 text-sm border-b border-admin-border">
                                <span class="text-[0.68rem] font-semibold px-2 py-0.5 rounded-full {{ $service->is_available ? 'bg-emerald-500/12 text-emerald-600' : 'bg-slate-500/12 text-slate-600' }}">
                                    {{ $service->is_available ? '✅ Available' : '⛔ Unavailable' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-sm border-b border-admin-border">
                                <div class="flex items-center gap-1.5">
                                    <form action="{{ route('admin.services.toggle-availability', $service) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-ghost btn-sm">{{ $service->is_available ? 'Disable' : 'Enable' }}</button>
                                    </form>
                                    <form action="{{ route('admin.services.delete', $service) }}" method="POST" class="inline" onsubmit="return confirm('Delete service \"{{ $service->title }}\"?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-cs-text-secondary"><div class="text-4xl mb-3">🍽️</div>No services found</td></tr>
                    @endforelse
                </tbody>
            </table>
            @php $paginator = $services; @endphp
        @endif
    </div>

    @if(isset($paginator) && $paginator->hasPages())
        <div class="px-5 py-4 border-t border-admin-border flex items-center justify-between flex-wrap gap-2">
            <div class="text-[0.78rem] text-cs-text-secondary">
                Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
            </div>
            <div class="flex gap-1">
                @if($paginator->onFirstPage())
                    <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-transparent border border-admin-border text-cs-text-muted">←</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50">←</a>
                @endif
                @foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    @if($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-admin-accent/10 border border-admin-accent/30 text-admin-accent font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50">{{ $page }}</a>
                    @endif
                @endforeach
                @if($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50">→</a>
                @else
                    <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] bg-transparent border border-admin-border text-cs-text-muted">→</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
