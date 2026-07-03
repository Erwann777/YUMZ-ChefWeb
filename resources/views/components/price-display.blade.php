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
    
    // 1. Ubah warna default harga utama menjadi putih ('text-white')
    $textColor  = $color ?? 'text-white';

    $block = $cs->formatWithConversion($amount, $currency, $viewer);

    $primaryClass = match($size) {
        'sm'    => 'text-[0.72rem] font-bold ' . $textColor,
        'lg'    => 'text-lg font-extrabold ' . $textColor,
        default => 'text-sm font-bold ' . $textColor,
    };
    
    // 2. Tambahkan warna hijau neon ('text-[#00FF00]') langsung ke class harga bawah
    $subClass = match($size) {
        'sm'    => 'text-[0.55rem] text-[#00FF00]',
        'lg'    => 'text-xs text-[#00FF00]',
        default => 'text-[0.62rem] text-[#00FF00]',
    };
@endphp

<div class="price-display {{ $extraClass }}">
    <span class="{{ $primaryClass }}">{{ $block['primary'] }}</span>
    @if(!$block['same_currency'] && $block['original'])
        {{-- 3. Hapus class warna lama 'text-[#9A7B5A]' agar warna neon dari $subClass tidak tertimpa --}}
        <span class="{{ $subClass }} block leading-tight mt-0.5">
            ≈ {{ $block['original'] }} original
        </span>
    @endif
</div>