{{--
    Price Display Component
    Props:
      $item           — Eloquent model with ->price and ->currency
      $viewerCurrency — the logged-in user currency code (IDR / SGD / MYR)
      $cs             — CurrencyService instance
      $size           — 'sm' | 'md' | 'lg' (optional, default 'md')
      $class          — extra CSS classes on outer wrapper (optional)
      $color          — custom text color class (optional, default 'text-white')
--}}
@php
    $currency   = strtoupper($item->currency ?? 'IDR');
    $viewer     = strtoupper($viewerCurrency ?? 'IDR');
    $amount     = (float) ($item->price ?? 0);
    $size       = $size ?? 'md';
    $extraClass = $class ?? '';
    
    // Default color is the warm brand accent orange '#C67C4E'
    $textColor  = $color ?? 'text-[#C67C4E]';

    $block = $cs->formatWithConversion($amount, $currency, $viewer);

    $primaryClass = match($size) {
        'sm'    => 'text-[0.72rem] font-bold ' . $textColor,
        'lg'    => 'text-lg font-extrabold ' . $textColor,
        default => 'text-sm font-bold ' . $textColor,
    };
    
    // Choose a readable sub-price color based on text context
    $subColor = 'text-[#7A6B5D]'; // Default dark muted brown for light backgrounds
    if ($textColor === 'text-[#D4A574]' || $textColor === 'text-white') {
        $subColor = 'text-[#D4A574]/80'; // Muted gold/white for dark overlay backgrounds
    }

    $subClass = match($size) {
        'sm'    => 'text-[0.55rem] ' . $subColor,
        'lg'    => 'text-xs ' . $subColor,
        default => 'text-[0.62rem] ' . $subColor,
    };
@endphp

<div class="price-display {{ $extraClass }}">
    <span class="{{ $primaryClass }}">{{ $block['primary'] }}</span>
    @if(!$block['same_currency'] && $block['original'])
        <span class="{{ $subClass }} block leading-tight mt-0.5">
            ≈ {{ $block['original'] }} original
        </span>
    @endif
</div>