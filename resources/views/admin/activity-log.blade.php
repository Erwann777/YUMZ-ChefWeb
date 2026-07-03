@extends('admin.layouts.admin')

@section('title', 'Activity Log — CookSpace Admin')
@section('page-title', 'Activity Log')
@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-4 anim-in">
    <div>
        <h1 class="text-2xl font-bold text-cs-text-primary mb-1">📋 Activity Log</h1>
        <p class="text-cs-text-secondary text-sm">All activities that happen on CookSpace</p>
    </div>
</div>

<div class="flex items-center gap-2 flex-wrap mb-5 anim-in anim-d1">
    <a href="{{ route('admin.activity-log') }}" class="px-3.5 py-1.5 rounded-full text-[0.78rem] font-medium border text-cs-text-secondary bg-white hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300 transition-all duration-200 {{ !$actionFilter ? 'bg-admin-accent/10 border-admin-accent/30 text-admin-accent' : 'border-admin-border' }}">All</a>
    <a href="{{ route('admin.activity-log', ['action' => 'login']) }}" class="px-3.5 py-1.5 rounded-full text-[0.78rem] font-medium border text-cs-text-secondary bg-white hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300 transition-all duration-200 {{ $actionFilter === 'login' ? 'bg-admin-accent/10 border-admin-accent/30 text-admin-accent' : 'border-admin-border' }}">🔑 Login</a>
    <a href="{{ route('admin.activity-log', ['action' => 'logout']) }}" class="px-3.5 py-1.5 rounded-full text-[0.78rem] font-medium border text-cs-text-secondary bg-white hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300 transition-all duration-200 {{ $actionFilter === 'logout' ? 'bg-admin-accent/10 border-admin-accent/30 text-admin-accent' : 'border-admin-border' }}">🚪 Logout</a>
    <a href="{{ route('admin.activity-log', ['action' => 'register']) }}" class="px-3.5 py-1.5 rounded-full text-[0.78rem] font-medium border text-cs-text-secondary bg-white hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300 transition-all duration-200 {{ $actionFilter === 'register' ? 'bg-admin-accent/10 border-admin-accent/30 text-admin-accent' : 'border-admin-border' }}">📝 Register</a>
    <a href="{{ route('admin.activity-log', ['action' => 'role_changed']) }}" class="px-3.5 py-1.5 rounded-full text-[0.78rem] font-medium border text-cs-text-secondary bg-white hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300 transition-all duration-200 {{ $actionFilter === 'role_changed' ? 'bg-admin-accent/10 border-admin-accent/30 text-admin-accent' : 'border-admin-border' }}">🔄 Role Changed</a>
    <a href="{{ route('admin.activity-log', ['action' => 'user_updated']) }}" class="px-3.5 py-1.5 rounded-full text-[0.78rem] font-medium border text-cs-text-secondary bg-white hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300 transition-all duration-200 {{ $actionFilter === 'user_updated' ? 'bg-admin-accent/10 border-admin-accent/30 text-admin-accent' : 'border-admin-border' }}">✏️ Updated</a>
    <a href="{{ route('admin.activity-log', ['action' => 'user_deleted']) }}" class="px-3.5 py-1.5 rounded-full text-[0.78rem] font-medium border text-cs-text-secondary bg-white hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300 transition-all duration-200 {{ $actionFilter === 'user_deleted' ? 'bg-admin-accent/10 border-admin-accent/30 text-admin-accent' : 'border-admin-border' }}">🗑️ Deleted</a>
    <a href="{{ route('admin.activity-log', ['action' => 'recipe_created']) }}" class="px-3.5 py-1.5 rounded-full text-[0.78rem] font-medium border text-cs-text-secondary bg-white hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300 transition-all duration-200 {{ $actionFilter === 'recipe_created' ? 'bg-admin-accent/10 border-admin-accent/30 text-admin-accent' : 'border-admin-border' }}">🥘 Recipe Upload</a>
    <a href="{{ route('admin.activity-log', ['action' => 'recipe_purchased']) }}" class="px-3.5 py-1.5 rounded-full text-[0.78rem] font-medium border text-cs-text-secondary bg-white hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300 transition-all duration-200 {{ $actionFilter === 'recipe_purchased' ? 'bg-admin-accent/10 border-admin-accent/30 text-admin-accent' : 'border-admin-border' }}">💳 Recipe Purchased</a>
    <a href="{{ route('admin.activity-log', ['action' => 'service_ordered']) }}" class="px-3.5 py-1.5 rounded-full text-[0.78rem] font-medium border text-cs-text-secondary bg-white hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300 transition-all duration-200 {{ $actionFilter === 'service_ordered' ? 'bg-admin-accent/10 border-admin-accent/30 text-admin-accent' : 'border-admin-border' }}">📦 Service Ordered</a>
    <a href="{{ route('admin.activity-log', ['action' => 'user_suspended']) }}" class="px-3.5 py-1.5 rounded-full text-[0.78rem] font-medium border text-cs-text-secondary bg-white hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300 transition-all duration-200 {{ $actionFilter === 'user_suspended' ? 'bg-admin-accent/10 border-admin-accent/30 text-admin-accent' : 'border-admin-border' }}">🚫 Suspended</a>
    <a href="{{ route('admin.activity-log', ['action' => 'content_moderated']) }}" class="px-3.5 py-1.5 rounded-full text-[0.78rem] font-medium border text-cs-text-secondary bg-white hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300 transition-all duration-200 {{ $actionFilter === 'content_moderated' ? 'bg-admin-accent/10 border-admin-accent/30 text-admin-accent' : 'border-admin-border' }}">🛡️ Moderated</a>
</div>

<div class="admin-card anim-in anim-d2">
    <ul class="list-none">
        @forelse($activities as $activity)
            <li class="flex items-start gap-3.5 px-5 py-4 border-b border-admin-border hover:bg-slate-50/50 transition-colors last:border-b-0">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center text-base flex-shrink-0
                    {{ $activity->color_class === 'green' ? 'bg-emerald-500/10 text-emerald-600' : '' }}
                    {{ $activity->color_class === 'gray' ? 'bg-slate-500/10 text-slate-600' : '' }}
                    {{ $activity->color_class === 'blue' ? 'bg-blue-500/10 text-blue-600' : '' }}
                    {{ $activity->color_class === 'orange' ? 'bg-cs-orange/10 text-cs-orange' : '' }}
                    {{ $activity->color_class === 'purple' ? 'bg-purple-500/10 text-purple-600' : '' }}
                    {{ $activity->color_class === 'red' ? 'bg-red-500/10 text-red-600' : '' }}">
                    {{ $activity->icon }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm text-cs-text-secondary leading-normal mb-1">{{ $activity->description }}</div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="action-badge action-badge-{{ $activity->color_class }}">{{ str_replace('_', ' ', $activity->action) }}</span>
                        <span class="text-[0.72rem] text-cs-text-secondary">{{ $activity->created_at->format('d M Y, H:i:s') }}</span>
                        @if($activity->ip_address)
                            <span class="text-[0.68rem] text-cs-text-secondary bg-admin-bg border border-admin-border px-2 py-0.5 rounded font-mono">{{ $activity->ip_address }}</span>
                        @endif
                        @if($activity->user)
                            <span class="text-[0.72rem] text-cs-text-secondary font-medium">by: {{ $activity->user->name }}</span>
                        @endif
                    </div>
                </div>
            </li>
        @empty
            <li>
                <div class="text-center py-12 px-8 text-cs-text-secondary">
                    <div class="text-4xl mb-3">📋</div>
                    <div>No activity recorded yet</div>
                </div>
            </li>
        @endforelse
    </ul>

    @if($activities->hasPages())
        <div class="px-5 py-4 border-t border-admin-border flex items-center justify-between flex-wrap gap-2">
            <div class="text-[0.78rem] text-cs-text-secondary">
                Showing {{ $activities->firstItem() }}–{{ $activities->lastItem() }} of {{ $activities->total() }} activities
            </div>
            <div class="flex gap-1">
                @if($activities->onFirstPage())
                    <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] transition-all bg-transparent border border-admin-border text-cs-text-muted cursor-default">←</span>
                @else
                    <a href="{{ $activities->previousPageUrl() }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] transition-all bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300">←</a>
                @endif

                @foreach($activities->getUrlRange(1, $activities->lastPage()) as $page => $url)
                    @if($page == $activities->currentPage())
                        <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] transition-all bg-admin-accent/10 border border-admin-accent/30 text-admin-accent font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] transition-all bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300">{{ $page }}</a>
                    @endif
                @endforeach

                @if($activities->hasMorePages())
                    <a href="{{ $activities->nextPageUrl() }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] transition-all bg-admin-card border border-admin-border text-cs-text-secondary hover:bg-slate-50/50 hover:text-cs-text-primary hover:border-slate-300">→</a>
                @else
                    <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-md text-[0.78rem] transition-all bg-transparent border border-admin-border text-cs-text-muted cursor-default">→</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
