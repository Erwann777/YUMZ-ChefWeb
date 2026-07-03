<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin — CookSpace')</title>
    <meta name="description" content="CookSpace Admin Panel">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/compiled.css', 'resources/js/app.js'])

    @yield('styles')
</head>
<body class="bg-admin-bg text-cs-text-primary min-h-screen font-sans antialiased flex">
    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay hidden fixed inset-0 bg-black/50 z-35" id="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar w-[260px] min-h-screen bg-admin-sidebar border-r border-admin-border flex flex-col fixed top-0 left-0 z-40 transition-transform duration-300 max-md:-translate-x-full" id="admin-sidebar">
       <div class="p-5 border-b border-admin-border flex-col items-center gap-2.5">
    <div class="w-20 h-16 flex items-center justify-center mb-4">
        <img src="{{ asset('images/landing/logo_web1.png') }}" alt="CookSpace Logo" class="w-full h-auto">
    </div>
    <div class="font-bold text-lg text-admin-border"><span class="text-[0.6rem] font-semibold uppercase tracking-wider bg-red-500/15 text-red-400 px-1.5 py-0.5 rounded">Admin</span></div>
</div>

        <nav class="flex-1 p-3 flex flex-col gap-1">
            <div class="text-[0.65rem] font-semibold uppercase tracking-wider text-cs-text-muted px-3 pt-3 pb-1.5">Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-admin-accent-glow text-admin-accent font-semibold' : 'text-cs-text-secondary hover:bg-admin-bg hover:text-cs-text-primary' }}" id="nav-overview">
                <span class="w-5 text-center text-base">📊</span>
                <span>Overview</span>
            </a>
            <a href="{{ route('admin.users') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.users*') ? 'bg-admin-accent-glow text-admin-accent font-semibold' : 'text-cs-text-secondary hover:bg-admin-bg hover:text-cs-text-primary' }}" id="nav-users">
                <span class="w-5 text-center text-base">👥</span>
                <span>Users</span>
            </a>
            <a href="{{ route('admin.orders') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.orders*') ? 'bg-admin-accent-glow text-admin-accent font-semibold' : 'text-cs-text-secondary hover:bg-admin-bg hover:text-cs-text-primary' }}" id="nav-orders">
                <span class="w-5 text-center text-base">📦</span>
                <span>Orders</span>
            </a>
            <a href="{{ route('admin.content') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.content') || request()->routeIs('admin.recipes*') || request()->routeIs('admin.services*') ? 'bg-admin-accent-glow text-admin-accent font-semibold' : 'text-cs-text-secondary hover:bg-admin-bg hover:text-cs-text-primary' }}" id="nav-content">
                <span class="w-5 text-center text-base">🍽️</span>
                <span>Content</span>
            </a>
            <a href="{{ route('admin.transactions') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.transactions') ? 'bg-admin-accent-glow text-admin-accent font-semibold' : 'text-cs-text-secondary hover:bg-admin-bg hover:text-cs-text-primary' }}" id="nav-transactions">
                <span class="w-5 text-center text-base">💰</span>
                <span>Transactions</span>
            </a>
            <a href="{{ route('admin.activity-log') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.activity-log') ? 'bg-admin-accent-glow text-admin-accent font-semibold' : 'text-cs-text-secondary hover:bg-admin-bg hover:text-cs-text-primary' }}" id="nav-activity">
                <span class="w-5 text-center text-base">📋</span>
                <span>Activity Log</span>
            </a>

            <div class="text-[0.65rem] font-semibold uppercase tracking-wider text-cs-text-muted px-3 pt-4 pb-1.5">Quick Links</div>
            <a href="/" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-all text-cs-text-secondary hover:bg-admin-bg hover:text-cs-text-primary" id="nav-site">
                <span class="w-5 text-center text-base">🌐</span>
                <span>View Site</span>
            </a>
        </nav>

        <div class="p-4 border-t border-admin-border">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center text-xs font-semibold text-white flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[0.82rem] font-medium text-cs-text-primary truncate">{{ Auth::user()->name }}</div>
                    <div class="text-[0.65rem] text-cs-text-muted uppercase tracking-wider">Administrator</div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center w-[30px] h-[30px] rounded-lg bg-transparent border border-admin-border text-cs-text-secondary cursor-pointer text-sm transition-all hover:bg-red-500/10 hover:border-red-500/30 hover:text-red-400" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
  <path strokeLinecap="round" strokeLinejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
</svg>

                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 min-h-screen ml-[260px] max-md:ml-0">
        <div class="h-[60px] border-b border-admin-border flex items-center justify-between px-8 bg-white sticky top-0 z-30 shadow-[0_1px_6px_0_rgba(49,53,59,0.06)] max-sm:px-4">
            <div class="flex items-center gap-3">
                <button class="mobile-toggle hidden max-md:block bg-admin-card border border-admin-border text-cs-text-primary p-2 rounded-lg cursor-pointer text-lg" onclick="toggleSidebar()">☰</button>
                <h2 class="text-base font-semibold">@yield('page-title', 'Dashboard')</h2>
            </div>
            <div class="flex items-center gap-2">
                @yield('topbar-actions')
            </div>
        </div>

        <div class="p-8 max-w-[1200px] max-md:p-5">
            @if (session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 px-4 py-3 rounded-xl text-[0.85rem] mb-6 flex items-center gap-2">✅ {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-500/10 border border-red-500/20 text-red-600 px-4 py-3 rounded-xl text-[0.85rem] mb-6 flex items-center gap-2">❌ {{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('admin-sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('open');
        }
    </script>

    @yield('scripts')
</body>
</html>

