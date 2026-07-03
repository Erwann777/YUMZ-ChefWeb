<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function __construct(private CurrencyService $currencyService)
    {
    }

    /**
     * Show wallet dashboard with balance and transaction history.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $transactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        $totalCredits = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->sum('amount');

        $totalDebits = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'debit')
            ->sum('amount');

        // Get current rates for display
        $rates = $this->currencyService->getRates('IDR');

        return view('wallet.index', [
            'user'         => $user,
            'transactions' => $transactions,
            'totalCredits' => $totalCredits,
            'totalDebits'  => $totalDebits,
            'rates'        => $rates,
            'currency'     => $user->currency ?? 'IDR',
        ]);
    }

    /**
     * Simulate a top-up (for demo/simulation purposes only).
     */
    public function topUp(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:99999999'],
        ]);

        $user    = Auth::user();
        $amount  = (float) $request->amount;
        $currency = $user->currency ?? 'IDR';

        DB::transaction(function () use ($user, $amount, $currency) {
            // Credit wallet
            $user->increment('wallet_balance', $amount);

            // Record transaction
            WalletTransaction::create([
                'user_id'     => $user->id,
                'type'        => 'credit',
                'amount'      => $amount,
                'currency'    => $currency,
                'reference_type' => 'topup',
                'description' => 'Virtual Wallet Simulation Top-Up',
            ]);
        });

        $formatted = $this->currencyService->formatAmount($amount, $currency);
        return back()->with('success', "Successfully topped up {$formatted} to your wallet! (Simulation)");
    }
}
