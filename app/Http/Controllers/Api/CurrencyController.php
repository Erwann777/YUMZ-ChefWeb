<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct(private CurrencyService $currencyService)
    {
    }

    /**
     * Return all exchange rates (IDR base) as JSON.
     */
    public function rates(): JsonResponse
    {
        return response()->json($this->currencyService->getAllRatesForFrontend());
    }

    /**
     * Convert an amount between currencies.
     */
    public function convert(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'from'   => ['required', 'in:IDR,SGD,MYR'],
            'to'     => ['required', 'in:IDR,SGD,MYR'],
        ]);

        $amount   = (float) $request->amount;
        $from     = $request->from;
        $to       = $request->to;
        $rate     = $this->currencyService->getRate($from, $to);
        $converted = $this->currencyService->convert($amount, $from, $to);

        return response()->json([
            'original_amount'   => $amount,
            'from'              => $from,
            'to'                => $to,
            'rate'              => $rate,
            'converted_amount'  => $converted,
            'formatted_original'  => $this->currencyService->formatAmount($amount, $from),
            'formatted_converted' => $this->currencyService->formatAmount($converted, $to),
        ]);
    }
}
