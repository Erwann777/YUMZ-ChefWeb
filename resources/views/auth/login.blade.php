<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login — Yumz</title>
    <meta name="description" content="Log in to Yumz to enjoy the best culinary services">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/compiled.css', 'resources/js/app.js'])
    @endif

    @yield('styles')

    {{-- Only keyframes live here — Tailwind utilities below reference them via animate-[...] arbitrary values.
         Feel free to move these into tailwind.config.js under theme.extend.keyframes/animation instead. --}}
    <style>
        @keyframes ticketPrint { 0% { transform: translateY(-32px) scaleY(0.94); opacity: 0; } 100% { transform: translateY(0) scaleY(1); opacity: 1; } }
        @keyframes stampIn { 0% { opacity: 0; transform: rotate(-30deg) scale(0.3); } 60% { opacity: 1; transform: rotate(-8deg) scale(1.08); } 100% { opacity: 1; transform: rotate(-11deg) scale(1); } }
        @keyframes stampThunk { 0% { transform: rotate(-11deg) scale(1); } 40% { transform: rotate(-13deg) scale(0.88); } 100% { transform: rotate(-11deg) scale(1); } }
        @keyframes steamRise { 0% { transform: translateY(10px) scaleY(0.8); opacity: 0; } 30% { opacity: 0.55; } 100% { transform: translateY(-70px) scaleY(1.15); opacity: 0; } }
        @keyframes floatSlow { 0%, 100% { transform: translate(0,0); } 50% { transform: translate(14px,-18px); } }
        @media (prefers-reduced-motion: reduce) {
            *{ animation-duration: 0.001ms !important; animation-iteration-count: 1 !important; transition-duration: 0.001ms !important; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden p-6 bg-[#EEE9E2] text-[#2B241C] font-['Inter',sans-serif]">

    {{-- ambient warm/herb glow blobs, quiet on purpose --}}
    <div class="fixed pointer-events-none w-[420px] h-[420px] rounded-full blur-[90px] opacity-40 bg-[#B23A1A]/10 -top-24 -left-24 animate-[floatSlow_9s_ease-in-out_infinite]"></div>
    <div class="fixed pointer-events-none w-[380px] h-[380px] rounded-full blur-[90px] opacity-40 bg-[#55724F]/10 -bottom-24 -right-16 animate-[floatSlow_11s_ease-in-out_infinite] [animation-delay:-4s]"></div>

    {{-- faint rising steam, corner detail only --}}
    <div class="absolute w-[2px] h-14 rounded-full blur-[2px] bg-gradient-to-t from-transparent via-[#2B241C]/10 to-transparent left-[12%] bottom-[20%] animate-[steamRise_6s_ease-in-out_infinite]"></div>
    <div class="absolute w-[2px] h-10 rounded-full blur-[2px] bg-gradient-to-t from-transparent via-[#2B241C]/10 to-transparent left-[15%] bottom-[22%] animate-[steamRise_6s_ease-in-out_infinite] [animation-delay:2s]"></div>
    <div class="absolute w-[2px] h-12 rounded-full blur-[2px] bg-gradient-to-t from-transparent via-[#2B241C]/10 to-transparent right-[14%] top-[18%] animate-[steamRise_6s_ease-in-out_infinite] [animation-delay:3.2s]"></div>

    <div class="relative z-10 w-full max-w-[420px] origin-top animate-[ticketPrint_0.7s_cubic-bezier(0.2,0.7,0.25,1)_both]">

        

        {{-- perforated tear edge: dots cut to the page background color so it reads as punched paper --}}
        <div aria-hidden="true"
             class="h-3.5 w-full bg-[radial-gradient(circle_at_10px_8px,#EEE9E2_6.5px,transparent_7px)] [background-size:20px_16px] bg-repeat-x [background-position:-4px_0]"></div>

        <div class="bg-[#FAF6EC] border border-black/[0.05] px-8 pt-7 pb-8 shadow-[0_20px_50px_rgba(43,36,28,0.14)]">

            <div class="flex items-baseline justify-between mb-1.5">
                <span class="font-['Fraunces',serif] font-semibold text-2xl text-[#2B241C]">Welcome back</span>
                <span class="font-['JetBrains_Mono',monospace] text-[11px] tracking-wide text-[#5C5346] whitespace-nowrap">NO. {{ str_pad(random_int(1, 999), 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            <span class="font-['JetBrains_Mono',monospace] text-[11px] font-semibold tracking-[0.14em] uppercase text-[#55724F]">Table for one &middot; sign in below</span>

            <div aria-hidden="true"
                 class="h-px w-full my-5 opacity-40 bg-[repeating-linear-gradient(to_right,#5C5346_0,#5C5346_5px,transparent_5px,transparent_10px)]"></div>

            @if ($errors->any())
                <div class="bg-[#B23A1A]/[0.08] border border-[#B23A1A]/25 rounded-lg px-3.5 py-2.5 mb-4 text-sm text-[#8F2C12]">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf

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
                        autofocus
                    >
                </div>

                <div class="mb-4">
                    <label for="password" class="block font-['JetBrains_Mono',monospace] text-[10.5px] font-semibold tracking-[0.12em] uppercase text-[#5C5346] mb-2">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="w-full px-3.5 py-2.5 bg-white/60 border rounded-lg text-[14.5px] text-[#2B241C] outline-none transition-colors placeholder:text-[#2B241C]/35
                               {{ $errors->has('password') ? 'border-[#B23A1A] focus:ring-4 focus:ring-[#B23A1A]/10' : 'border-[#2B241C]/15 hover:border-[#2B241C]/25 focus:border-[#55724F] focus:bg-white focus:ring-4 focus:ring-[#55724F]/15' }}"
                        placeholder="Enter password"
                        required
                    >
                </div>

                <div class="flex items-center gap-2.5 mb-6">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 accent-[#55724F] cursor-pointer" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="text-[13px] text-[#5C5346] cursor-pointer select-none">Remember me</label>
                </div>

                <div class="flex items-center gap-3.5">
                    <button type="submit"
                        class="peer flex-1 relative px-5 py-3 rounded-lg text-[14.5px] font-semibold tracking-wide text-[#FDF6EC]
                               bg-gradient-to-b from-[#B23A1A] to-[#8F2C12] shadow-[0_6px_18px_rgba(178,58,26,0.32)]
                               transition-all hover:-translate-y-px hover:shadow-[0_8px_22px_rgba(178,58,26,0.4)] hover:brightness-[1.04] active:translate-y-0
                               focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#E3A73F]"
                        id="btn-login">
                        Sign In
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center mt-6 text-[13.5px] text-[#5C5346]">
            Don't have an account?
            <a href="{{ route('register') }}"
               class="font-semibold no-underline text-[#8F2C12] hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#E3A73F] focus-visible:rounded-sm">
                Register now
            </a>
        </div>
    </div>
</body>
</html>