@extends('layouts.app')
@section('navbar-extra-class', 'nav-hero')
@section('title', 'Yumz — From Their Stove to Your Table')
@section('body-class', 'landing-body')
@section('main-class', '')

@section('content')
{{-- ═══════════════════════════════════════════════════
    SECTION 1: HERO
═══════════════════════════════════════════════════ --}}
<section
    id="hero"
    class="
    relative
    min-h-screen
    flex
    items-center
    overflow-hidden
    "
>

    <!-- Background Mobile -->
    <div
        class="
        absolute
        inset-0
        md:hidden
        bg-cover
        bg-center
        bg-no-repeat
        "
        style="
            background-image: url('{{ asset('images/landing/gemini2.png') }}');
        "
    ></div>

    <!-- Background Tablet & Desktop -->
    <div
        class="
        absolute
        inset-0
        hidden
        md:block
        bg-cover
        bg-center
        bg-no-repeat
        "
        style="
            background-image: url('{{ asset('images/landing/gemini.png') }}');
        "
    ></div>

    <!-- Overlay Gelap -->
    <div
        class="
        absolute
        inset-0
        bg-black/20
        "
    ></div>

    <!-- Gradient untuk membantu readability -->
    <div
        class="
        absolute
        inset-0
        bg-gradient-to-r
        from-black
        via-black/75
        to-black/20
        "
    ></div>

    <!-- Khusus mobile gradient lebih kuat -->
    <div
        class="
        absolute
        inset-0
        md:hidden
        bg-black/20
        "
    ></div>

    <div
        class="
        relative
        z-10
        max-w-7xl
        mx-auto
        px-6
        lg:px-10
        w-full
        "
    >

        <div
            class="
            max-w-3xl
            text-center
            md:text-left
            "
        >

            <div
                class="
                uppercase
                tracking-[0.3em]
                text-[#C67C4E]
                text-xs
                md:text-sm
                mb-6
                "
            >
                Taste of the Neighborhood
            </div>

            <h1
                class="
                font-['Playfair_Display']
                text-white
                font-bold
                leading-[1.05]
                text-4xl
                sm:text-5xl
                md:text-6xl
                lg:text-8xl
                "
            >
                From Their

                <span class="text-[#C67C4E] block">
                    Kitchen
                </span>

                To Your Table
            </h1>

            <p
                class="
                mt-8
                text-gray-200
                text-base
                sm:text-lg
                leading-8
                max-w-xl
                mx-auto
                md:mx-0
                "
            >
                Discover authentic homemade dishes crafted by talented local
                cookers. Experience recipes passed through generations and
                prepared with passion.
            </p>

            <div
                class="
                flex
                gap-4
                mt-10
                flex-wrap
                justify-center
                md:justify-start
                "
            >

                <a
                    href="{{ route('register') }}"
                    class="
                    px-8
                    py-4
                    bg-[#C67C4E]
                    rounded-lg
                    text-white
                    font-semibold
                    hover:bg-[#d78d5d]
                    transition
                    "
                >
                    Browse Dishes
                </a>

                <a
                    href="#cookers"
                    class="
                    px-8
                    py-4
                    border
                    border-white
                    rounded-lg
                    text-white
                    hover:bg-white
                    hover:text-black
                    transition
                    "
                >
                    Meet Cookers
                </a>

            </div>

        </div>

    </div>

</section>
{{-- ═══════════════════════════════════════════════════
    SECTION 2: SLIDESHOW GALLERY
═══════════════════════════════════════════════════ --}}
<section class="bg-[#EDE5DC] py-16 overflow-hidden" id="gallery">
    <div class="text-center mb-10 px-8">
        <div class="text-[0.72rem] font-bold tracking-[0.2em] uppercase text-[#C67C4E] mb-3">Gallery</div>
        <h2 class="font-['Playfair_Display',Georgia,serif] text-[2.5rem] font-bold text-[#2C1810] text-center mb-4 leading-[1.2]">Handcrafted with Love</h2>
        <p class="text-[1rem] text-[#7A6B5D] text-center max-w-[600px] mx-auto leading-[1.7]">A glimpse into the beautiful dishes our cookers create every day</p>
    </div>
    <div class="flex gap-6 [animation:slideScroll_30s_linear_infinite] w-max hover:[animation-play-state:paused]">
        {{-- First set of images --}}
        @if($recipes->count() > 0)
            @foreach($recipes as $recipe)
                @if($recipe->image_path)
                    <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]">
                        <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover">
                    </div>
                @endif
            @endforeach
        @endif
        @if($services->count() > 0)
            @foreach($services as $service)
                @if($service->image_path)
                    <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]">
                        <img src="{{ asset('storage/' . $service->image_path) }}" alt="{{ $service->title }}" class="w-full h-full object-cover">
                    </div>
                @endif
            @endforeach
        @endif
        <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]"><img src="{{ asset('images/landing/food_slide_1.png') }}" alt="Nasi Goreng" class="w-full h-full object-cover"></div>
        <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]"><img src="{{ asset('images/landing/food_slide_2.png') }}" alt="Soto Ayam" class="w-full h-full object-cover"></div>
        <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]"><img src="{{ asset('images/landing/food_slide_3.png') }}" alt="Rendang" class="w-full h-full object-cover"></div>
        <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]"><img src="{{ asset('images/landing/food_slide_4.png') }}" alt="Chocolate Lava Cake" class="w-full h-full object-cover"></div>
        <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]"><img src="{{ asset('images/landing/hero_food.png') }}" alt="Pasta" class="w-full h-full object-cover"></div>
        <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]"><img src="{{ asset('images/landing/food_weekly.png') }}" alt="Steak" class="w-full h-full object-cover"></div>
        {{-- Duplicate set for infinite scroll --}}
        @if($recipes->count() > 0)
            @foreach($recipes as $recipe)
                @if($recipe->image_path)
                    <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]">
                        <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover">
                    </div>
                @endif
            @endforeach
        @endif
        @if($services->count() > 0)
            @foreach($services as $service)
                @if($service->image_path)
                    <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]">
                        <img src="{{ asset('storage/' . $service->image_path) }}" alt="{{ $service->title }}" class="w-full h-full object-cover">
                    </div>
                @endif
            @endforeach
        @endif
        <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]"><img src="{{ asset('images/landing/food_slide_1.png') }}" alt="Nasi Goreng" class="w-full h-full object-cover"></div>
        <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]"><img src="{{ asset('images/landing/food_slide_2.png') }}" alt="Soto Ayam" class="w-full h-full object-cover"></div>
        <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]"><img src="{{ asset('images/landing/food_slide_3.png') }}" alt="Rendang" class="w-full h-full object-cover"></div>
        <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]"><img src="{{ asset('images/landing/food_slide_4.png') }}" alt="Chocolate Lava Cake" class="w-full h-full object-cover"></div>
        <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]"><img src="{{ asset('images/landing/hero_food.png') }}" alt="Pasta" class="w-full h-full object-cover"></div>
        <div class="flex-shrink-0 w-[260px] sm:w-[320px] h-[180px] sm:h-[220px] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(44,24,16,0.1)] transition-transform duration-300 hover:scale-[1.03]"><img src="{{ asset('images/landing/food_weekly.png') }}" alt="Steak" class="w-full h-full object-cover"></div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
    SECTION 3: COOKER PROFILES
═══════════════════════════════════════════════════ --}}
<section class="py-20 px-8 bg-[#F5F0EB]" id="cookers">
    <div class="max-w-[1200px] mx-auto reveal">
        <div class="text-[0.72rem] font-bold tracking-[0.2em] uppercase text-[#C67C4E] mb-3 text-center">Our Cookers</div>
        <h2 class="font-['Playfair_Display',Georgia,serif] text-[2.5rem] font-bold text-[#2C1810] text-center mb-4 leading-[1.2]">Meet the Talented Cooks</h2>
        <p class="text-[1rem] text-[#7A6B5D] text-center max-w-[600px] mx-auto mb-12 leading-[1.7]">Passionate home cooks from your neighborhood, bringing authentic flavors and culinary expertise right to your doorstep.</p>

        @if($cookers->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                @foreach($cookers as $cooker)
                    <div class="bg-white rounded-xl sm:rounded-2xl p-3 sm:p-8 sm:px-6 text-center transition-all duration-300 border border-[rgba(139,69,19,0.08)] shadow-[0_2px_12px_rgba(44,24,16,0.04)] hover:-translate-y-1 hover:shadow-[0_12px_35px_rgba(44,24,16,0.1)] hover:border-[#D4A574] flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 sm:w-[72px] sm:h-[72px] rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center font-['Playfair_Display',Georgia,serif] text-sm sm:text-[1.75rem] font-bold text-white mx-auto mb-2 sm:mb-4 overflow-hidden border border-[#EDE5DA]">
                                @if($cooker->profile_photo_path)
                                    <img src="{{ $cooker->getProfilePhotoUrl() }}" alt="{{ $cooker->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($cooker->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="font-['Playfair_Display',Georgia,serif] text-xs sm:text-[1.1rem] font-semibold text-[#2C1810] mb-1 sm:mb-2 truncate max-w-[120px] sm:max-w-none" title="{{ $cooker->name }}">{{ $cooker->name }}</div>
                            <div class="flex justify-center gap-2 sm:gap-6 mb-2 sm:mb-5">
                                <div>
                                    <div class="font-bold text-[0.8rem] sm:text-[1rem] text-[#2C1810]">{{ $cooker->recipes_count + $cooker->cooking_services_count }}</div>
                                    <div class="text-[0.52rem] sm:text-[0.7rem] text-[#7A6B5D] uppercase tracking-[0.05em] font-semibold">Posts</div>
                                    <div class="text-[0.45rem] sm:text-[0.55rem] text-[#9A7B5A] mt-0.5 font-medium">({{ $cooker->cooking_services_count }} Services, {{ $cooker->recipes_count }} Recipes)</div>
                                </div>
                                <div>
                                    <div class="font-bold text-[0.8rem] sm:text-[1rem] text-[#2C1810]">{{ $cooker->followers_count ?? $cooker->followers()->count() }}</div>
                                    <div class="text-[0.52rem] sm:text-[0.7rem] text-[#7A6B5D] uppercase tracking-[0.05em] font-semibold">Followers</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 flex flex-col gap-2">
                            @auth
                                <a href="{{ route('cookers.show', $cooker) }}" class="inline-flex justify-center px-3 py-1.5 sm:px-5 sm:py-2 text-[0.62rem] sm:text-[0.78rem] font-semibold text-[#C67C4E] border-[1.5px] border-[#C67C4E] rounded-full no-underline transition-all duration-[0.25s] hover:bg-[#C67C4E] hover:text-white">View Profile</a>
                                @if(Auth::id() !== $cooker->id)
                                    <form action="{{ route('cookers.toggle-follow', $cooker) }}" method="POST" class="m-0 w-full flex">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-1.5 sm:px-5 sm:py-2 text-[0.62rem] sm:text-[0.78rem] font-bold rounded-full border border-solid transition-all duration-[0.25s] cursor-pointer
                                            {{ Auth::user()->isFollowing($cooker)
                                                ? 'bg-[#7A6B5D] text-white border-[#7A6B5D] hover:bg-[#5C4D40] hover:border-[#5C4D40]'
                                                : 'bg-white text-[#C67C4E] border-[#C67C4E] hover:bg-[#C67C4E] hover:text-white' }}">
                                            {{ Auth::user()->isFollowing($cooker) ? ' Unfollow' : ' Follow' }}
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-[#C67C4E] text-white font-semibold rounded-full hover:bg-[#d78d5d] transition no-underline text-sm shadow-md">
                        View All Cookers &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-[#C67C4E] text-white font-semibold rounded-full hover:bg-[#d78d5d] transition no-underline text-sm shadow-md">
                        View All Cookers &rarr;
                    </a>
                @endauth
            </div>
        @else
            <div class="text-center py-12 px-8 text-[#7A6B5D]">
                <div class="text-5xl mb-4">👨‍🍳</div>
                <p>Cookers are getting ready! Check back soon for amazing home cooks.</p>
            </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
    SECTION 4: RECIPES & SERVICES MARKETPLACE
    ═══════════════════════════════════════════════════ --}}
<section class="py-20 px-8 bg-white" id="recipes">
    <div class="max-w-[1200px] mx-auto reveal">
        <div class="text-[0.72rem] font-bold tracking-[0.2em] uppercase text-[#C67C4E] mb-3 text-center">Marketplace</div>
        <h2 class="font-['Playfair_Display',Georgia,serif] text-[2.5rem] font-bold text-[#2C1810] text-center mb-4 leading-[1.2]">Discover Foods & Services</h2>
        <p class="text-[1rem] text-[#7A6B5D] text-center max-w-[600px] mx-auto mb-16 leading-[1.7]">Explore delicious homemade foods and authentic cooking services from talented neighborhood cookers.</p>

        <!-- Sub-section 1: Cooking Services -->
        <div class="border-b border-[#E8DDD2] pb-2.5 mb-6 flex items-center justify-between">
            <h3 class="font-['Playfair_Display',Georgia,serif] text-xl font-bold text-[#2C1810] m-0 flex items-center gap-2">🍽️ Cooking Services &amp; Menu Offerings</h3>
            @auth
                <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-[#C67C4E] hover:text-[#d78d5d] transition-colors no-underline flex items-center gap-1">View All &rarr;</a>
            @else
                <a href="{{ route('login') }}" class="text-xs font-semibold text-[#C67C4E] hover:text-[#d78d5d] transition-colors no-underline flex items-center gap-1">View All &rarr;</a>
            @endauth
        </div>
        @if($services->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                @foreach($services as $service)
                    <div class="bg-white rounded-2xl overflow-hidden border border-[rgba(139,69,19,0.06)] shadow-[0_2px_12px_rgba(44,24,16,0.04)] transition-all duration-300 flex flex-col hover:-translate-y-1 hover:shadow-[0_12px_35px_rgba(44,24,16,0.1)]">
                        @if($service->image_path)
                            <img src="{{ asset('storage/' . $service->image_path) }}" alt="{{ $service->title }}" class="w-full h-[200px] object-cover bg-[#EDE5DC]">
                        @else
                            <div class="w-full h-[200px] flex items-center justify-center text-5xl bg-[#EDE5DC]">🍽️</div>
                        @endif
                        <div class="p-5 pt-5 pb-6 flex-1 flex flex-col">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center text-[0.65rem] font-bold text-white overflow-hidden border border-[#E8DDD2]">
                                    @if($service->cooker->profile_photo_path)
                                        <img src="{{ $service->cooker->getProfilePhotoUrl() }}" alt="{{ $service->cooker->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($service->cooker->name, 0, 1)) }}
                                    @endif
                                </div>
                                <span class="text-[0.78rem] text-[#7A6B5D] font-medium">{{ $service->cooker->name }}</span>
                            </div>
                            <div class="font-['Playfair_Display',Georgia,serif] text-[1.15rem] font-semibold text-[#2C1810] mb-1">{{ $service->title }}</div>
                            <div class="text-[0.82rem] text-[#7A6B5D] leading-[1.6] mb-4 line-clamp-2 flex-1">{{ $service->description }}</div>
                            <div class="flex items-center justify-between">
                                <x-price-display :item="$service" :viewerCurrency="$viewerCurrency" size="md" class="font-['Playfair_Display',Georgia,serif]" />
                                @auth
                                    <a href="{{ route('cookers.service', [$service->cooker, $service]) }}" class="inline-flex items-center gap-1 px-[1.15rem] py-2 bg-[#2C1810] text-white text-[0.78rem] font-semibold rounded-full no-underline transition-all duration-[0.25s] hover:bg-[#C67C4E] hover:-translate-y-px hover:shadow-[0_4px_15px_rgba(198,124,78,0.3)]">Preorder →</a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1 px-[1.15rem] py-2 bg-[#2C1810] text-white text-[0.78rem] font-semibold rounded-full no-underline transition-all duration-[0.25s] hover:bg-[#C67C4E] hover:-translate-y-px hover:shadow-[0_4px_15px_rgba(198,124,78,0.3)]">Preorder →</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 px-8 text-[#7A6B5D] bg-[#F5F0EB] rounded-2xl mb-16">
                <div class="text-4xl mb-3">🍽️</div>
                <p class="text-sm font-semibold">No cooking services registered yet.</p>
            </div>
        @endif

        <!-- Sub-section 2: Secret Recipes -->
        <div class="border-b border-[#E8DDD2] pb-2.5 mb-6 flex items-center justify-between">
            <h3 class="font-['Playfair_Display',Georgia,serif] text-xl font-bold text-[#2C1810] m-0 flex items-center gap-2">🥘 Secret Culinary Recipes</h3>
            @auth
                <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-[#C67C4E] hover:text-[#d78d5d] transition-colors no-underline flex items-center gap-1">View All &rarr;</a>
            @else
                <a href="{{ route('login') }}" class="text-xs font-semibold text-[#C67C4E] hover:text-[#d78d5d] transition-colors no-underline flex items-center gap-1">View All &rarr;</a>
            @endauth
        </div>
        @if($recipes->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                  @foreach($recipes as $recipe)
                      <div class="bg-white rounded-2xl overflow-hidden border border-[rgba(139,69,19,0.06)] shadow-[0_2px_12px_rgba(44,24,16,0.04)] transition-all duration-300 flex flex-col hover:-translate-y-1 hover:shadow-[0_12px_35px_rgba(44,24,16,0.1)]">
                          @if($recipe->image_path)
                              <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->title }}" class="w-full h-[200px] object-cover bg-[#EDE5DC]">
                          @else
                              <div class="w-full h-[200px] flex items-center justify-center text-5xl bg-[#EDE5DC]">🥘</div>
                          @endif
                          <div class="p-5 pt-5 pb-6 flex-1 flex flex-col">
                              <div class="flex items-center gap-2 mb-3">
                                  <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center text-[0.65rem] font-bold text-white overflow-hidden border border-[#E8DDD2]">
                                      @if($recipe->cooker->profile_photo_path)
                                          <img src="{{ $recipe->cooker->getProfilePhotoUrl() }}" alt="{{ $recipe->cooker->name }}" class="w-full h-full object-cover">
                                      @else
                                          {{ strtoupper(substr($recipe->cooker->name, 0, 1)) }}
                                      @endif
                                  </div>
                                  <span class="text-[0.78rem] text-[#7A6B5D] font-medium">{{ $recipe->cooker->name }}</span>
                              </div>
                              <div class="font-['Playfair_Display',Georgia,serif] text-[1.15rem] font-semibold text-[#2C1810] mb-1">{{ $recipe->title }}</div>
                              <div class="text-[0.82rem] text-[#7A6B5D] leading-[1.6] mb-4 line-clamp-2 flex-1">{{ $recipe->description }}</div>
                              <div class="flex items-center justify-between">
                                  <x-price-display :item="$recipe" :viewerCurrency="$viewerCurrency" size="md" class="font-['Playfair_Display',Georgia,serif]" />
                                  @auth
                                      <a href="{{ route('cookers.recipe', [$recipe->cooker, $recipe]) }}" class="inline-flex items-center gap-1 px-[1.15rem] py-2 bg-[#2C1810] text-white text-[0.78rem] font-semibold rounded-full no-underline transition-all duration-[0.25s] hover:bg-[#C67C4E] hover:-translate-y-px hover:shadow-[0_4px_15px_rgba(198,124,78,0.3)]">Unlock Recipe →</a>
                                  @else
                                      <a href="{{ route('login') }}" class="inline-flex items-center gap-1 px-[1.15rem] py-2 bg-[#2C1810] text-white text-[0.78rem] font-semibold rounded-full no-underline transition-all duration-[0.25s] hover:bg-[#C67C4E] hover:-translate-y-px hover:shadow-[0_4px_15px_rgba(198,124,78,0.3)]">Unlock Recipe →</a>
                                  @endauth
                              </div>
                          </div>
                      </div>
                  @endforeach
            </div>
        @else
            <div class="text-center py-12 px-8 text-[#7A6B5D] bg-[#F5F0EB] rounded-2xl">
                <div class="text-4xl mb-3">🥘</div>
                <p class="text-sm font-semibold">New secret recipes will be coming soon from our cookers!</p>
            </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
    SECTION 5: RECIPE OF THE WEEK
═══════════════════════════════════════════════════ --}}
<section class="py-20 px-8 bg-[#2C1810] text-white" id="recipe-of-the-week">
    <div class="max-w-[1200px] mx-auto reveal">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center">
            <div class="rounded-[20px] overflow-hidden aspect-[4/3]">
                @if($featuredRecipe && $featuredRecipe->image_path)
                    <img src="{{ asset('storage/' . $featuredRecipe->image_path) }}" alt="{{ $featuredRecipe->title }}" class="w-full h-full object-cover">
                @else
                    <img src="{{ asset('images/landing/food_weekly.png') }}" alt="Recipe of the Week" class="w-full h-full object-cover">
                @endif
            </div>
            <div>
                <div class="text-[0.72rem] font-bold tracking-[0.2em] uppercase text-[#D4A574] mb-4">⭐ Food of the Week</div>
                @if($featuredRecipe)
                    <h2 class="font-['Playfair_Display',Georgia,serif] text-[2rem] lg:text-[2.5rem] font-bold text-white leading-[1.2] mb-5">{{ $featuredRecipe->title }}</h2>
                    <p class="text-[1rem] text-white/70 leading-[1.8] mb-8">
                        {{ $featuredRecipe->description }}
                    </p>
                    <div class="flex items-center gap-[0.85rem] pt-6 border-t border-[#EDE5DA]">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#C67C4E] to-[#D4A574] flex items-center justify-center font-['Playfair_Display',Georgia,serif] text-[1.25rem] font-bold text-white overflow-hidden border border-white/20">
                            @if($featuredRecipe->cooker->profile_photo_path)
                                <img src="{{ $featuredRecipe->cooker->getProfilePhotoUrl() }}" alt="{{ $featuredRecipe->cooker->name }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($featuredRecipe->cooker->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <strong class="block text-[0.95rem] text-white">{{ $featuredRecipe->cooker->name }}</strong>
                            <span class="text-[0.78rem] text-white/55">Cooker since {{ $featuredRecipe->cooker->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                @else
                    <h2 class="font-['Playfair_Display',Georgia,serif] text-[2rem] lg:text-[2.5rem] font-bold text-white leading-[1.2] mb-5">Coming Soon</h2>
                    <p class="text-[1rem] text-white/70 leading-[1.8] mb-8">
                        Our cookers are preparing something extraordinary. Every week, we highlight a special recipe that tells a story — of heritage, creativity, and the love poured into every dish. Stay tuned for our first feature!
                    </p>
                    <div class="flex items-center gap-[0.85rem] pt-6 border-t border-[#EDE5DA]">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#C67C4E] to-[#D4A574] flex items-center justify-center text-[1.25rem]">🍳</div>
                        <div>
                            <strong class="block text-[0.95rem] text-white">CookSpace Team</strong>
                            <span class="text-[0.78rem] text-white/55">Curating the best for you</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
    SECTION 6: COOKER STORIES
═══════════════════════════════════════════════════ --}}
<section class="py-20 px-8 bg-[#F5F0EB]" id="stories">
    <div class="max-w-[1200px] mx-auto reveal">
        <div class="text-[0.72rem] font-bold tracking-[0.2em] uppercase text-[#C67C4E] mb-3 text-center">Journal</div>
        <h2 class="font-['Playfair_Display',Georgia,serif] text-[2.5rem] font-bold text-[#2C1810] text-center mb-4 leading-[1.2]">Stories from the Kitchen</h2>
        <p class="text-[1rem] text-[#7A6B5D] text-center max-w-[600px] mx-auto mb-12 leading-[1.7]">Behind every dish is a story. Discover the passion, culture, and memories that inspire our cookers' creations.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @if($recipes->count() > 0)
                @foreach($recipes->take(3) as $recipe)
                    <div class="bg-white rounded-2xl overflow-hidden border border-[rgba(139,69,19,0.06)] shadow-[0_2px_12px_rgba(44,24,16,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_35px_rgba(44,24,16,0.1)]">
                        @if($recipe->image_path)
                            <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->title }}" class="w-full h-[200px] object-cover">
                        @else
                            <div class="w-full h-[200px] flex items-center justify-center text-5xl bg-[#EDE5DC]">📖</div>
                        @endif
                        <div class="p-6">
                            <div class="font-['Playfair_Display',Georgia,serif] text-[1.1rem] font-semibold text-[#2C1810] mb-2">The Story Behind "{{ $recipe->title }}"</div>
                            <div class="text-[0.85rem] text-[#7A6B5D] leading-[1.7] mb-4 line-clamp-3">{{ $recipe->description }}</div>
                            <div class="flex items-center gap-[0.6rem] text-[0.8rem] text-[#7A6B5D]">
                                <div class="w-[30px] h-[30px] rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center text-[0.6rem] font-bold text-white overflow-hidden border border-[#E8DDD2]">
                                    @if($recipe->cooker->profile_photo_path)
                                        <img src="{{ $recipe->cooker->getProfilePhotoUrl() }}" alt="{{ $recipe->cooker->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($recipe->cooker->name, 0, 1)) }}
                                    @endif
                                </div>
                                <span>by <strong>{{ $recipe->cooker->name }}</strong></span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Fallback story cards --}}
                <div class="bg-white rounded-2xl overflow-hidden border border-[rgba(139,69,19,0.06)] shadow-[0_2px_12px_rgba(44,24,16,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_35px_rgba(44,24,16,0.1)]">
                    <img src="{{ asset('images/landing/food_slide_1.png') }}" alt="Kitchen Story" class="w-full h-[200px] object-cover">
                    <div class="p-6">
                        <div class="font-['Playfair_Display',Georgia,serif] text-[1.1rem] font-semibold text-[#2C1810] mb-2">The Art of Nasi Goreng</div>
                        <div class="text-[0.85rem] text-[#7A6B5D] leading-[1.7] mb-4 line-clamp-3">Every family has their own secret recipe for nasi goreng. Mine comes from my grandmother, who learned it from a street vendor in Yogyakarta during the 1960s. The secret lies in the kecap manis timing.</div>
                        <div class="flex items-center gap-[0.6rem] text-[0.8rem] text-[#7A6B5D]">
                            <div class="w-[30px] h-[30px] rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center text-[0.6rem] font-bold text-white">N</div>
                            <span>by <strong>Nusa Kitchen</strong></span>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl overflow-hidden border border-[rgba(139,69,19,0.06)] shadow-[0_2px_12px_rgba(44,24,16,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_35px_rgba(44,24,16,0.1)]">
                    <img src="{{ asset('images/landing/food_slide_3.png') }}" alt="Kitchen Story" class="w-full h-[200px] object-cover">
                    <div class="p-6">
                        <div class="font-['Playfair_Display',Georgia,serif] text-[1.1rem] font-semibold text-[#2C1810] mb-2">Rendang: A 4-Hour Labor of Love</div>
                        <div class="text-[0.85rem] text-[#7A6B5D] leading-[1.7] mb-4 line-clamp-3">There are no shortcuts to great rendang. My mother always said patience is the most important ingredient. The slow caramelization of coconut milk is what gives it that deep, complex flavor.</div>
                        <div class="flex items-center gap-[0.6rem] text-[0.8rem] text-[#7A6B5D]">
                            <div class="w-[30px] h-[30px] rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center text-[0.6rem] font-bold text-white">R</div>
                            <span>by <strong>Ranah Minang</strong></span>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl overflow-hidden border border-[rgba(139,69,19,0.06)] shadow-[0_2px_12px_rgba(44,24,16,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_35px_rgba(44,24,16,0.1)]">
                    <img src="{{ asset('images/landing/food_slide_4.png') }}" alt="Kitchen Story" class="w-full h-[200px] object-cover">
                    <div class="p-6">
                        <div class="font-['Playfair_Display',Georgia,serif] text-[1.1rem] font-semibold text-[#2C1810] mb-2">When Chocolate Meets Memories</div>
                        <div class="text-[0.85rem] text-[#7A6B5D] leading-[1.7] mb-4 line-clamp-3">I started baking to cope with homesickness. This lava cake recipe reminds me of the café my mother used to take me to every Sunday. Now I share that warmth with every customer.</div>
                        <div class="flex items-center gap-[0.6rem] text-[0.8rem] text-[#7A6B5D]">
                            <div class="w-[30px] h-[30px] rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center text-[0.6rem] font-bold text-white">S</div>
                            <span>by <strong>Sweet Haven</strong></span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
    SECTION 7: SUPPORT & FEEDBACK
═══════════════════════════════════════════════════ --}}
<section class="py-20 px-8 bg-gradient-to-br from-[#F5F0EB] to-[#EDE5DC]" id="feedback">
    <div class="max-w-[1200px] mx-auto reveal">
        <div class="text-[0.72rem] font-bold tracking-[0.2em] uppercase text-[#C67C4E] mb-3 text-center">Get in Touch</div>
        <h2 class="font-['Playfair_Display',Georgia,serif] text-[2.5rem] font-bold text-[#2C1810] text-center mb-4 leading-[1.2]">Support & Feedback</h2>
        <p class="text-[1rem] text-[#7A6B5D] text-center max-w-[600px] mx-auto mb-12 leading-[1.7]">Have questions, suggestions, or need help? We'd love to hear from you. Send us a message and our team will respond promptly.</p>

        <form class="max-w-[600px] mx-auto flex flex-col gap-5" onsubmit="event.preventDefault(); handleFeedbackSubmit(this);">
            @csrf
            <input type="text" name="name" placeholder="Your name" required
                class="w-full px-5 py-[0.9rem] border-[1.5px] border-[rgba(139,69,19,0.15)] rounded-xl text-[0.92rem] font-[inherit] bg-white text-[#3D2B1F] transition-all duration-[0.25s] outline-none focus:border-[#C67C4E] focus:shadow-[0_0_0_3px_rgba(198,124,78,0.1)] box-border">
            <input type="email" name="email" placeholder="Your email address" required
                class="w-full px-5 py-[0.9rem] border-[1.5px] border-[rgba(139,69,19,0.15)] rounded-xl text-[0.92rem] font-[inherit] bg-white text-[#3D2B1F] transition-all duration-[0.25s] outline-none focus:border-[#C67C4E] focus:shadow-[0_0_0_3px_rgba(198,124,78,0.1)] box-border">
            <textarea name="message" placeholder="Write your message, feedback, or question here..." required
                class="w-full px-5 py-[0.9rem] border-[1.5px] border-[rgba(139,69,19,0.15)] rounded-xl text-[0.92rem] font-[inherit] bg-white text-[#3D2B1F] transition-all duration-[0.25s] outline-none focus:border-[#C67C4E] focus:shadow-[0_0_0_3px_rgba(198,124,78,0.1)] box-border resize-y min-h-[140px]"></textarea>
            <button type="submit" id="feedback-submit-btn"
                class="self-center inline-flex items-center justify-center gap-2 px-10 py-[0.9rem] bg-[#2C1810] text-white text-[0.92rem] font-semibold rounded-full border-none cursor-pointer transition-all duration-300 hover:bg-[#C67C4E] hover:-translate-y-0.5 hover:shadow-[0_8px_25px_rgba(198,124,78,0.3)]">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                Send to Admin
            </button>
        </form>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
    SECTION 8: HOW TO BECOME A COOKER
═══════════════════════════════════════════════════ --}}
<section class="py-20 px-8 bg-white" id="become-cooker">
    <div class="max-w-[1200px] mx-auto reveal">
        <div class="text-[0.72rem] font-bold tracking-[0.2em] uppercase text-[#C67C4E] mb-3 text-center">For Cookers</div>
        <h2 class="font-['Playfair_Display',Georgia,serif] text-[2.5rem] font-bold text-[#2C1810] text-center mb-4 leading-[1.2]">How to Become a Cooker</h2>
        <p class="text-[1rem] text-[#7A6B5D] text-center max-w-[600px] mx-auto mb-12 leading-[1.7]">Share your culinary talent with the world and earn from your passion. Here's how to get started in just 4 simple steps.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 relative">
            <div class="absolute top-[26px] left-[15%] right-[15%] h-[2px] bg-gradient-to-r from-[#D4A574] via-[#C67C4E] to-[#D4A574] opacity-30 z-0 hidden lg:block"></div>
            <div class="text-center p-6 relative">
                <div class="w-[52px] h-[52px] rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center font-['Playfair_Display',Georgia,serif] text-[1.25rem] font-bold text-white mx-auto mb-5">1</div>
                <div class="font-['Playfair_Display',Georgia,serif] text-[1.05rem] font-semibold text-[#2C1810] mb-2">Create Your Account</div>
                <div class="text-[0.85rem] text-[#7A6B5D] leading-[1.6]">Register for free and set up your cooker profile with your specialties and cooking story.</div>
            </div>
            <div class="text-center p-6 relative">
                <div class="w-[52px] h-[52px] rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center font-['Playfair_Display',Georgia,serif] text-[1.25rem] font-bold text-white mx-auto mb-5">2</div>
                <div class="font-['Playfair_Display',Georgia,serif] text-[1.05rem] font-semibold text-[#2C1810] mb-2">Add Your Recipes</div>
                <div class="text-[0.85rem] text-[#7A6B5D] leading-[1.6]">Upload your best recipes with photos, ingredients, and secret steps. Set your price for premium access.</div>
            </div>
            <div class="text-center p-6 relative">
                <div class="w-[52px] h-[52px] rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center font-['Playfair_Display',Georgia,serif] text-[1.25rem] font-bold text-white mx-auto mb-5">3</div>
                <div class="font-['Playfair_Display',Georgia,serif] text-[1.05rem] font-semibold text-[#2C1810] mb-2">Offer Cooking Services</div>
                <div class="text-[0.85rem] text-[#7A6B5D] leading-[1.6]">List your cooking services — from private dinners to catering events. Set your own prices and availability.</div>
            </div>
            <div class="text-center p-6 relative">
                <div class="w-[52px] h-[52px] rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center font-['Playfair_Display',Georgia,serif] text-[1.25rem] font-bold text-white mx-auto mb-5">4</div>
                <div class="font-['Playfair_Display',Georgia,serif] text-[1.05rem] font-semibold text-[#2C1810] mb-2">Start Earning</div>
                <div class="text-[0.85rem] text-[#7A6B5D] leading-[1.6]">Receive orders, cook with passion, and build your reputation. Grow your culinary business from home.</div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
    SECTION 9: HOW TO BECOME A CUSTOMER
═══════════════════════════════════════════════════ --}}
<section class="py-20 px-8 bg-[#F5F0EB]" id="become-customer">
    <div class="max-w-[1200px] mx-auto reveal">
        <div class="text-[0.72rem] font-bold tracking-[0.2em] uppercase text-[#C67C4E] mb-3 text-center">For Customers</div>
        <h2 class="font-['Playfair_Display',Georgia,serif] text-[2.5rem] font-bold text-[#2C1810] text-center mb-4 leading-[1.2]">How to Order</h2>
        <p class="text-[1rem] text-[#7A6B5D] text-center max-w-[600px] mx-auto mb-12 leading-[1.7]">Finding delicious food and authentic recipes has never been easier. Start your culinary journey in 3 simple steps.</p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 relative">
            <div class="absolute top-[26px] left-[15%] right-[15%] h-[2px] bg-gradient-to-r from-[#D4A574] via-[#C67C4E] to-[#D4A574] opacity-30 z-0 hidden sm:block"></div>
            <div class="text-center p-6 relative">
                <div class="w-[52px] h-[52px] rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center font-['Playfair_Display',Georgia,serif] text-[1.25rem] font-bold text-white mx-auto mb-5">1</div>
                <div class="font-['Playfair_Display',Georgia,serif] text-[1.05rem] font-semibold text-[#2C1810] mb-2">Sign Up & Browse</div>
                <div class="text-[0.85rem] text-[#7A6B5D] leading-[1.6]">Create your free account and explore a curated marketplace of home cooks, recipes, and cooking services near you.</div>
            </div>
            <div class="text-center p-6 relative">
                <div class="w-[52px] h-[52px] rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center font-['Playfair_Display',Georgia,serif] text-[1.25rem] font-bold text-white mx-auto mb-5">2</div>
                <div class="font-['Playfair_Display',Georgia,serif] text-[1.05rem] font-semibold text-[#2C1810] mb-2">Choose & Order</div>
                <div class="text-[0.85rem] text-[#7A6B5D] leading-[1.6]">Pick a recipe to unlock its secret steps, or book a cooker's services for your next event. Pay securely through the platform.</div>
            </div>
            <div class="text-center p-6 relative">
                <div class="w-[52px] h-[52px] rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center font-['Playfair_Display',Georgia,serif] text-[1.25rem] font-bold text-white mx-auto mb-5">3</div>
                <div class="font-['Playfair_Display',Georgia,serif] text-[1.05rem] font-semibold text-[#2C1810] mb-2">Enjoy the Experience</div>
                <div class="text-[0.85rem] text-[#7A6B5D] leading-[1.6]">Master new recipes in your own kitchen or sit back while a talented cooker prepares an unforgettable meal for you.</div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════
    SECTION 10: FOOTER
═══════════════════════════════════════════════════ --}}
<footer class="bg-[#2C1810] text-white/75 pt-10 pb-6 px-6 sm:px-8" id="footer">
    <div class="max-w-[1200px] mx-auto">

        {{-- Grid utama --}}
        <div class="grid grid-cols-1 sm:grid-cols-[1.8fr_1fr_1fr_1fr] gap-6 sm:gap-10 mb-8">

            {{-- Brand column: di mobile lebih ringkas --}}
            <div>
                <div class="flex ">
                   <img src="{{ asset('images/landing/logo_web1.png') }}" alt="YUMZ Logo" class="h-28 w-28">
                </div>
                <p class="text-[0.82rem] leading-[1.6] text-white/50 mb-4 max-w-[340px]">
                    Connecting home cooks with food lovers. Discover recipes, book services, and taste the neighborhood.
                </p>
                <div class="flex gap-2">
                    <a href="#" class="w-8 h-8 rounded-full bg-[#F5EFE6] flex items-center justify-center text-white/60 no-underline transition-all duration-[0.25s] hover:bg-[#C67C4E] hover:text-white hover:-translate-y-0.5" aria-label="Instagram">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-[#F5EFE6] flex items-center justify-center text-white/60 no-underline transition-all duration-[0.25s] hover:bg-[#C67C4E] hover:text-white hover:-translate-y-0.5" aria-label="Twitter">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-[#F5EFE6] flex items-center justify-center text-white/60 no-underline transition-all duration-[0.25s] hover:bg-[#C67C4E] hover:text-white hover:-translate-y-0.5" aria-label="Facebook">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-[#F5EFE6] flex items-center justify-center text-white/60 no-underline transition-all duration-[0.25s] hover:bg-[#C67C4E] hover:text-white hover:-translate-y-0.5" aria-label="YouTube">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19.1c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.43z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                    </a>
                </div>
            </div>

            {{-- 3 kolom link: di mobile jadi 2 kolom grid (Quick Links + Community | Contact) --}}
            <div class="col-span-1 sm:col-span-3 grid grid-cols-2 sm:grid-cols-3 gap-6 sm:gap-10">

                {{-- Quick Links --}}
                <div>
                    <div class="font-['Playfair_Display',Georgia,serif] text-[0.9rem] font-semibold text-white mb-3">Quick Links</div>
                    <ul class="list-none p-0 m-0 flex flex-col gap-[0.45rem]">
                        <li><a href="#hero"     class="text-[0.8rem] text-white/50 no-underline transition-colors duration-200 hover:text-[#D4A574]">Home</a></li>
                        <li><a href="#recipes"  class="text-[0.8rem] text-white/50 no-underline transition-colors duration-200 hover:text-[#D4A574]">Recipes</a></li>
                        <li><a href="#cookers"  class="text-[0.8rem] text-white/50 no-underline transition-colors duration-200 hover:text-[#D4A574]">Cookers</a></li>
                        <li><a href="#stories"  class="text-[0.8rem] text-white/50 no-underline transition-colors duration-200 hover:text-[#D4A574]">Journal</a></li>
                        <li><a href="#feedback" class="text-[0.8rem] text-white/50 no-underline transition-colors duration-200 hover:text-[#D4A574]">Contact</a></li>
                    </ul>
                </div>

                {{-- Community --}}
                <div>
                    <div class="font-['Playfair_Display',Georgia,serif] text-[0.9rem] font-semibold text-white mb-3">Community</div>
                    <ul class="list-none p-0 m-0 flex flex-col gap-[0.45rem]">
                        <li><a href="#become-cooker"   class="text-[0.8rem] text-white/50 no-underline transition-colors duration-200 hover:text-[#D4A574]">Become a Cooker</a></li>
                        <li><a href="#become-customer" class="text-[0.8rem] text-white/50 no-underline transition-colors duration-200 hover:text-[#D4A574]">How to Order</a></li>
                        <li><a href="#feedback"        class="text-[0.8rem] text-white/50 no-underline transition-colors duration-200 hover:text-[#D4A574]">Support Center</a></li>
                        <li><a href="#"               class="text-[0.8rem] text-white/50 no-underline transition-colors duration-200 hover:text-[#D4A574]">Privacy Policy</a></li>
                        <li><a href="#"               class="text-[0.8rem] text-white/50 no-underline transition-colors duration-200 hover:text-[#D4A574]">Terms of Service</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="col-span-2 sm:col-span-1">
                    <div class="font-['Playfair_Display',Georgia,serif] text-[0.9rem] font-semibold text-white mb-3">Contact Us</div>
                    <ul class="list-none p-0 m-0 flex flex-col gap-[0.45rem]">
                        <li><a href="mailto:erwan@gmail.com" class="text-[0.8rem] text-white/50 no-underline transition-colors duration-200 hover:text-[#D4A574]">yumz@gmail.com</a></li>
                        <li><a href="tel:+6281234567890"        class="text-[0.8rem] text-white/50 no-underline transition-colors duration-200 hover:text-[#D4A574]">+62 812-3456-7890</a></li>
                        <li><a href="#"                         class="text-[0.8rem] text-white/50 no-underline transition-colors duration-200 hover:text-[#D4A574]">Jakarta, Indonesia</a></li>
                    </ul>
                </div>

            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-white/[0.08] pt-4 flex flex-col sm:flex-row justify-between items-center gap-1 text-center text-[0.75rem] text-white/35">
            <span>&copy; {{ date('Y') }} YUMZ. All rights reserved.</span>
        </div>
    </div>
</footer>
@endsection

@section('scripts')
<script>
    // Scroll-triggered reveal animations
    document.addEventListener('DOMContentLoaded', function() {
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -50px 0px' });

        reveals.forEach(el => observer.observe(el));
    });

    // Feedback form handler
    function handleFeedbackSubmit(form) {
        const btn = document.getElementById('feedback-submit-btn');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '✓ Message Sent!';
        btn.style.background = '#C67C4E';
        btn.disabled = true;
        form.reset();
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.style.background = '';
            btn.disabled = false;
        }, 3000);
    }
</script>
@endsection
