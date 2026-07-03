<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login — CookSpace</title>
    <meta name="description" content="Log in to CookSpace to enjoy the best culinary services">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/compiled.css', 'resources/js/app.js'])
    @endif

    @yield('styles')
</head>
<body class="bg-cs-bg-primary text-[#2C1810] min-h-screen flex items-center justify-center font-sans antialiased relative overflow-hidden">
    <div class="fixed rounded-full filter blur-[80px] opacity-50 animate-float pointer-events-none w-[400px] h-[400px] bg-[rgba(238,77,45,0.08)] -top-[100px] -left-[100px]"></div>
    <div class="fixed rounded-full filter blur-[80px] opacity-50 animate-float pointer-events-none w-[350px] h-[350px] bg-[rgba(3,172,14,0.06)] -bottom-[100px] -right-[100px] [animation-delay:-7s]"></div>
    <div class="fixed rounded-full filter blur-[80px] opacity-50 animate-float pointer-events-none w-[250px] h-[250px] bg-[rgba(59,130,246,0.05)] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 [animation-delay:-14s]"></div>

    <div class="relative z-10 w-full max-w-[440px] px-6">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2 no-underline font-bold text-2xl text-[#2C1810] mb-6">
                <span class="w-10 h-10 bg-gradient-to-br from-cs-orange to-[#ff7337] rounded-xl flex items-center justify-center text-xl text-white">🍳</span>
                <span>CookSpace</span>
            </a>
            <h1 class="text-3xl font-bold mb-2 bg-gradient-to-br from-[#1e293b] to-[#475569] bg-clip-text text-transparent">Welcome Back</h1>
            <p class="text-[#64748b] text-sm">Sign in to your account to continue</p>
        </div>

        <div class="bg-white border border-[#E8DDD2] rounded-2xl p-8 shadow-[0_4px_20px_rgba(0,0,0,0.05)] animate-fadeInUp">
            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-3.5 mb-5 text-sm text-red-500">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf

                <div class="mb-5">
                    <label for="email" class="block text-xs font-semibold text-[#475569] mb-2 tracking-wide uppercase">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="w-full px-4 py-3 bg-white border {{ $errors->has('email') ? 'border-red-500 focus:ring-red-500/10' : 'border-slate-300 focus:border-cs-orange focus:ring-cs-orange/10' }} rounded-xl text-[#2C1810] text-sm transition-all outline-none focus:ring-3"
                        value="{{ old('email') }}"
                        placeholder="name@email.com"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-xs font-semibold text-[#475569] mb-2 tracking-wide uppercase">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="w-full px-4 py-3 bg-white border {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500/10' : 'border-slate-300 focus:border-cs-orange focus:ring-cs-orange/10' }} rounded-xl text-[#2C1810] text-sm transition-all outline-none focus:ring-3"
                        placeholder="Enter password"
                        required
                    >
                </div>

                <div class="flex items-center gap-2 mb-6">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 accent-cs-orange cursor-pointer" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="text-sm text-[#64748b] cursor-pointer select-none">Remember me</label>
                </div>

                <button type="submit" class="relative overflow-hidden w-full px-6 py-3.5 bg-gradient-to-r from-cs-orange to-[#ff7337] text-white border-none rounded-xl text-sm font-semibold cursor-pointer transition-all shadow-[0_2px_10px_rgba(238,77,45,0.15)] hover:-translate-y-px hover:shadow-[0_4px_15px_rgba(238,77,45,0.25)] active:translate-y-0" id="btn-login">
                    Sign In
                </button>
            </form>
        </div>

        <div class="text-center mt-6 text-sm text-[#64748b]">
            Don't have an account? <a href="{{ route('register') }}" class="no-underline font-semibold transition-colors hover:text-[#ff7337] hover:underline text-cs-orange">Register now</a>
        </div>
    </div>
</body>
</html>
