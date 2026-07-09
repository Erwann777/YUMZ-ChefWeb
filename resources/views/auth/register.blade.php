<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register — Yumz</title>
    <meta name="description" content="Register in Yumz as Customer or Cooker">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/compiled.css', 'resources/js/app.js'])
    @endif

    @yield('styles')

    {{-- Same keyframe set as the login ticket. Move into tailwind.config.js theme.extend if you want these reusable app-wide. --}}
    <style>
        @keyframes ticketPrint { 0% { transform: translateY(-32px) scaleY(0.94); opacity: 0; } 100% { transform: translateY(0) scaleY(1); opacity: 1; } }
        @keyframes stampIn { 0% { opacity: 0; transform: rotate(-30deg) scale(0.3); } 60% { opacity: 1; transform: rotate(-8deg) scale(1.08); } 100% { opacity: 1; transform: rotate(-11deg) scale(1); } }
        @keyframes stampThunk { 0% { transform: rotate(-11deg) scale(1); } 40% { transform: rotate(-13deg) scale(0.88); } 100% { transform: rotate(-11deg) scale(1); } }
        @keyframes steamRise { 0% { transform: translateY(10px) scaleY(0.8); opacity: 0; } 30% { opacity: 0.55; } 100% { transform: translateY(-70px) scaleY(1.15); opacity: 0; } }
        @keyframes floatSlow { 0%, 100% { transform: translate(0,0); } 50% { transform: translate(14px,-18px); } }
        @keyframes walletPulse { 0% { transform: scale(1); } 40% { transform: scale(0.97); } 100% { transform: scale(1); } }
        @media (prefers-reduced-motion: reduce) {
            *{ animation-duration: 0.001ms !important; animation-iteration-count: 1 !important; transition-duration: 0.001ms !important; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-x-hidden py-10 px-6 bg-[#EEE9E2] text-[#2B241C] font-['Inter',sans-serif]">

    {{-- ambient warm/herb glow blobs, quiet on purpose --}}
    <div class="fixed pointer-events-none w-[400px] h-[400px] rounded-full blur-[90px] opacity-40 bg-[#B23A1A]/10 -top-24 -right-24 animate-[floatSlow_9s_ease-in-out_infinite]"></div>
    <div class="fixed pointer-events-none w-[350px] h-[350px] rounded-full blur-[90px] opacity-40 bg-[#55724F]/10 -bottom-24 -left-24 animate-[floatSlow_11s_ease-in-out_infinite] [animation-delay:-4s]"></div>
    <div class="fixed pointer-events-none w-[220px] h-[220px] rounded-full blur-[90px] opacity-30 bg-[#E3A73F]/10 top-[30%] left-[18%] animate-[floatSlow_13s_ease-in-out_infinite] [animation-delay:-9s]"></div>

    <div class="relative z-10 w-full max-w-[480px] origin-top animate-[ticketPrint_0.7s_cubic-bezier(0.2,0.7,0.25,1)_both]">

        

        {{-- perforated tear edge --}}
        <div aria-hidden="true"
             class="h-3.5 w-full bg-[radial-gradient(circle_at_10px_8px,#EEE9E2_6.5px,transparent_7px)] [background-size:20px_16px] bg-repeat-x [background-position:-4px_0]"></div>

        <div class="bg-[#FAF6EC] border border-black/[0.05] px-8 pt-7 pb-8 shadow-[0_20px_50px_rgba(43,36,28,0.14)]">

            <div class="flex items-baseline justify-between mb-1.5">
                <span class="font-['Fraunces',serif] font-semibold text-2xl text-[#2B241C]">New account</span>
                <span class="font-['JetBrains_Mono',monospace] text-[11px] tracking-wide text-[#5C5346] whitespace-nowrap">NO. {{ str_pad(random_int(1, 999), 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            <span class="font-['JetBrains_Mono',monospace] text-[11px] font-semibold tracking-[0.14em] uppercase text-[#55724F]">First visit &middot; full ticket required</span>

            <div aria-hidden="true"
                 class="h-px w-full my-5 opacity-40 bg-[repeating-linear-gradient(to_right,#5C5346_0,#5C5346_5px,transparent_5px,transparent_10px)]"></div>

            @if ($errors->any())
                <div class="bg-[#B23A1A]/[0.08] border border-[#B23A1A]/25 rounded-lg px-3.5 py-2.5 mb-4 text-sm text-[#8F2C12]">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block font-['JetBrains_Mono',monospace] text-[10.5px] font-semibold tracking-[0.12em] uppercase text-[#5C5346] mb-2">Full Name</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        class="w-full px-3.5 py-2.5 bg-white/60 border rounded-lg text-[14.5px] text-[#2B241C] outline-none transition-colors placeholder:text-[#2B241C]/35
                               {{ $errors->has('name') ? 'border-[#B23A1A] focus:ring-4 focus:ring-[#B23A1A]/10' : 'border-[#2B241C]/15 hover:border-[#2B241C]/25 focus:border-[#55724F] focus:bg-white focus:ring-4 focus:ring-[#55724F]/15' }}"
                        value="{{ old('name') }}"
                        placeholder="Enter full name"
                        required
                        autofocus
                    >
                    @error('name')
                        <div class="text-xs text-[#8F2C12] mt-1.5">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block font-['JetBrains_Mono',monospace] text-[10.5px] font-semibold tracking-[0.12em] uppercase text-[#5C5346] mb-2">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="w-full px-3.5 py-2.5 bg-white/60 border rounded-lg text-[14.5px] text-[#2B241C] outline-none transition-colors placeholder:text-[#2B241C]/35
                               {{ $errors->has('email') ? 'border-[#B23A1A] focus:ring-4 focus:ring-[#B23A1A]/10' : 'border-[#2B241C]/15 hover:border-[#2B241C]/25 focus:border-[#55724F] focus:bg-white focus:ring-4 focus:ring-[#55724F]/15' }}"
                        value="{{ old('email') }}"
                        placeholder="name@email.com"
                        required
                    >
                    @error('email')
                        <div class="text-xs text-[#8F2C12] mt-1.5">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block font-['JetBrains_Mono',monospace] text-[10.5px] font-semibold tracking-[0.12em] uppercase text-[#5C5346] mb-2">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="w-full px-3.5 py-2.5 bg-white/60 border rounded-lg text-[14.5px] text-[#2B241C] outline-none transition-colors placeholder:text-[#2B241C]/35
                               {{ $errors->has('password') ? 'border-[#B23A1A] focus:ring-4 focus:ring-[#B23A1A]/10' : 'border-[#2B241C]/15 hover:border-[#2B241C]/25 focus:border-[#55724F] focus:bg-white focus:ring-4 focus:ring-[#55724F]/15' }}"
                        placeholder="Minimum 8 characters"
                        required
                    >
                    @error('password')
                        <div class="text-xs text-[#8F2C12] mt-1.5">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password_confirmation" class="block font-['JetBrains_Mono',monospace] text-[10.5px] font-semibold tracking-[0.12em] uppercase text-[#5C5346] mb-2">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="w-full px-3.5 py-2.5 bg-white/60 border border-[#2B241C]/15 hover:border-[#2B241C]/25 rounded-lg text-[14.5px] text-[#2B241C] outline-none transition-colors placeholder:text-[#2B241C]/35 focus:border-[#55724F] focus:bg-white focus:ring-4 focus:ring-[#55724F]/15"
                        placeholder="Repeat password"
                        required
                    >
                </div>

                {{-- Role selector --}}
                <label class="block font-['JetBrains_Mono',monospace] text-[10.5px] font-semibold tracking-[0.12em] uppercase text-[#5C5346] mb-2.5">I want to join as</label>
                <div class="grid grid-cols-2 gap-3 mb-5">
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="role" value="customer" {{ old('role', 'customer') === 'customer' ? 'checked' : '' }} required class="absolute opacity-0 pointer-events-none peer">
                        <div class="flex flex-col items-center gap-2 p-4 bg-white/60 border-2 border-[#2B241C]/12 rounded-xl text-center transition-all
                                    group-hover:border-[#2B241C]/25 group-hover:bg-white
                                    peer-checked:border-[#B23A1A] peer-checked:bg-[#B23A1A]/[0.06] peer-checked:shadow-[0_2px_10px_rgba(178,58,26,0.12)]">
                            <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#55724F]/10 text-[#55724F] transition-transform group-hover:scale-105">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="9" cy="20" r="1.4"/><circle cx="17" cy="20" r="1.4"/>
                                    <path d="M2.5 3h2l2.2 11.4a2 2 0 0 0 2 1.6h7.6a2 2 0 0 0 2-1.6L20 7H6"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-[#2B241C]">Customer</span>
                            <span class="text-[0.7rem] text-[#5C5346] leading-snug">Order from the best cookers</span>
                        </div>
                        <span class="absolute top-2 right-2 w-5 h-5 rounded-full flex items-center justify-center opacity-0 scale-50 transition-all bg-gradient-to-br from-[#B23A1A] to-[#8F2C12] peer-checked:opacity-100 peer-checked:scale-100">
                            <svg viewBox="0 0 24 24" class="w-3 h-3 stroke-white stroke-[3] fill-none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </span>
                    </label>

                    <label class="relative cursor-pointer group">
                        <input type="radio" name="role" value="cooker" {{ old('role') === 'cooker' ? 'checked' : '' }} required class="absolute opacity-0 pointer-events-none peer">
                        <div class="flex flex-col items-center gap-2 p-4 bg-white/60 border-2 border-[#2B241C]/12 rounded-xl text-center transition-all
                                    group-hover:border-[#2B241C]/25 group-hover:bg-white
                                    peer-checked:border-[#B23A1A] peer-checked:bg-[#B23A1A]/[0.06] peer-checked:shadow-[0_2px_10px_rgba(178,58,26,0.12)]">
                            <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#B23A1A]/10 text-[#B23A1A] transition-transform group-hover:scale-105">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 19h12M7 19v-6.5M17 19v-6.5"/>
                                    <path d="M5 12.5c0-3 1.6-4.7 2.8-5.6A3 3 0 0 1 12 5a3 3 0 0 1 4.2 1.9c1.2.9 2.8 2.6 2.8 5.6z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-[#2B241C]">Cooker</span>
                            <span class="text-[0.7rem] text-[#5C5346] leading-snug">Sell dishes &amp; earn income</span>
                        </div>
                        <span class="absolute top-2 right-2 w-5 h-5 rounded-full flex items-center justify-center opacity-0 scale-50 transition-all bg-gradient-to-br from-[#B23A1A] to-[#8F2C12] peer-checked:opacity-100 peer-checked:scale-100">
                            <svg viewBox="0 0 24 24" class="w-3 h-3 stroke-white stroke-[3] fill-none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </span>
                    </label>
                </div>
                @error('role')
                    <div class="text-xs text-[#8F2C12] -mt-3 mb-4">{{ $message }}</div>
                @enderror

                {{-- Country selector --}}
                <label class="block font-['JetBrains_Mono',monospace] text-[10.5px] font-semibold tracking-[0.12em] uppercase text-[#5C5346] mb-2.5">Country of Origin</label>
                <div class="grid grid-cols-3 gap-3 mb-4" id="country-selector-group">
                    @foreach([
                        ['code' => 'ID', 'flag' => '🇮🇩', 'name' => 'Indonesia', 'currency' => 'IDR', 'balance' => 'Rp 10.000.000'],
                        ['code' => 'SG', 'flag' => '🇸🇬', 'name' => 'Singapore', 'currency' => 'SGD', 'balance' => 'S$1,000.00'],
                        ['code' => 'MY', 'flag' => '🇲🇾', 'name' => 'Malaysia',  'currency' => 'MYR', 'balance' => 'RM3,000.00'],
                    ] as $c)
                    <label class="relative cursor-pointer group country-card" data-balance="{{ $c['balance'] }}" data-currency="{{ $c['currency'] }}" data-country="{{ $c['code'] }}">
                        <input type="radio" name="country" value="{{ $c['code'] }}" {{ old('country', 'ID') === $c['code'] ? 'checked' : '' }} required class="absolute opacity-0 pointer-events-none peer">
                        <div class="flex flex-col items-center gap-1.5 p-3 bg-white/60 border-2 border-[#2B241C]/12 rounded-xl text-center transition-all
                                    group-hover:border-[#2B241C]/25 group-hover:bg-white
                                    peer-checked:border-[#B23A1A] peer-checked:bg-[#B23A1A]/[0.06] peer-checked:shadow-[0_2px_10px_rgba(178,58,26,0.12)]">
                            <span class="text-2xl leading-none">{{ $c['flag'] }}</span>
                            <span class="text-xs font-bold text-[#2B241C]">{{ $c['name'] }}</span>
                            <span class="font-['JetBrains_Mono',monospace] text-[0.65rem] font-semibold text-[#8F2C12] bg-[#B23A1A]/10 px-1.5 py-0.5 rounded-full">{{ $c['currency'] }}</span>
                        </div>
                        <span class="absolute top-1.5 right-1.5 w-4 h-4 rounded-full flex items-center justify-center opacity-0 scale-50 transition-all bg-gradient-to-br from-[#B23A1A] to-[#8F2C12] peer-checked:opacity-100 peer-checked:scale-100">
                            <svg viewBox="0 0 24 24" class="w-2.5 h-2.5 stroke-white stroke-[3] fill-none"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </span>
                    </label>
                    @endforeach
                </div>
                @error('country')
                    <div class="text-xs text-[#8F2C12] -mt-2 mb-3">{{ $message }}</div>
                @enderror

                {{-- Wallet preview, styled as the ticket's subtotal line --}}
                <div id="wallet-preview" class="mb-5 pt-3.5 border-t border-dashed border-[#5C5346]/35 transition-transform">
                    <div class="flex items-center justify-between font-['JetBrains_Mono',monospace] text-[10.5px] font-semibold tracking-[0.12em] uppercase text-[#5C5346] mb-1.5">
                        <span>Starting wallet balance</span>
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                    </div>
                    <div id="wallet-balance-text" class="font-['JetBrains_Mono',monospace] text-xl font-bold text-[#8F2C12]">Rp 10.000.000</div>
                    <p class="text-[0.7rem] text-[#5C5346]/80 mt-1 leading-snug">
                        Virtual balance for simulation only — not real money, and can't be withdrawn.
                    </p>
                </div>

                <div class="flex items-center gap-3.5">
                    <button type="submit"
                        class="peer flex-1 relative px-5 py-3 rounded-lg text-[14.5px] font-semibold tracking-wide text-[#FDF6EC]
                               bg-gradient-to-b from-[#B23A1A] to-[#8F2C12] shadow-[0_6px_18px_rgba(178,58,26,0.32)]
                               transition-all hover:-translate-y-px hover:shadow-[0_8px_22px_rgba(178,58,26,0.4)] hover:brightness-[1.04] active:translate-y-0
                               focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#E3A73F]"
                        id="btn-register">
                        Register Now
                    </button>

                    
                </div>
            </form>
        </div>

        <div class="text-center mt-6 text-[13.5px] text-[#5C5346]">
            Already have an account?
            <a href="{{ route('login') }}"
               class="font-semibold no-underline text-[#8F2C12] hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#E3A73F] focus-visible:rounded-sm">
                Log in here
            </a>
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
                preview.style.animation = 'none';
                preview.offsetHeight; // reflow to restart the animation
                preview.style.animation = 'walletPulse 0.3s ease-out';
            });
        });
    </script>
</body>
</html>