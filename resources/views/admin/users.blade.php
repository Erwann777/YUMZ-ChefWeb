@extends('admin.layouts.admin')

@section('title', 'Manage Users — CookSpace Admin')
@section('page-title', 'Users')
@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4 anim-in">
    <div>
        <h1 class="text-2xl font-bold text-cs-text-primary">👥 User Management</h1>
        <p class="text-cs-text-secondary text-sm">Manage all CookSpace users</p>
    </div>
</div>

<form method="GET" action="{{ route('admin.users') }}" class="flex items-center gap-2.5 flex-wrap mb-5 anim-in anim-d1">
    <input
        type="text"
        name="search"
        class="flex-1 min-w-[200px] px-4 py-2.5 bg-white border border-admin-border rounded-lg text-cs-text-primary text-sm font-sans outline-none transition-all focus:border-admin-accent focus:ring-3 focus:ring-admin-accent-glow placeholder:text-cs-text-muted"
        placeholder="🔍 Search name or email..."
        value="{{ $search ?? '' }}"
        id="search-input"
    >
    <select name="role" class="px-3 py-2.5 bg-white border border-admin-border rounded-lg text-cs-text-primary text-sm font-sans outline-none cursor-pointer transition-all focus:border-admin-accent" id="filter-role" onchange="this.form.submit()">
        <option value="">All Roles</option>
        <option value="customer" {{ ($roleFilter ?? '') === 'customer' ? 'selected' : '' }}>Customer</option>
        <option value="cooker" {{ ($roleFilter ?? '') === 'cooker' ? 'selected' : '' }}>Cooker</option>
        <option value="admin" {{ ($roleFilter ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
    </select>
    <select name="status" class="px-3 py-2.5 bg-white border border-admin-border rounded-lg text-cs-text-primary text-sm font-sans outline-none cursor-pointer transition-all focus:border-admin-accent" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="active" {{ ($statusFilter ?? '') === 'active' ? 'selected' : '' }}>Active</option>
        <option value="suspended" {{ ($statusFilter ?? '') === 'suspended' ? 'selected' : '' }}>Suspended</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Search</button>
    @if($search || $roleFilter || $statusFilter)
        <a href="{{ route('admin.users') }}" class="btn btn-ghost btn-sm">Reset</a>
    @endif
</form>

<div class="admin-card anim-in anim-d2">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">User</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Role</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border max-sm:hidden">Email</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border max-sm:hidden">Joined</th>
                    <th class="text-left px-5 py-3 text-[0.7rem] text-cs-text-secondary uppercase tracking-wider font-semibold bg-admin-bg border-b border-admin-border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3 text-sm text-cs-text-secondary border-b border-admin-border last:border-b-0">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold text-white flex-shrink-0
                                    {{ $u->role === 'customer' ? 'bg-gradient-to-br from-blue-400 to-blue-600' : '' }}
                                    {{ $u->role === 'cooker' ? 'bg-gradient-to-br from-orange-400 to-orange-600' : '' }}
                                    {{ $u->role === 'admin' ? 'bg-gradient-to-br from-admin-accent to-[#00b159]' : '' }}">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-cs-text-primary">{{ $u->name }}</div>
                                    <div class="text-xs text-cs-text-secondary">{{ $u->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-sm text-cs-text-secondary border-b border-admin-border last:border-b-0">
                            <span class="role-badge role-badge-{{ $u->role }}">{{ $u->role }}</span>
                            @if($u->is_suspended)
                                <span class="ml-1 text-[0.62rem] font-semibold px-1.5 py-0.5 rounded-full bg-red-500/12 text-red-600">Suspended</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-sm text-cs-text-secondary border-b border-admin-border last:border-b-0 max-sm:hidden">{{ $u->email }}</td>
                        <td class="px-5 py-3 text-sm text-cs-text-secondary border-b border-admin-border last:border-b-0 max-sm:hidden">{{ $u->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3 text-sm text-cs-text-secondary border-b border-admin-border last:border-b-0">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.users.show', $u) }}" class="btn btn-ghost btn-sm">👁️ View</a>
                                <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-ghost btn-sm" id="edit-user-{{ $u->id }}">✏️ Edit</a>
                                @if($u->id !== $user->id)
                                    <form action="{{ route('admin.users.delete', $u) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete {{ $u->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" id="delete-user-{{ $u->id }}">🗑️</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-3 border-b border-admin-border last:border-b-0">
                            <div class="text-center py-12 px-8 text-cs-text-secondary">
                                <div class="text-4xl mb-3">🔍</div>
                                <div>No users found</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="px-5 py-4 border-t border-admin-border flex items-center justify-between flex-wrap gap-2">
            <div class="text-[0.78rem] text-cs-text-secondary">
                Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }} users
            </div>
            <div class="flex gap-1">
                {{-- Previous --}}
                @if($users->onFirstPage())
                    <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] transition-all bg-transparent border border-admin-border text-cs-text-muted cursor-default">←</span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] transition-all bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300">←</a>
                @endif

                {{-- Pages --}}
                @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    @if($page == $users->currentPage())
                        <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] transition-all bg-admin-accent/10 border border-admin-accent/30 text-admin-accent font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] transition-all bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] transition-all bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300">→</a>
                @else
                    <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] transition-all bg-transparent border border-admin-border text-cs-text-muted cursor-default">→</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

