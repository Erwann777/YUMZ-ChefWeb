<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    /**
     * Fallback exchange rates (base: IDR).
     * These are used when the external API is unavailable.
     */
    private const FALLBACK_RATES_IDR_BASE = [
        'IDR' => 1.0,
        'SGD' => 0.000087,    // 1 IDR ≈ 0.000087 SGD  →  1 SGD ≈ 11,500 IDR
        'MYR' => 0.000294,    // 1 IDR ≈ 0.000294 MYR  →  1 MYR ≈ 3,400 IDR
    ];

    /**
     * Supported currencies.
     */
    public const CURRENCIES = ['IDR', 'SGD', 'MYR'];

    /**
     * Cache key prefix for rates.
     */
    private const CACHE_KEY = 'currency_rates_';

    /**
     * Cache duration in seconds (1 hour).
     */
    private const CACHE_TTL = 3600;

    /**
     * Fetch all rates from Frankfurter API, cached for 1 hour.
     * Returns rates relative to IDR base.
     */
    public function getRates(string $baseCurrency = 'IDR'): array
    {
        $cacheKey = self::CACHE_KEY . $baseCurrency;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($baseCurrency) {
            return $this->fetchFromApi($baseCurrency);
        });
    }

    /**
     * Fetch rates from Frankfurter API.
     */
    private function fetchFromApi(string $baseCurrency): array
    {
        try {
            // Frankfurter API only supports major currencies; IDR/MYR/SGD are supported.
            $symbols = implode(',', array_filter(self::CURRENCIES, fn($c) => $c !== $baseCurrency));
            $response = Http::timeout(5)->get("https://api.frankfurter.app/latest", [
                'base'    => $baseCurrency,
                'symbols' => $symbols,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $rates = $data['rates'] ?? [];
                $rates[$baseCurrency] = 1.0; // base rate is always 1
                return $rates;
            }
        } catch (\Throwable $e) {
            Log::warning("CurrencyService: Frankfurter API failed. Using fallback rates. Error: " . $e->getMessage());
        }

        return $this->getFallbackRates($baseCurrency);
    }

    /**
     * Get fallback rates when API is unavailable.
     */
    private function getFallbackRates(string $baseCurrency): array
    {
        if ($baseCurrency === 'IDR') {
            return self::FALLBACK_RATES_IDR_BASE;
        }

        // Convert from IDR-base fallback to any other base
        $idrBase = self::FALLBACK_RATES_IDR_BASE;
        $baseInIdr = 1 / $idrBase[$baseCurrency];
        $rates = [];
        foreach ($idrBase as $currency => $rateFromIdr) {
            $rates[$currency] = $rateFromIdr * $baseInIdr;
        }
        $rates[$baseCurrency] = 1.0;
        return $rates;
    }

    /**
     * Get exchange rate from one currency to another.
     */
    public function getRate(string $from, string $to): float
    {
        if ($from === $to) {
            return 1.0;
        }

        $rates = $this->getRates($from);
        return (float) ($rates[$to] ?? $this->getFallbackRate($from, $to));
    }

    /**
     * Get fallback rate directly.
     */
    private function getFallbackRate(string $from, string $to): float
    {
        $rates = $this->getFallbackRates($from);
        return (float) ($rates[$to] ?? 1.0);
    }

    /**
     * Convert an amount from one currency to another.
     */
    public function convert(float $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return $amount;
        }

        $rate = $this->getRate($from, $to);
        return round($amount * $rate, 2);
    }

    /**
     * Format an amount with correct currency symbol and decimals.
     */
    public function formatAmount(float $amount, string $currency): string
    {
        return match($currency) {
            'IDR' => 'Rp ' . number_format($amount, 0, ',', '.'),
            'SGD' => 'S$' . number_format($amount, 2, '.', ','),
            'MYR' => 'RM' . number_format($amount, 2, '.', ','),
            default => number_format($amount, 2) . ' ' . $currency,
        };
    }

    /**
     * Get currency symbol only.
     */
    public function getSymbol(string $currency): string
    {
        return match($currency) {
            'SGD' => 'S$',
            'MYR' => 'RM',
            default => 'Rp',
        };
    }

    /**
     * Get all rates for frontend use (from IDR base).
     */
    public function getAllRatesForFrontend(): array
    {
        return [
            'rates'   => $this->getRates('IDR'),
            'updated' => now()->toIso8601String(),
            'source'  => 'Frankfurter API (with fallback)',
        ];
    }

    /**
     * Clear cached rates (useful for testing or manual refresh).
     */
    public function clearCache(): void
    {
        foreach (self::CURRENCIES as $currency) {
            Cache::forget(self::CACHE_KEY . $currency);
        }
    }

    /**
     * Check whether the rates are from fallback (API unavailable).
     */
    public function isUsingFallback(): bool
    {
        try {
            $response = Http::timeout(3)->get("https://api.frankfurter.app/latest", [
                'base'    => 'IDR',
                'symbols' => 'SGD,MYR',
            ]);
            return !$response->successful();
        } catch (\Throwable) {
            return true;
        }
    }

    /**
     * Return price display data for a given amount/currency pair.
     * - 'primary'       : formatted string in the viewer's currency
     * - 'original'      : formatted string in the cooker's currency (null when same)
     * - 'same_currency' : bool — true when viewer and cooker share the same currency
     */
    public function formatWithConversion(
        float  $amount,
        string $fromCurrency,
        string $toCurrency
    ): array {
        $fromCurrency = strtoupper($fromCurrency ?: 'IDR');
        $toCurrency   = strtoupper($toCurrency   ?: 'IDR');

        if ($fromCurrency === $toCurrency) {
            return [
                'primary'       => $this->formatAmount($amount, $fromCurrency),
                'original'      => null,
                'same_currency' => true,
            ];
        }

        $converted = $this->convert($amount, $fromCurrency, $toCurrency);

        return [
            'primary'       => $this->formatAmount($converted, $toCurrency),
            'original'      => $this->formatAmount($amount, $fromCurrency),
            'same_currency' => false,
        ];
    }

    /**
     * Convenience wrapper: given an Eloquent model with ->price and ->currency,
     * and the viewer's currency, return the price block array.
     */
    public function priceBlockFor(mixed $item, string $viewerCurrency): array
    {
        $itemCurrency = strtoupper($item->currency ?? 'IDR');
        return $this->formatWithConversion((float) $item->price, $itemCurrency, $viewerCurrency);
    }
}
