<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipePurchase;
use App\Models\ServiceOrder;
use App\Notifications\OrderCompletedNotification;
use Illuminate\Http\Request;

class CookerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $recipes = $user->recipes()->latest()->get();
        $services = $user->cookingServices()->latest()->get();
        $totalRecipes = $recipes->count();
        $totalServices = $services->count();

        // Earnings from recipe purchases & service orders (already converted and logged in cooker's currency)
        $totalEarnings = \App\Models\WalletTransaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->where('reference_type', 'sale_credit')
            ->sum('amount');

        // Pending orders
        $pendingOrders = ServiceOrder::where('cooker_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // Received orders list
        $orders = ServiceOrder::where('cooker_id', $user->id)
            ->with(['service', 'customer'])
            ->latest()
            ->get();

        return view('cooker.dashboard', [
            'user' => $user,
            'recipes' => $recipes,
            'services' => $services,
            'totalRecipes' => $totalRecipes,
            'totalServices' => $totalServices,
            'totalEarnings' => $totalEarnings,
            'pendingOrders' => $pendingOrders,
            'orders' => $orders,
        ]);
    }

    public function updateOrderStatus(Request $request, ServiceOrder $order)
    {
        if ($order->cooker_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:confirmed,completed,cancelled'],
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
            $buyerDebitTx = \App\Models\WalletTransaction::where('reference_type', 'service_order')
                ->where('reference_id', $order->id)
                ->where('type', 'debit')
                ->first();

            $sellerCreditTx = \App\Models\WalletTransaction::where('reference_type', 'sale_credit')
                ->where('reference_id', $order->id)
                ->where('type', 'credit')
                ->first();

            if ($buyerDebitTx && $sellerCreditTx) {
                \Illuminate\Support\Facades\DB::transaction(function () use ($order, $buyerDebitTx, $sellerCreditTx) {
                    $buyer = $buyerDebitTx->user;
                    $seller = $sellerCreditTx->user;

                    // Refund buyer
                    $buyer->increment('wallet_balance', $buyerDebitTx->amount);

                    // Revert seller credit
                    $seller->decrement('wallet_balance', $sellerCreditTx->amount);

                    // Record refund transaction for buyer
                    \App\Models\WalletTransaction::create([
                        'user_id'           => $buyer->id,
                        'type'              => 'credit',
                        'amount'            => $buyerDebitTx->amount,
                        'currency'          => $buyerDebitTx->currency,
                        'reference_type'    => 'refund',
                        'reference_id'      => $order->id,
                        'description'       => "Refund for cancellation of order #{$order->id}",
                    ]);

                    // Record refund transaction for seller
                    \App\Models\WalletTransaction::create([
                        'user_id'           => $seller->id,
                        'type'              => 'debit',
                        'amount'            => $sellerCreditTx->amount,
                        'currency'          => $sellerCreditTx->currency,
                        'reference_type'    => 'refund',
                        'reference_id'      => $order->id,
                        'description'       => "Reversal of funds for cancelled order #{$order->id}",
                    ]);
                });
            }
        }

        $order->update([
            'status' => $newStatus,
        ]);

        // Notify customer when cooker marks order as completed
        if ($newStatus === 'completed') {
            try {
                $order->load(['customer', 'service']);
                $order->customer->notify(new OrderCompletedNotification($order, $request->user()));
            } catch (\Exception $e) {
                // Silently fail if notification fails
            }
        }

        \App\Models\ActivityLog::log(
            'order_status_updated',
            "Cooker updated order #{$order->id} status: {$oldStatus} -> {$newStatus}",
            $request->user()->id,
            $order->customer_id,
            $request->ip()
        );

        return back()->with('success', "Order #{$order->id} status successfully updated to: " . ucfirst($newStatus));
    }
}
