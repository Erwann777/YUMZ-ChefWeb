<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Yumz')</title>
    <meta name="description" content="Yumz - The best culinary platform for customers and cookers">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/compiled.css', 'resources/js/app.js'])
    @endif

    @yield('styles')

    <style>
        /* ════════════════════════════════════════════
           NAVBAR — Light Glassmorphism
        ════════════════════════════════════════════ */
        #navbar {
            background: rgba(245,239,230,0.85);
            backdrop-filter: blur(24px) saturate(160%);
            -webkit-backdrop-filter: blur(24px) saturate(160%);
            border-bottom: 1px solid rgba(198,124,78,0.12);
            box-shadow: 0 2px 32px rgba(0,0,0,0.20);
            transition: background 0.4s ease, box-shadow 0.4s ease, border-color 0.4s ease;
        }
        #navbar.nav-hero {
            background: transparent;
            backdrop-filter: none; -webkit-backdrop-filter: none;
            border-bottom: 1px solid transparent;
            box-shadow: none;
        }
        #navbar.nav-scrolled {
            background: rgba(255,253,250,0.96);
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            border-bottom: 1px solid rgba(198,124,78,0.18);
            box-shadow: 0 4px 40px rgba(0,0,0,0.25);
        }

        /* Hero: always white text over dark image */
        #navbar.nav-hero .nav-link        { color: rgba(255,255,255,0.88); }
        #navbar.nav-hero .nav-link:hover  { color: #fff; }
        #navbar.nav-hero .nav-link-accent { color: #e8a87c; }
        #navbar.nav-hero #logo-text       { color: #ffffff; }
        #navbar.nav-hero .bar             { background: #ffffff; }
        #navbar.nav-hero #user-dropdown-btn { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.20); }
        #navbar.nav-hero #user-name-text  { color: #ffffff; }
        #navbar.nav-hero #user-role-text  { color: rgba(255,255,255,0.50); }
        #navbar.nav-hero #dropdown-chevron{ color: rgba(255,255,255,0.45); }
        #navbar.nav-hero #login-btn       { color: rgba(255,255,255,0.88); border-color: rgba(255,255,255,0.22); }
        #navbar.nav-hero #login-btn:hover { color:#fff; border-color:rgba(255,255,255,0.55); background:rgba(255,255,255,0.08); }

        /* Nav links - light mode */
        .nav-link {
            position: relative;
            color: #3D2B1E;
            font-weight: 500;
            font-size: 0.92rem;
            letter-spacing: 0.02em;
            text-decoration: none;
            transition: color 0.25s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute; bottom: -3px; left: 0; width: 0; height: 1.5px;
            background: linear-gradient(90deg, #C67C4E, #e8a87c);
            border-radius: 2px;
            transition: width 0.3s cubic-bezier(0.4,0,0.2,1);
        }
        .nav-link:hover      { color: #C67C4E; }
        .nav-link:hover::after { width: 100%; }
        .nav-link-accent     { color: #C67C4E; font-weight: 600; }

        /* Mobile menu */
        #mobile-menu {
            transform: translateY(-100%);
            opacity: 0; visibility: hidden;
            transition: transform 0.4s cubic-bezier(0.4,0,0.2,1), opacity 0.3s ease, visibility 0s linear 0.4s;
        }
        #mobile-menu.menu-open {
            transform: translateY(0);
            opacity: 1; visibility: visible;
            transition: transform 0.4s cubic-bezier(0.4,0,0.2,1), opacity 0.3s ease, visibility 0s linear 0s;
        }
        #menu-overlay { opacity: 0; visibility: hidden; transition: opacity 0.4s ease, visibility 0s linear 0.4s; }
        #menu-overlay.overlay-open { opacity: 1; visibility: visible; transition: opacity 0.4s ease, visibility 0s linear 0s; }

        /* Hamburger bars - light mode */
        .bar {
            display: block; width: 22px; height: 2px;
            background: #2C1810;
            border-radius: 2px;
            transition: transform 0.35s cubic-bezier(0.4,0,0.2,1), opacity 0.2s ease, background 0.3s ease;
            transform-origin: center;
        }
        /* White bars when on hero (transparent) navbar */
        #menu-toggle.hero-bars .bar { background: #ffffff !important; }
        /* Logo default color — overridden by hero rule */
        #logo-text { color: #2C1810; }
        #menu-toggle.menu-active .bar:nth-child(1) { transform: translateY(8px) rotate(45deg);  background: #ffffff !important; }
        #menu-toggle.menu-active .bar:nth-child(2) { opacity: 0; transform: scaleX(0); }
        #menu-toggle.menu-active .bar:nth-child(3) { transform: translateY(-8px) rotate(-45deg); background: #ffffff !important; }

        .mobile-link { transition: color 0.2s ease, padding-left 0.2s ease; }
        .mobile-link:hover { color: #C67C4E; }

        /* Hamburger button */
        #menu-toggle {
            position: fixed; top: 12px; right: 16px; z-index: 9999;
            width: 44px; height: 44px;
            display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 6px;
            background: rgba(0,0,0,0.25); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px; cursor: pointer; padding: 0;
            transition: background 0.3s ease, border-color 0.3s ease;
        }
        #menu-toggle.menu-active {
            background: rgba(198, 124, 78, 0.25);
            border-color: rgba(198, 124, 78, 0.4);
        }

        /* Sembunyikan tombol di desktop */
        @media (min-width: 1280px) {
            #menu-toggle { display: none; }
            #mobile-nav-icons { display: none !important; }
        }

        /* Mobile nav icon row (chat + bell) */
        #mobile-nav-icons {
            position: fixed; top: 12px; right: 68px; z-index: 9999;
            display: flex; align-items: center; gap: 6px;
        }

        /* Icon buttons (messages / notifications) - adaptive color */
.nav-icon-btn {
    background: rgba(44,24,16,0.04);
    border: 1px solid rgba(44,24,16,0.10);
    transition: background 0.3s ease, border-color 0.3s ease;
}
.nav-icon-btn:hover {
    background: rgba(44,24,16,0.08);
}
.nav-icon-svg {
    color: #5C4232;
    transition: color 0.3s ease;
}

/* Hero (transparent navbar) state */
#navbar.nav-hero .nav-icon-btn {
    background: rgba(255,255,255,0.08);
    border-color: rgba(255,255,255,0.20);
}
#navbar.nav-hero .nav-icon-btn:hover {
    background: rgba(255,255,255,0.14);
}
#navbar.nav-hero .nav-icon-svg {
    color: #ffffff;
}

#mobile-nav-icons .nav-icon-btn {
    background: rgba(44,24,16,0.04);
    border: 1px solid rgba(44,24,16,0.10);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    transition: background 0.3s ease, border-color 0.3s ease;
}
#mobile-nav-icons .nav-icon-svg {
    color: #5C4232;
    transition: color 0.3s ease;
}

/* Hero state (belum scroll, di atas gambar) → gelap-transparan + icon putih (kayak Image 1) */
#mobile-nav-icons.icons-hero .nav-icon-btn {
    background: rgba(255,255,255,0.08);
    border-color: rgba(255,255,255,0.20);
}
#mobile-nav-icons.icons-hero .nav-icon-svg {
    color: #ffffff;
}
    </style>
</head>

<body class="bg-cs-bg-primary text-[#2C1810] min-h-screen font-sans antialiased @yield('body-class')">

    <!-- ════════════════ NAVBAR ════════════════ -->
    <nav
    id="navbar"
    class="fixed top-0 left-0 right-0 z-50 @yield('navbar-extra-class')"
>
    <div
        id="navbar-container"
        class="max-w-7xl mx-auto px-6 lg:px-10 h-16 flex items-center justify-between transition-all duration-300"
    >

        <!-- Logo -->
        <a href="/" class="flex items-center gap-3 shrink-0">
          <img src="{{ asset('images/landing/logo_web1.png') }}" alt="CookSpace Logo" class="h-32 w-32">
        </a>

        <!-- Desktop Menu -->
        <div class="hidden xl:flex items-center gap-8">
            @auth
                <a href="{{ route('welcome') }}"     class="nav-link">Home</a>
                <a href="{{ route('foods.index') }}"  class="nav-link">Recipes</a>
                <a href="{{ route('cookers.index') }}"  class="nav-link">Cookers</a>
                <a href="{{ route('welcome') }}#stories"  class="nav-link">Stories</a>
                <a href="{{ route('welcome') }}#feedback" class="nav-link">Contact</a>
                @if(!Auth::user()->isCooker())
                    <a href="{{ route('dashboard') }}" class="nav-link nav-link-accent">Dashboard</a>
                @endif
                @if(Auth::user()->isCooker())
                    <a href="{{ route('cooker.dashboard') }}" class="nav-link nav-link-accent">Cooker Panel</a>
                @endif
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-link nav-link-accent">Admin Panel</a>
                @endif
            @else
                <a href="{{ route('welcome') }}#hero"     class="nav-link">Home</a>
                <a href="{{ route('welcome') }}#recipes"  class="nav-link">Recipes</a>
                <a href="{{ route('welcome') }}#cookers"  class="nav-link">Cookers</a>
                <a href="{{ route('welcome') }}#stories"  class="nav-link">Stories</a>
                <a href="{{ route('welcome') }}#feedback" class="nav-link">Contact</a>
            @endauth
        </div>

        <!-- Desktop Auth + Theme Toggle -->
        <div class="hidden xl:flex items-center gap-3">
            @auth
                <!-- User Dropdown Menu -->
                <div class="relative" id="user-dropdown-wrapper">
                    <button id="user-dropdown-btn" class="flex items-center gap-3 bg-[rgba(44,24,16,0.04)] hover:bg-[rgba(44,24,16,0.08)] border border-[rgba(44,24,16,0.10)] rounded-full py-1.5 pl-2.5 pr-4 text-left cursor-pointer transition-all duration-200 focus:outline-none shadow-sm">
                        <!-- Avatar / Logo User -->
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ Auth::user()->getProfilePhotoUrl() }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover border border-black/10">
                        @else
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center text-white text-xs font-bold border border-black/10">
                                {{ Auth::user()->getInitials() }}
                            </div>
                        @endif
                        <!-- Name and Role -->
                        <div class="leading-tight">
                            <div id="user-name-text" class="text-[#2C1810] text-sm font-semibold max-w-[120px] truncate">{{ Auth::user()->name }}</div>
                            <div id="user-role-text" class="text-[#7A6248] text-[0.7rem] uppercase tracking-wider font-medium">
                                @if(Auth::user()->isCooker())
                                    Cooker
                                @elseif(Auth::user()->isAdmin())
                                    Admin
                                @else
                                    Customer
                                @endif
                            </div>
                        </div>
                        <!-- Chevron icon -->
                        <svg class="w-4 h-4 text-[#7A6248]/60 ml-1 transition-transform duration-200" id="dropdown-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu Box -->
                    <div id="user-dropdown-menu" class="absolute right-0 mt-2 w-56 bg-white border border-[#E8DDD2] rounded-2xl shadow-lg shadow-[rgba(44,24,16,0.08)] py-2 hidden animate-fadeIn transform origin-top-right transition-all duration-200 z-[99]">
                        <div class="px-4 py-2.5 border-b border-[#E8DDD2]">
                            <p class="text-xs text-[#7A6248] uppercase tracking-wider font-semibold mb-2">Account Menu</p>
                            <!-- Wallet Badge (moved here, replaces old plain balance line) -->
                            <a href="{{ route('wallet') }}" id="wallet-badge"
                                class="flex items-center justify-center gap-2 bg-gradient-to-r from-[#FDF5EE] to-[#FFF0E3] hover:from-[#F5E8D8] hover:to-[#FFE8CC] border border-[#E0C9B0] rounded-full py-1.5 px-3.5 text-sm font-semibold text-[#8B4513] transition-all duration-200 shadow-sm hover:shadow-md group w-full no-underline"
                                title="Virtual Wallet — Simulation">
                                <span class="text-base leading-none">{{ Auth::user()->getCountryFlag() }}</span>
                                <span class="text-xs">{{ Auth::user()->getFormattedWalletBalance() }}</span>
                            </a>
                        </div>
                        <a href="{{ route('wallet') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[#2C1810] hover:bg-[#F5EFE6] text-sm no-underline transition-colors block">
                            <span class="text-base"></span> My Wallet
                        </a>
                        @if(Auth::user()->isCustomer())
                        <a href="{{ route('dashboard') }}?tab=orders#transaction-section" class="flex items-center gap-2.5 px-4 py-2.5 text-[#2C1810] hover:bg-[#F5EFE6] text-sm no-underline transition-colors block">
                            <span class="text-base"></span> My Orders
                        </a>
                        @endif
                        <a href="{{ route('profile') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[#2C1810] hover:bg-[#F5EFE6] text-sm no-underline transition-colors block">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                              <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.964 0a9 9 0 10-11.964 0m11.964 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg> Detail & Edit Profile
                        </a>
                        <hr class="border-[#E8DDD2] my-1">
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-red-400 hover:bg-red-500/10 text-sm border-none bg-transparent cursor-pointer transition-colors block">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                  <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg> Logout
                            </button>
                        </form>
                    </div>
                </div>

            
            <!-- Messages + Notification wrapper (desktop) -->
            <div id="notif-bell-wrapper" class="relative flex items-center gap-2">

                <!-- Messages link -->
                <a href="{{ route('chat.index') }}"
                    id="chat-nav-btn"
                    class="nav-icon-btn relative flex items-center justify-center w-13 h-13 rounded-full shadow-sm"
                    title="Messages">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon-svg" style="width:18px;height:18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                    </svg>
                    <span id="chat-badge"
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-[0.6rem] font-bold rounded-full flex items-center justify-center hidden"
                        style="width:17px;height:17px;font-size:0.58rem;">0</span>
                </a>

                <!-- Notification Bell -->
                <div class="relative" id="notif-btn-wrap">
                    <button id="notif-bell-btn"
                        class="nav-icon-btn relative flex items-center justify-center w-13 h-13 rounded-full focus:outline-none shadow-sm"
                        onclick="toggleNotifDropdown(event)"
                        title="Notifications">
                        <svg class="nav-icon-svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span id="notif-badge"
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-[0.6rem] font-bold rounded-full flex items-center justify-center hidden"
                            style="width:17px;height:17px;font-size:0.58rem;">0</span>
                    </button>
                    <!-- Dropdown — anchored right below the bell button -->
                    <div id="notif-dropdown"
                        class="absolute right-0 w-80 bg-white border border-[#E8DDD2] rounded-2xl shadow-xl shadow-[rgba(44,24,16,0.10)] py-2 hidden z-[200] overflow-hidden"
                        style="top:calc(100% + 10px); max-height:420px;">
                        <div class="px-4 py-2.5 border-b border-[#E8DDD2] flex items-center justify-between">
                            <span class="text-xs text-[#7A6248] uppercase tracking-wider font-semibold">Notifications</span>
                            <button onclick="markAllNotifRead()" class="text-[0.68rem] text-[#C67C4E] hover:text-[#8B4513] font-semibold bg-none border-none cursor-pointer transition-colors">Mark all read</button>
                        </div>
                        <div id="notif-list" style="max-height:340px;overflow-y:auto;"></div>
                    </div>
                </div>

            </div><!-- /notif-bell-wrapper -->
            </div><!-- /desktop auth wrapper -->
            @else
                <a href="{{ route('login') }}"
                    id="login-btn"
                    class="nav-link px-4 py-2 border border-[rgba(44,24,16,0.15)] rounded-lg hover:border-[#C67C4E]/60 hover:bg-[#C67C4E]/8 transition-all duration-200">
                    Login
                </a>
                <a href="{{ route('register') }}"
                    class="px-6 py-2.5 bg-[#C67C4E] text-white rounded-lg hover:bg-[#b56b3f] shadow-md hover:shadow-lg hover:shadow-[#C67C4E]/30 transition-all duration-200 font-medium text-sm">
                    Register
                </a>
            @endauth
        </div>

    </div>
</nav>
    <!-- ════════════ END NAVBAR ════════════ -->


    <!-- ════════════════════════════════════════════════════
         TOMBOL HAMBURGER — fixed tersendiri, z-index 9999
         Tidak bisa tertimpa oleh layer apapun
    ═════════════════════════════════════════════════════ -->
    @auth
   <div id="mobile-nav-icons" class="fixed top-3 z-[9999] flex items-center gap-2">
    <!-- Messages link mobile -->
    <a href="{{ route('chat.index') }}"
        id="chat-nav-btn-mobile"
        class="nav-icon-btn relative flex items-center justify-center w-11 h-11 rounded-full shadow-sm"
        title="Messages">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon-svg" style="width:18px;height:18px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
        </svg>
        <span id="chat-badge-mobile"
            class="absolute -top-1 -right-1 bg-red-500 text-white text-[0.6rem] font-bold rounded-full flex items-center justify-center hidden"
            style="width:17px;height:17px;font-size:0.58rem;">0</span>
    </a>

    <!-- Notification Bell mobile -->
    <div class="relative" id="notif-mobile-btn-wrap">
        <button id="notif-bell-btn-mobile"
            class="nav-icon-btn relative flex items-center justify-center w-11 h-11 rounded-full focus:outline-none shadow-sm"
            onclick="toggleNotifDropdownMobile(event)"
            title="Notifications">
            <svg class="nav-icon-svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span id="notif-badge-mobile"
                class="absolute -top-1 -right-1 bg-red-500 text-white text-[0.6rem] font-bold rounded-full flex items-center justify-center hidden"
                style="width:17px;height:17px;font-size:0.58rem;">0</span>
        </button>
            <!-- Dropdown mobile -->
            <div id="notif-dropdown-mobile"
                class="absolute right-0 w-72 bg-white border border-[#E8DDD2] rounded-2xl shadow-xl shadow-[rgba(44,24,16,0.10)] py-2 hidden z-[9999] overflow-hidden"
                style="top:calc(100% + 10px); max-height:360px;">
                <div class="px-3 py-2 border-b border-[#E8DDD2] flex items-center justify-between">
                    <span class="text-xs text-[#7A6248] uppercase tracking-wider font-semibold">Notifications</span>
                    <button onclick="markAllNotifRead()" class="text-[0.65rem] text-[#C67C4E] hover:text-[#8B4513] font-semibold bg-none border-none cursor-pointer transition-colors">Mark all read</button>
                </div>
                <div id="notif-list-mobile" style="max-height:280px;overflow-y:auto;"></div>
            </div>
        </div>
    </div>
    @endauth

    <button
        id="menu-toggle"
        aria-label="Toggle menu"
        aria-expanded="false"
    >
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </button>

<!-- ═══════════ MOBILE MENU ═══════════ -->
<div
    id="mobile-menu"
    class="fixed top-0 left-0 right-0 z-[60]"
>
    <div class="bg-[rgba(10,8,6,0.92)] backdrop-blur-2xl border-b border-white/10 shadow-2xl max-h-[85vh] overflow-y-auto">
        <div class="px-5 pt-16 pb-5">

            <!-- Nav Links -->
            <nav class="flex flex-col gap-0.5">
                @auth
                    <a href="{{ route('welcome') }}"     class="mobile-link text-white/85 text-sm font-medium py-2 px-3 rounded-lg hover:bg-white/8 hover:text-white text-center transition-all">Home</a>
                    <a href="{{ route('foods.index') }}"  class="mobile-link text-white/85 text-sm font-medium py-2 px-3 rounded-lg hover:bg-white/8 hover:text-white text-center transition-all">Recipes</a>
                    <a href="{{ route('cookers.index') }}"  class="mobile-link text-white/85 text-sm font-medium py-2 px-3 rounded-lg hover:bg-white/8 hover:text-white text-center transition-all">Cookers</a>
                    <a href="{{ route('welcome') }}#stories"  class="mobile-link text-white/85 text-sm font-medium py-2 px-3 rounded-lg hover:bg-white/8 hover:text-white text-center transition-all">Stories</a>
                    <a href="{{ route('welcome') }}#feedback" class="mobile-link text-white/85 text-sm font-medium py-2 px-3 rounded-lg hover:bg-white/8 hover:text-white text-center transition-all">Contact</a>
                    @if(!Auth::user()->isCooker())
                        <a href="{{ route('dashboard') }}" class="mobile-link text-[#e8a87c] text-sm font-semibold py-2 px-3 rounded-lg hover:bg-[#C67C4E]/15 text-center transition-all">Dashboard</a>
                    @endif
                    @if(Auth::user()->isCooker())
                        <a href="{{ route('cooker.dashboard') }}" class="mobile-link text-[#e8a87c] text-sm font-semibold py-2 px-3 rounded-lg hover:bg-[#C67C4E]/15 text-center transition-all">Cooker Panel</a>
                    @endif
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="mobile-link text-[#e8a87c] text-sm font-semibold py-2 px-3 rounded-lg hover:bg-[#C67C4E]/15 text-center transition-all">Admin Panel</a>
                    @endif
                @else
                    <a href="{{ route('welcome') }}#hero"     class="mobile-link text-white/85 text-sm font-medium py-2 px-3 rounded-lg hover:bg-white/8 hover:text-white text-center transition-all">Home</a>
                    <a href="{{ route('welcome') }}#recipes"  class="mobile-link text-white/85 text-sm font-medium py-2 px-3 rounded-lg hover:bg-white/8 hover:text-white text-center transition-all">Recipes</a>
                    <a href="{{ route('welcome') }}#cookers"  class="mobile-link text-white/85 text-sm font-medium py-2 px-3 rounded-lg hover:bg-white/8 hover:text-white text-center transition-all">Cookers</a>
                    <a href="{{ route('welcome') }}#stories"  class="mobile-link text-white/85 text-sm font-medium py-2 px-3 rounded-lg hover:bg-white/8 hover:text-white text-center transition-all">Stories</a>
                    <a href="{{ route('welcome') }}#feedback" class="mobile-link text-white/85 text-sm font-medium py-2 px-3 rounded-lg hover:bg-white/8 hover:text-white text-center transition-all">Contact</a>
                @endauth
            </nav>

            <!-- Auth -->
            <div class="mt-3 pt-3 border-t border-white/10">
                @auth
                    <!-- User Card -->
                    <div class="flex items-center gap-3 mb-3 bg-white/5 border border-white/10 rounded-xl p-3">
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ Auth::user()->getProfilePhotoUrl() }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover border border-white/20 shrink-0">
                        @else
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center text-white text-xs font-bold border border-white/20 shrink-0">
                                {{ Auth::user()->getInitials() }}
                            </div>
                        @endif
                        <div class="leading-tight min-w-0">
                            <div class="text-white font-semibold text-sm truncate">{{ Auth::user()->name }}</div>
                            <div class="text-white/50 text-[0.65rem] uppercase tracking-wider mt-0.5">
                                @if(Auth::user()->isCooker())
                                    Cooker
                                @elseif(Auth::user()->isAdmin())
                                    Admin
                                @else
                                    Customer
                                @endif
                            </div>
                            <div class="mt-1 flex items-center gap-1">
                                <span class="text-xs">{{ Auth::user()->getCountryFlag() }}</span>
                                <span class="text-xs font-bold text-[#e8a87c]">{{ Auth::user()->getFormattedWalletBalance() }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('wallet') }}" class="flex items-center justify-center gap-2 py-2.5 border border-[#C67C4E]/30 bg-[#C67C4E]/10 rounded-lg text-[#e8a87c] hover:bg-[#C67C4E]/20 transition-all text-sm font-medium no-underline">
                            My Wallet
                        </a>
                        @if(Auth::user()->isCustomer())
                        <a href="{{ route('dashboard') }}?tab=orders#transaction-section" class="mobile-link flex items-center justify-center gap-2 py-2.5 border border-white/15 rounded-lg text-white/85 hover:bg-white/8 hover:text-white transition-all text-sm font-medium no-underline">
                            My Orders
                        </a>
                        @endif
                        <a href="{{ route('profile') }}" class="flex items-center justify-center gap-2 py-2.5 border border-white/15 rounded-lg text-white/85 hover:bg-white/8 hover:text-white transition-all text-sm font-medium no-underline">
                            Profile Detail &amp; Edit
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-2.5 border border-red-500/25 rounded-lg text-red-400 hover:bg-red-500/10 hover:border-red-500/40 transition-all duration-200 bg-transparent cursor-pointer font-medium text-sm">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Guest Buttons -->
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('login') }}"
                            class="flex items-center justify-center py-2.5 border border-white/20 rounded-lg text-white font-medium hover:bg-white/8 hover:border-white/35 transition-all duration-200 text-sm no-underline">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="flex items-center justify-center py-2.5 rounded-lg bg-[#C67C4E] text-white font-semibold hover:bg-[#b56b3f] transition-all duration-200 text-sm no-underline shadow-lg shadow-[#C67C4E]/20">
                            Register
                        </a>
                    </div>
                @endauth
            </div>

        </div>
    </div>
</div>
<!-- ════════ END MOBILE MENU ════════ -->


    <!-- ════════ OVERLAY (klik luar = tutup menu) ════════ -->
    <div
        id="menu-overlay"
        class="fixed inset-0 z-[55] bg-black/40"
    ></div>
    <!-- ════════ END OVERLAY ════════ -->


    <!-- Main Content -->
    <main class="@yield('main-class', 'w-full max-w-full px-8 sm:px-12 xl:px-16 py-8 max-sm:px-4 max-sm:py-6')">
        @yield('content')
    </main>


    @yield('scripts')

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // ── Navbar scroll handler ──────────────────────────────
        const navbar    = document.getElementById('navbar');
        const container = document.getElementById('navbar-container');
        const isHeroPage = navbar.classList.contains('nav-hero');

        const toggle       = document.getElementById('menu-toggle');
        const mobileIcons  = document.getElementById('mobile-nav-icons');
        const menu         = document.getElementById('mobile-menu');
        const overlay      = document.getElementById('menu-overlay');

        // ── Sync warna ikon message & notif mobile ──────────────
        function syncMobileIconColor() {
            if (!mobileIcons) return;
            const menuIsOpen  = menu.classList.contains('menu-open');
            const shouldBeWhite = menuIsOpen || (isHeroPage && window.scrollY <= 50);
            mobileIcons.classList.toggle('icons-hero', shouldBeWhite);
        }

        function handleNavbar() {
            if (window.scrollY > 50) {
                if (isHeroPage) navbar.classList.remove('nav-hero');
                navbar.classList.add('nav-scrolled');
                container.classList.replace('h-20', 'h-16');
                toggle.classList.remove('hero-bars');
            } else {
                navbar.classList.remove('nav-scrolled');
                if (isHeroPage) {
                    navbar.classList.add('nav-hero');
                    toggle.classList.add('hero-bars');
                }
                container.classList.replace('h-16', 'h-20');
            }
            syncMobileIconColor();
        }
        handleNavbar();
        window.addEventListener('scroll', handleNavbar, { passive: true });

        // ── Mobile menu ────────────────────────────────────────
        function openMenu() {
            menu.classList.add('menu-open');
            overlay.classList.add('overlay-open');
            toggle.classList.add('menu-active');
            toggle.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
            syncMobileIconColor();
        }
        function closeMenu() {
            menu.classList.remove('menu-open');
            overlay.classList.remove('overlay-open');
            toggle.classList.remove('menu-active');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
            syncMobileIconColor();
        }

        toggle.addEventListener('click', () => menu.classList.contains('menu-open') ? closeMenu() : openMenu());
        overlay.addEventListener('click', closeMenu);
        document.querySelectorAll('.mobile-link').forEach(l => l.addEventListener('click', closeMenu));
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMenu(); });

        // ── Desktop dropdown ───────────────────────────────────
        const dropdownBtn     = document.getElementById('user-dropdown-btn');
        const dropdownMenu    = document.getElementById('user-dropdown-menu');
        const dropdownChevron = document.getElementById('dropdown-chevron');

        if (dropdownBtn && dropdownMenu) {
            dropdownBtn.addEventListener('click', e => {
                e.stopPropagation();
                const hidden = dropdownMenu.classList.contains('hidden');
                dropdownMenu.classList.toggle('hidden', !hidden);
                dropdownChevron.style.transform = hidden ? 'rotate(180deg)' : 'rotate(0deg)';
            });
            document.addEventListener('click', e => {
                if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                    dropdownChevron.style.transform = 'rotate(0deg)';
                }
            });
        }
    });
</script>

<script>
/* ── Global AJAX Follow / Unfollow ──────────────────────────────── */
window._followCsrf = '{{ csrf_token() }}';

async function toggleFollow(btn, cookerId, url) {
    if (!cookerId || !url) return;

    if (btn.dataset.transitioning === 'true') return;
    btn.dataset.transitioning = 'true';

    const currentFollowing = btn.dataset.following === '1';
    const nextFollowing = !currentFollowing;

    // Save previous state for rollback
    const prevStates = [];
    document.querySelectorAll('[data-follow-cooker="' + cookerId + '"]').forEach(el => {
        prevStates.push({
            el: el,
            following: el.dataset.following,
            innerHTML: el.innerHTML,
            className: el.className
        });
    });

    const prevCounters = [];
    document.querySelectorAll('[data-followers-count="' + cookerId + '"]').forEach(el => {
        prevCounters.push({
            el: el,
            textContent: el.textContent
        });
    });

    // ── Apply Optimistic Updates ──
    document.querySelectorAll('[data-follow-cooker="' + cookerId + '"]').forEach(el => {
        el.dataset.following = nextFollowing ? '1' : '0';
        _applyFollowState(el, nextFollowing);
    });

    document.querySelectorAll('[data-followers-count="' + cookerId + '"]').forEach(el => {
        const currentCount = parseInt(el.textContent.trim()) || 0;
        const newCount = nextFollowing ? currentCount + 1 : Math.max(0, currentCount - 1);
        el.textContent = newCount + (el.dataset.suffix || '');
    });

    try {
        const res  = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window._followCsrf,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        const data = await res.json();
        if (!res.ok || !data.success) throw new Error(data.message || 'Error');

        // Optional: synchronize final count from server to ensure correctness
        document.querySelectorAll('[data-followers-count="' + cookerId + '"]').forEach(el => {
            el.textContent = data.followers_count + (el.dataset.suffix || '');
        });

    } catch (err) {
        console.error('[Follow Error - Rolling back]', err);
        
        // Rollback states on error
        prevStates.forEach(state => {
            state.el.dataset.following = state.following;
            state.el.innerHTML = state.innerHTML;
            state.el.className = state.className;
        });

        prevCounters.forEach(counter => {
            counter.el.textContent = counter.textContent;
        });

        alert(err.message || 'Gagal mengubah status ikuti.');
    } finally {
        btn.dataset.transitioning = 'false';
    }
}

function _applyFollowState(btn, following) {
    const classFollow = btn.dataset.classFollow;
    const classUnfollow = btn.dataset.classUnfollow;

    if (following) {
        btn.innerHTML = btn.dataset.labelUnfollow || ' Unfollow';
        if (classFollow && classUnfollow) {
            classFollow.split(/\s+/).forEach(c => {
                if (c.trim()) btn.classList.remove(c.trim());
            });
            classUnfollow.split(/\s+/).forEach(c => {
                if (c.trim()) btn.classList.add(c.trim());
            });
        } else {
            btn.className = btn.className
                .replace(/bg-gradient-to-r\s+from-\[#C67C4E\]\s+to-\[#b56b3f\]/g, '')
                .replace(/bg-white/g, '')
                .replace(/text-\[#C67C4E\]/g, '')
                .replace(/border-\[#C67C4E\]/g, '')
                .replace(/hover:bg-\[#C67C4E\]/g, '')
                .replace(/hover:text-white/g, '')
                .trim();
            btn.className = btn.className.replace(/text-white/g, '').trim();
            if (!btn.className.includes('bg-[#7A6B5D]')) {
                btn.className += ' bg-[#7A6B5D] text-white border-[#7A6B5D] hover:bg-[#5C4D40] hover:border-[#5C4D40]';
            }
        }
    } else {
        btn.innerHTML = btn.dataset.labelFollow || ' Follow';
        if (classFollow && classUnfollow) {
            classUnfollow.split(/\s+/).forEach(c => {
                if (c.trim()) btn.classList.remove(c.trim());
            });
            classFollow.split(/\s+/).forEach(c => {
                if (c.trim()) btn.classList.add(c.trim());
            });
        } else {
            btn.className = btn.className
                .replace(/bg-\[#7A6B5D\]/g, '')
                .replace(/border-\[#7A6B5D\]/g, '')
                .replace(/hover:bg-\[#5C4D40\]/g, '')
                .replace(/hover:border-\[#5C4D40\]/g, '')
                .replace(/text-white/g, '')
                .trim();
            const baseClass = btn.dataset.btnVariant === 'pill'
                ? 'bg-white text-[#C67C4E] border-[#C67C4E] hover:bg-[#C67C4E] hover:text-white'
                : 'bg-white text-[#C67C4E] border-[#C67C4E] hover:bg-[#C67C4E] hover:text-white';
            if (!btn.className.includes('text-[#C67C4E]')) {
                btn.className += ' ' + baseClass;
            }
        }
    }
}
</script>


@auth
<style>
/* Toast notifications */
#toast-container {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    z-index: 10000;
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    pointer-events: none;
}
.toast-item {
    background: white;
    border: 1px solid #EDE5DA;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(44,24,16,0.18);
    padding: 0.85rem 1rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    max-width: 320px;
    min-width: 280px;
    pointer-events: all;
    animation: toastSlideIn 0.35s cubic-bezier(0.34,1.56,0.64,1);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}
.toast-item::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, #C67C4E, #e8a87c);
    border-radius: 4px 0 0 4px;
}
.toast-item.toast-order { }
.toast-item.toast-order::before { background: linear-gradient(180deg, #10b981, #34d399); }
.toast-item.toast-chat::before { background: linear-gradient(180deg, #3b82f6, #60a5fa); }
.toast-icon { font-size: 1.4rem; flex-shrink: 0; }
.toast-body { flex: 1; }
.toast-title { font-weight: 700; font-size: 0.82rem; color: #2C1810; }
.toast-msg { font-size: 0.75rem; color: #7A6248; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.toast-close { color: #C3A882; font-size: 0.9rem; flex-shrink: 0; background: none; border: none; cursor: pointer; padding: 0; }
.toast-close:hover { color: #8B4513; }
@keyframes toastSlideIn {
    from { opacity: 0; transform: translateX(60px); }
    to   { opacity: 1; transform: translateX(0); }
}
@keyframes toastSlideOut {
    from { opacity: 1; transform: translateX(0); }
    to   { opacity: 0; transform: translateX(60px); }
}

/* Notification dropdown items */
.notif-item {
    display: flex;
    align-items: flex-start;
    gap: 0.6rem;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #F5EFE6;
    transition: background 0.15s;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}
.notif-item:hover { background: #FDF5EE; }
.notif-item.unread { background: #FFFAF5; }
.notif-item.unread:hover { background: #FDF0E6; }
.notif-icon { font-size: 1.2rem; flex-shrink: 0; margin-top: 1px; }
.notif-body { flex: 1; min-width: 0; }
.notif-title { font-size: 0.8rem; font-weight: 600; color: #2C1810; }
.notif-msg { font-size: 0.72rem; color: #7A6248; margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.notif-time { font-size: 0.65rem; color: #BBA88C; margin-top: 2px; }
.notif-unread-dot {
    width: 8px; height: 8px;
    background: #C67C4E; border-radius: 50%;
    flex-shrink: 0; margin-top: 5px;
}
</style>

<div id="toast-container"></div>

<script>
const NOTIF_POLL_URL = '{{ route('api.notifications') }}';
const MARK_ALL_URL   = '{{ route('notifications.mark-all-read') }}';
const CURRENT_USER_ID = {{ Auth::id() }};
const NOTIF_CSRF = '{{ csrf_token() }}';

let knownNotifIds = new Set();
let notifPollInterval = null;
let notifEchoWorking = false;
let isFirstPoll = true;

/* ── Bell dropdown toggle ─────────────── */
function toggleNotifDropdown(e) {
    e.stopPropagation();
    const dd = document.getElementById('notif-dropdown');
    const isHidden = dd.classList.contains('hidden');
    dd.classList.toggle('hidden');
    if (isHidden) {
        loadNotifications();
    }
}
function toggleNotifDropdownMobile(e) {
    e.stopPropagation();
    const dd = document.getElementById('notif-dropdown-mobile');
    const isHidden = dd.classList.contains('hidden');
    dd.classList.toggle('hidden');
    if (isHidden) {
        loadNotifications();
    }
}
document.addEventListener('click', (e) => {
    const wrapper = document.getElementById('notif-bell-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('notif-dropdown')?.classList.add('hidden');
    }
    const wrapperMobile = document.getElementById('notif-mobile-btn-wrap');
    if (wrapperMobile && !wrapperMobile.contains(e.target)) {
        document.getElementById('notif-dropdown-mobile')?.classList.add('hidden');
    }
});

/* ── Load and render notifications ───── */
async function loadNotifications() {
    try {
        const res  = await fetch(NOTIF_POLL_URL);
        const data = await res.json();
        renderNotifications(data.notifications, data.unread_count, data.unread_chat_count);
    } catch(e) { /* silent */ }
}

function renderNotifications(notifications, unreadCount, unreadChatCount) {
    const badge = document.getElementById('notif-badge');
    const badgeMobile = document.getElementById('notif-badge-mobile');
    const txt = unreadCount > 9 ? '9+' : unreadCount;
    
    if (unreadCount > 0) {
        if (badge) { badge.textContent = txt; badge.classList.remove('hidden'); }
        if (badgeMobile) { badgeMobile.textContent = txt; badgeMobile.classList.remove('hidden'); }
    } else {
        if (badge) badge.classList.add('hidden');
        if (badgeMobile) badgeMobile.classList.add('hidden');
    }

    const chatBadge = document.getElementById('chat-badge');
    const chatBadgeMobile = document.getElementById('chat-badge-mobile');
    const ctxt = unreadChatCount > 9 ? '9+' : unreadChatCount;

    if (chatBadge) {
        if (unreadChatCount > 0) {
            chatBadge.textContent = ctxt;
            chatBadge.classList.remove('hidden');
        } else {
            chatBadge.classList.add('hidden');
        }
    }
    if (chatBadgeMobile) {
        if (unreadChatCount > 0) {
            chatBadgeMobile.textContent = ctxt;
            chatBadgeMobile.classList.remove('hidden');
        } else {
            chatBadgeMobile.classList.add('hidden');
        }
    }

    const list = document.getElementById('notif-list');
    const listMobile = document.getElementById('notif-list-mobile');

    const html = !notifications.length
        ? '<div style="padding:1.5rem;text-align:center;color:#9A7B5A;font-size:0.8rem;">No notifications yet</div>'
        : notifications.map(n => {
            const d = n.data;
            const url = '/notifications/' + n.id + '/read';
            return `<a href="${url}" class="notif-item ${!n.read ? 'unread' : ''}">
                <span class="notif-icon">${d.icon || ''}</span>
                <div class="notif-body">
                    <div class="notif-title">${esc(d.title || '')}</div>
                    <div class="notif-msg">${esc(d.body || '')}</div>
                    <div class="notif-time">${n.time_ago || ''}</div>
                </div>
                ${!n.read ? '<span class="notif-unread-dot"></span>' : ''}
            </a>`;
        }).join('');

    if (list) list.innerHTML = html;
    if (listMobile) listMobile.innerHTML = html;
}

async function markAllNotifRead() {
    try {
        await fetch(MARK_ALL_URL, { method: 'POST', headers: { 'X-CSRF-TOKEN': NOTIF_CSRF } });
        document.getElementById('notif-badge')?.classList.add('hidden');
        document.getElementById('notif-badge-mobile')?.classList.add('hidden');
        document.querySelectorAll('.notif-item.unread').forEach(el => {
            el.classList.remove('unread');
            el.querySelector('.notif-unread-dot')?.remove();
        });
    } catch(e) { /* silent */ }
}

/* ── Toast popup — disabled (badge-only mode) ── */
function showToast(notif, toastType) { /* intentionally no-op: notifications are badge-only */ }

function esc(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

/* ── Polling for new notifications ────── */
async function pollNotifications() {
    try {
        const res  = await fetch(NOTIF_POLL_URL);
        const data = await res.json();
        renderNotifications(data.notifications, data.unread_count, data.unread_chat_count);

        // Track newly arrived unread notifications for chat sidebar sync
        data.notifications.filter(n => !n.read && !knownNotifIds.has(n.id)).forEach(n => {
            if (!isFirstPoll) {
                if (n.data?.type === 'chat') {
                    const payload = {
                        room_id: parseInt(n.data.room_id),
                        body: n.data.body,
                        created_at_ts: n.data.created_at_ts || Math.floor(new Date(n.created_at || Date.now()).getTime() / 1000)
                    };
                    document.dispatchEvent(new CustomEvent('chat-notification-received', { detail: payload }));
                }
            }
            knownNotifIds.add(n.id);
        });
        // Track all known
        data.notifications.forEach(n => knownNotifIds.add(n.id));
        isFirstPoll = false;
    } catch(e) { /* silent */ }
}

/* ── Laravel Echo – listen for broadcast notifications ── */
function setupEchoNotifications() {
    if (typeof window.Echo === 'undefined') { startNotifPolling(); return; }
    try {
        window.Echo.private('App.Models.User.' + CURRENT_USER_ID)
            .notification((notification) => {
                notifEchoWorking = true;
                loadNotifications();
                if (notification.type === 'chat') {
                    const payload = {
                        room_id: parseInt(notification.room_id),
                        body: notification.body,
                        created_at_ts: notification.created_at_ts || Math.floor(Date.now() / 1000)
                    };
                    document.dispatchEvent(new CustomEvent('chat-notification-received', { detail: payload }));
                }
            });
        console.log('[Notif] Echo listening for notifications');
    } catch(e) {
        console.warn('[Notif] Echo failed, falling back to polling', e);
        startNotifPolling();
    }
}

function startNotifPolling() {
    if (notifPollInterval) return;
    console.log('[Notif] Using polling for notifications');
    notifPollInterval = setInterval(pollNotifications, 8000);
}

// Init
document.addEventListener('DOMContentLoaded', () => {
    pollNotifications(); // initial load to show badge count
    setupEchoNotifications();
    // Even with Echo, poll every 30s as a safety net
    setInterval(pollNotifications, 30000);
});
</script>
@endauth

</body>
</html>