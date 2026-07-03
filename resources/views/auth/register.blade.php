<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register — CookSpace</title>
    <meta name="description" content="Register in CookSpace as Customer or Cooker">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/compiled.css', 'resources/js/app.js'])
    @endif

    @yield('styles')
</head>
<body class="bg-cs-bg-primary text-[#2C1810] min-h-screen flex items-center justify-center font-sans antialiased relative overflow-x-hidden py-8">
    <div class="fixed rounded-full filter blur-[80px] opacity-50 animate-float pointer-events-none w-[400px] h-[400px] bg-[rgba(238,77,45,0.08)] -top-[100px] -right-[100px]"></div>
    <div class="fixed rounded-full filter blur-[80px] opacity-50 animate-float pointer-events-none w-[350px] h-[350px] bg-[rgba(3,172,14,0.06)] -bottom-[100px] -left-[100px] [animation-delay:-7s]"></div>
    <div class="fixed rounded-full filter blur-[80px] opacity-50 animate-float pointer-events-none w-[200px] h-[200px] bg-[rgba(59,130,246,0.05)] top-[30%] left-[20%] [animation-delay:-14s]"></div>

    <div class="relative z-10 w-full max-w-[480px] px-6">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2 no-underline font-bold text-2xl text-[#2C1810] mb-6">
                <span class="w-10 h-10 bg-gradient-to-br from-cs-orange to-[#ff7337] rounded-xl flex items-center justify-center text-xl text-white">🍳</span>
                <span>CookSpace</span>
            </a>
            <h1>Create New Account</h1>
            <p>Join CookSpace now</p>
        </div>

        <div class="bg-white border border-[#E8DDD2] rounded-2xl p-8 shadow-[0_4px_20px_rgba(0,0,0,0.05)] animate-fadeInUp">
            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-3.5 mb-5 text-sm text-red-500">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf

                <div class="mb-5">
                    <label for="name" class="block text-xs font-semibold text-[#475569] mb-2 tracking-wide uppercase">Full Name</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        class="w-full px-4 py-3 bg-white border {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500/10' : 'border-slate-300 focus:border-cs-orange focus:ring-cs-orange/10' }} rounded-xl text-[#2C1810] text-sm transition-all outline-none focus:ring-3"
                        value="{{ old('name') }}"
                        placeholder="Enter full name"
                        required
                        autofocus
                    >
                    @error('name')
                        <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div>
                    @enderror
                </div>

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
                    >
                    @error('email')
                        <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-xs font-semibold text-[#475569] mb-2 tracking-wide uppercase">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="w-full px-4 py-3 bg-white border {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500/10' : 'border-slate-300 focus:border-cs-orange focus:ring-cs-orange/10' }} rounded-xl text-[#2C1810] text-sm transition-all outline-none focus:ring-3"
                        placeholder="Minimum 8 characters"
                        required
                    >
                    @error('password')
                        <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password_confirmation" class="block text-xs font-semibold text-[#475569] mb-2 tracking-wide uppercase">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="w-full px-4 py-3 bg-white border border-slate-300 focus:border-cs-orange focus:ring-cs-orange/10 rounded-xl text-[#2C1810] text-sm transition-all outline-none focus:ring-3"
                        placeholder="Repeat password"
                        required
                    >
                </div>

                <!-- Role Selector -->
                <label class="block text-xs font-semibold text-[#475569] mb-3 tracking-wide uppercase">I want to join as</label>
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <label class="relative cursor-pointer group" id="role-customer-card">
                        <input type="radio" name="role" value="customer" {{ old('role', 'customer') === 'customer' ? 'checked' : '' }} required class="absolute opacity-0 pointer-events-none peer">
                        <div class="flex flex-col items-center gap-2 p-5 bg-white border-2 border-slate-300 rounded-xl text-center transition-all group-hover:bg-[#F5EFE6] group-hover:border-slate-400 peer-checked:border-cs-orange peer-checked:bg-cs-orange-glow peer-checked:shadow-[0_2px_10px_rgba(238,77,45,0.1)]">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-blue-50 transition-transform group-hover:scale-105 peer-checked:group-hover:scale-115">🛒</div>
                            <span class="text-sm font-semibold text-[#1e293b]">Customer</span>
                            <span class="text-[0.72rem] text-[#64748b] leading-tight">Order food from the best cookers</span>
                        </div>
                        <span class="absolute top-2 right-2 w-5 h-5 bg-gradient-to-br from-cs-orange to-[#ff7337] rounded-full flex items-center justify-center opacity-0 scale-50 transition-all peer-checked:opacity-100 peer-checked:scale-100">
                            <svg viewBox="0 0 24 24" class="w-3 h-3 stroke-white stroke-[3] fill-none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </span>
                    </label>

                    <label class="relative cursor-pointer group" id="role-cooker-card">
                        <input type="radio" name="role" value="cooker" {{ old('role') === 'cooker' ? 'checked' : '' }} required class="absolute opacity-0 pointer-events-none peer">
                        <div class="flex flex-col items-center gap-2 p-5 bg-white border-2 border-slate-300 rounded-xl text-center transition-all group-hover:bg-[#F5EFE6] group-hover:border-slate-400 peer-checked:border-cs-orange peer-checked:bg-cs-orange-glow peer-checked:shadow-[0_2px_10px_rgba(238,77,45,0.1)]">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-orange-50 transition-transform group-hover:scale-105 peer-checked:group-hover:scale-115">👨‍🍳</div>
                            <span class="text-sm font-semibold text-[#1e293b]">Cooker</span>
                            <span class="text-[0.72rem] text-[#64748b] leading-tight">Sell dishes &amp; earn income</span>
                        </div>
                        <span class="absolute top-2 right-2 w-5 h-5 bg-gradient-to-br from-cs-orange to-[#ff7337] rounded-full flex items-center justify-center opacity-0 scale-50 transition-all peer-checked:opacity-100 peer-checked:scale-100">
                            <svg viewBox="0 0 24 24" class="w-3 h-3 stroke-white stroke-[3] fill-none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </span>
                    </label>
                </div>
                @error('role')
                    <div class="text-xs text-red-500 -mt-4 mb-4">{{ $message }}</div>
                @enderror

                <!-- Country Selector -->
                <label class="block text-xs font-semibold text-[#475569] mb-3 tracking-wide uppercase">Country of Origin</label>
                <div class="grid grid-cols-3 gap-3 mb-4" id="country-selector-group">
                    @foreach([
                        ['code' => 'ID', 'flag' => '🇮🇩', 'name' => 'Indonesia', 'currency' => 'IDR', 'balance' => 'Rp 10.000.000', 'color' => 'red'],
                        ['code' => 'SG', 'flag' => '🇸🇬', 'name' => 'Singapore', 'currency' => 'SGD', 'balance' => 'S$1,000.00', 'color' => 'rose'],
                        ['code' => 'MY', 'flag' => '🇲🇾', 'name' => 'Malaysia',  'currency' => 'MYR', 'balance' => 'RM3,000.00', 'color' => 'blue'],
                    ] as $c)
                    <label class="relative cursor-pointer group country-card" data-balance="{{ $c['balance'] }}" data-currency="{{ $c['currency'] }}" data-country="{{ $c['code'] }}">
                        <input type="radio" name="country" value="{{ $c['code'] }}" {{ old('country', 'ID') === $c['code'] ? 'checked' : '' }} required class="absolute opacity-0 pointer-events-none peer">
                        <div class="flex flex-col items-center gap-1.5 p-3.5 bg-white border-2 border-slate-200 rounded-xl text-center transition-all group-hover:border-slate-400 peer-checked:border-cs-orange peer-checked:bg-cs-orange-glow peer-checked:shadow-[0_2px_10px_rgba(198,124,78,0.15)]">
                            <span class="text-2xl leading-none">{{ $c['flag'] }}</span>
                            <span class="text-xs font-bold text-[#1e293b]">{{ $c['name'] }}</span>
                            <span class="text-[0.65rem] font-medium text-[#C67C4E] bg-[#FDF5EE] px-1.5 py-0.5 rounded-full">{{ $c['currency'] }}</span>
                        </div>
                        <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-gradient-to-br from-cs-orange to-[#ff7337] rounded-full flex items-center justify-center opacity-0 scale-50 transition-all peer-checked:opacity-100 peer-checked:scale-100">
                            <svg viewBox="0 0 24 24" class="w-2.5 h-2.5 stroke-white stroke-[3] fill-none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </span>
                    </label>
                    @endforeach
                </div>
                @error('country')
                    <div class="text-xs text-red-500 -mt-2 mb-3">{{ $message }}</div>
                @enderror

                <!-- Wallet Preview -->
                <div id="wallet-preview" class="mb-5 p-4 rounded-xl border border-[#E0D5C8] bg-gradient-to-r from-[#FDF5EE] to-[#FFF8F2]">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-base">💰</span>
                        <span class="text-xs font-semibold text-[#475569] uppercase tracking-wide">Initial Virtual Wallet Balance</span>
                    </div>
                    <div id="wallet-balance-text" class="text-xl font-bold text-[#C67C4E]">Rp 10.000.000</div>
                    <p class="text-[0.7rem] text-[#94a3b8] mt-1 leading-snug">
                        ⚠️ This is <strong>virtual money for simulation</strong>. Not real money and cannot be withdrawn.
                    </p>
                </div>

                <button type="submit" class="relative overflow-hidden w-full px-6 py-3.5 bg-gradient-to-r from-cs-orange to-[#ff7337] text-white border-none rounded-xl text-sm font-semibold cursor-pointer transition-all shadow-[0_2px_10px_rgba(238,77,45,0.15)] hover:-translate-y-px hover:shadow-[0_4px_15px_rgba(238,77,45,0.25)] active:translate-y-0" id="btn-register">
                    Register Now
                </button>
            </form>
        </div>

        <div class="text-center mt-6 text-sm text-[#64748b]">
            Already have an account? <a href="{{ route('login') }}" class="no-underline font-semibold transition-colors hover:text-[#ff7337] hover:underline text-cs-orange">Log in here</a>
        </div>
    </div>

    <script>
        // Dynamically update wallet balance preview when country is selected
        document.querySelectorAll('.country-card input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function () {
                const card = this.closest('.country-card');
                const balance = card.dataset.balance;
                document.getElementById('wallet-balance-text').textContent = balance;

                // Animate the preview
                const preview = document.getElementById('wallet-preview');
                preview.style.transform = 'scale(0.97)';
                setTimeout(() => { preview.style.transform = 'scale(1)'; }, 150);
            });
        });
    </script>
</body>
</html>
