<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\CookingService;
use App\Models\Recipe;
use App\Models\RecipePurchase;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Notifications\OrderPlacedNotification;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CookerProfileController extends Controller
{
    public function __construct(private CurrencyService $currencyService)
    {
    }

    /**
     * List all cookers (for customer dashboard / browse) with search capability.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::where('role', 'cooker')
            ->withCount(['recipes' => fn($q) => $q->where('is_published', true)])
            ->withCount(['cookingServices' => fn($q) => $q->where('is_available', true)])
            ->withCount('followers');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        } else {
            $query->where(function ($q) {
                $q->whereHas('recipes', fn($r) => $r->where('is_published', true))
                  ->orWhereHas('cookingServices', fn($s) => $s->where('is_available', true));
            });
        }

        $cookers = $query->latest()->paginate(12);

        return view('cookers.index', [
            'user'    => Auth::user(),
            'cookers' => $cookers,
            'search'  => $search,
        ]);
    }

    /**
     * Show all foods (services and recipes) with search and pagination.
     */
    public function allFoods(Request $request)
    {
        $search = $request->input('search');

        // Fetch services
        $servicesQuery = CookingService::available()->with('cooker');
        if ($search) {
            $servicesQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('cooker', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        $services = $servicesQuery->latest()->paginate(12, ['*'], 'services_page');

        // Fetch recipes
        $recipesQuery = Recipe::published()->with('cooker');
        if ($search) {
            $recipesQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('cooker', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        $recipes = $recipesQuery->latest()->paginate(12, ['*'], 'recipes_page');

        return view('cookers.all-foods', [
            'user'     => Auth::user(),
            'services' => $services,
            'recipes'  => $recipes,
            'search'   => $search,
        ]);
    }

    /**
     * Show a cooker's public profile.
     */
    public function show(User $cooker)
    {
        if ($cooker->role !== 'cooker') {
            abort(404);
        }

        $cooker->loadCount('followers');
        $recipes  = $cooker->recipes()->published()->latest()->get();
        $services = $cooker->cookingServices()->available()->latest()->get();
        $isFollowing = Auth::check() ? Auth::user()->isFollowing($cooker) : false;

        return view('cookers.profile', [
            'user'        => Auth::user(),
            'cooker'      => $cooker,
            'recipes'     => $recipes,
            'services'    => $services,
            'isFollowing' => $isFollowing,
        ]);
    }

    /**
     * Toggle follow/unfollow a cooker.
     */
    public function toggleFollow(User $cooker)
    {
        if ($cooker->role !== 'cooker') {
            abort(404);
        }

        $user = Auth::user();
        if ($user->id === $cooker->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        // Toggle follow
        if ($user->isFollowing($cooker)) {
            $user->followingCookers()->detach($cooker->id);
            ActivityLog::log(
                'unfollowed_cooker',
                "{$user->name} unfollowed cooker: {$cooker->name}",
                $user->id, $cooker->id, request()->ip()
            );
            $message = "You unfollowed {$cooker->name}.";
        } else {
            $user->followingCookers()->attach($cooker->id);
            ActivityLog::log(
                'followed_cooker',
                "{$user->name} followed cooker: {$cooker->name}",
                $user->id, $cooker->id, request()->ip()
            );
            $message = "You are now following {$cooker->name}.";
        }

        return back()->with('success', $message);
    }

    /**
     * Show recipe detail (ingredients free, steps behind paywall).
     */
    public function showRecipe(User $cooker, Recipe $recipe)
    {
        if ($recipe->cooker_id !== $cooker->id || !$recipe->is_published) {
            abort(404);
        }

        $currentUser  = Auth::user();
        $hasPurchased = $recipe->isPurchasedBy($currentUser);

        $userPurchase = null;
        if ($currentUser && $hasPurchased) {
            $userPurchase = RecipePurchase::where('customer_id', $currentUser->id)
                ->where('recipe_id', $recipe->id)
                ->first();
        }

        $reviews = RecipePurchase::where('recipe_id', $recipe->id)
            ->whereNotNull('rating')
            ->with('customer')
            ->latest('rated_at')
            ->get();

        // Currency conversion info for buyer
        $conversionInfo = null;
        if ($currentUser && !$hasPurchased) {
            $conversionInfo = $this->getConversionInfo(
                (float) $recipe->price,
                $recipe->currency ?? 'IDR',
                $currentUser->currency ?? 'IDR'
            );
        }

        return view('cookers.recipe-detail', [
            'user'           => $currentUser,
            'cooker'         => $cooker,
            'recipe'         => $recipe,
            'hasPurchased'   => $hasPurchased,
            'userPurchase'   => $userPurchase,
            'reviews'        => $reviews,
            'conversionInfo' => $conversionInfo,
        ]);
    }

    /**
     * Show cooking service detail.
     */
    public function showService(User $cooker, CookingService $service)
    {
        if ($service->cooker_id !== $cooker->id || !$service->is_available) {
            abort(404);
        }

        $currentUser = Auth::user();
        $userOrder   = null;
        if ($currentUser) {
            $userOrder = ServiceOrder::where('customer_id', $currentUser->id)
                ->where('service_id', $service->id)
                ->whereNull('rating')
                ->latest()
                ->first();
        }

        $reviews = ServiceOrder::where('service_id', $service->id)
            ->whereNotNull('rating')
            ->with('customer')
            ->latest('rated_at')
            ->get();

        // Currency conversion info
        $conversionInfo = null;
        if ($currentUser) {
            $conversionInfo = $this->getConversionInfo(
                (float) $service->price,
                $service->currency ?? 'IDR',
                $currentUser->currency ?? 'IDR'
            );
        }

        return view('cookers.service-detail', [
            'user'           => $currentUser,
            'cooker'         => $cooker,
            'service'        => $service,
            'userOrder'      => $userOrder,
            'reviews'        => $reviews,
            'conversionInfo' => $conversionInfo,
        ]);
    }

    /**
     * Purchase recipe steps — deducts buyer wallet, credits seller wallet.
     */
    public function purchaseRecipe(Request $request, Recipe $recipe)
    {
        $user = Auth::user();

        if ($user->id === $recipe->cooker_id) {
            return back()->with('error', 'You cannot purchase your own recipe.');
        }

        if ($recipe->isPurchasedBy($user)) {
            return back()->with('info', 'You already have access to this recipe.');
        }

        $buyerCurrency  = $user->currency ?? 'IDR';
        $sellerCurrency = $recipe->currency ?? 'IDR';
        $originalPrice  = (float) $recipe->price;

        // Convert price to buyer's currency
        $rate           = $this->currencyService->getRate($sellerCurrency, $buyerCurrency);
        $buyerAmount    = $this->currencyService->convert($originalPrice, $sellerCurrency, $buyerCurrency);

        // Check buyer has sufficient balance
        if ((float) $user->wallet_balance < $buyerAmount) {
            $formatted = $this->currencyService->formatAmount($buyerAmount, $buyerCurrency);
            return back()->with('error', "Insufficient wallet balance. Required: {$formatted}. Please top-up on the wallet page.");
        }

        DB::transaction(function () use ($user, $recipe, $buyerCurrency, $sellerCurrency, $originalPrice, $rate, $buyerAmount) {
            $seller = $recipe->cooker;

            // 1. Deduct buyer wallet
            $user->decrement('wallet_balance', $buyerAmount);

            // 2. Credit seller wallet (convert buyer amount back to seller's currency)
            $sellerCreditAmount = $this->currencyService->convert($buyerAmount, $buyerCurrency, $sellerCurrency);
            $seller->increment('wallet_balance', $sellerCreditAmount);

            // 3. Record purchase
            RecipePurchase::create([
                'customer_id' => $user->id,
                'recipe_id'   => $recipe->id,
                'amount_paid' => $buyerAmount,
                'created_at'  => now(),
            ]);

            // 4. Record buyer's debit transaction
            WalletTransaction::create([
                'user_id'           => $user->id,
                'type'              => 'debit',
                'amount'            => $buyerAmount,
                'currency'          => $buyerCurrency,
                'reference_type'    => 'recipe_purchase',
                'reference_id'      => $recipe->id,
                'original_amount'   => $originalPrice,
                'original_currency' => $sellerCurrency,
                'exchange_rate'     => $rate,
                'description'       => "Purchased recipe: {$recipe->title}",
            ]);

            // 5. Record seller's credit transaction
            WalletTransaction::create([
                'user_id'           => $seller->id,
                'type'              => 'credit',
                'amount'            => $sellerCreditAmount,
                'currency'          => $sellerCurrency,
                'reference_type'    => 'sale_credit',
                'reference_id'      => $recipe->id,
                'original_amount'   => $buyerAmount,
                'original_currency' => $buyerCurrency,
                'exchange_rate'     => $this->currencyService->getRate($buyerCurrency, $sellerCurrency),
                'description'       => "Sold recipe: {$recipe->title} (from {$user->name})",
            ]);
        });

        ActivityLog::log(
            'recipe_purchased',
            "{$user->name} purchased recipe: {$recipe->title} ({$recipe->formatted_price})",
            $user->id, $recipe->cooker_id, $request->ip()
        );

        return back()->with('success', "Successfully purchased recipe: \"{$recipe->title}\"! 🎉");
    }

    /**
     * Submit rating and review for a purchased recipe.
     */
    public function rateRecipe(Request $request, Recipe $recipe)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();

        $purchase = RecipePurchase::where('customer_id', $user->id)
            ->where('recipe_id', $recipe->id)
            ->first();

        if (!$purchase) {
            return back()->with('error', 'You must purchase this recipe first before giving a rating.');
        }

        $purchase->update([
            'rating'   => $request->input('rating'),
            'review'   => $request->input('review'),
            'rated_at' => now(),
        ]);

        ActivityLog::log(
            'recipe_rated',
            "{$user->name} rated recipe: {$recipe->title} with {$request->input('rating')} stars",
            $user->id, $recipe->cooker_id, $request->ip()
        );

        return back()->with('success', 'Thank you for your rating and review! ⭐');
    }

    /**
     * Show order service form.
     */
    public function orderServiceForm(User $cooker, CookingService $service)
    {
        if ($service->cooker_id !== $cooker->id || !$service->is_available) {
            abort(404);
        }

        $currentUser    = Auth::user();
        $conversionInfo = $this->getConversionInfo(
            (float) $service->price,
            $service->currency ?? 'IDR',
            $currentUser->currency ?? 'IDR'
        );

        return view('cookers.order-service', [
            'user'           => $currentUser,
            'cooker'         => $cooker,
            'service'        => $service,
            'conversionInfo' => $conversionInfo,
        ]);
    }

    /**
     * Process service order — deducts buyer wallet, credits seller wallet.
     */
    public function orderService(Request $request, CookingService $service)
    {
        $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();

        if ($user->id === $service->cooker_id) {
            return back()->with('error', 'You cannot order your own service.');
        }

        $buyerCurrency  = $user->currency ?? 'IDR';
        $sellerCurrency = $service->currency ?? 'IDR';
        $originalPrice  = (float) $service->price;

        // Convert price to buyer's currency
        $rate        = $this->currencyService->getRate($sellerCurrency, $buyerCurrency);
        $buyerAmount = $this->currencyService->convert($originalPrice, $sellerCurrency, $buyerCurrency);

        // Check buyer has sufficient balance
        if ((float) $user->wallet_balance < $buyerAmount) {
            $formatted = $this->currencyService->formatAmount($buyerAmount, $buyerCurrency);
            return back()->with('error', "Insufficient wallet balance. Required: {$formatted}. Please top-up on the wallet page.");
        }

        $order = DB::transaction(function () use ($user, $service, $request, $buyerCurrency, $sellerCurrency, $originalPrice, $rate, $buyerAmount) {
            $seller = $service->cooker;

            // 1. Deduct buyer wallet
            $user->decrement('wallet_balance', $buyerAmount);

            // 2. Credit seller wallet
            $sellerCreditAmount = $this->currencyService->convert($buyerAmount, $buyerCurrency, $sellerCurrency);
            $seller->increment('wallet_balance', $sellerCreditAmount);

            // 3. Create order
            $order = ServiceOrder::create([
                'customer_id' => $user->id,
                'service_id'  => $service->id,
                'cooker_id'   => $service->cooker_id,
                'status'      => 'pending',
                'notes'       => $request->input('notes'),
                'total_price' => $buyerAmount,
            ]);

            // 4. Record buyer's debit transaction
            WalletTransaction::create([
                'user_id'           => $user->id,
                'type'              => 'debit',
                'amount'            => $buyerAmount,
                'currency'          => $buyerCurrency,
                'reference_type'    => 'service_order',
                'reference_id'      => $order->id,
                'original_amount'   => $originalPrice,
                'original_currency' => $sellerCurrency,
                'exchange_rate'     => $rate,
                'description'       => "Ordered service: {$service->title}",
            ]);

            // 5. Record seller's credit transaction
            WalletTransaction::create([
                'user_id'           => $seller->id,
                'type'              => 'credit',
                'amount'            => $sellerCreditAmount,
                'currency'          => $sellerCurrency,
                'reference_type'    => 'sale_credit',
                'reference_id'      => $order->id,
                'original_amount'   => $buyerAmount,
                'original_currency' => $buyerCurrency,
                'exchange_rate'     => $this->currencyService->getRate($buyerCurrency, $sellerCurrency),
                'description'       => "Service earnings: {$service->title} (from {$user->name})",
            ]);

            return $order;
        });

        ActivityLog::log(
            'service_ordered',
            "{$user->name} ordered service: {$service->title} from {$service->cooker->name}",
            $user->id, $service->cooker_id, $request->ip()
        );

        // Notify the cooker about the new order
        try {
            $order->load('service');
            $service->cooker->notify(new OrderPlacedNotification($order, $user));
        } catch (\Exception $e) {
            // Silently fail if notification fails
        }

        return redirect()->route('cookers.show', $service->cooker)
            ->with('success', "Cooking service order \"{$service->title}\" created successfully! Status: Pending. 🎉");
    }

    /**
     * Submit rating and review for a service order.
     */
    public function rateOrder(Request $request, ServiceOrder $order)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();

        if ($order->customer_id !== $user->id) {
            abort(403);
        }

        $order->update([
            'rating'   => $request->input('rating'),
            'review'   => $request->input('review'),
            'rated_at' => now(),
        ]);

        ActivityLog::log(
            'service_rated',
            "{$user->name} rated service order #{$order->id} with {$request->input('rating')} stars",
            $user->id, $order->cooker_id, $request->ip()
        );

        return back()->with('success', 'Thank you for your rating and review! ⭐');
    }

    // ── Private helpers ──

    /**
     * Build conversion info array for views.
     */
    private function getConversionInfo(float $amount, string $fromCurrency, string $toCurrency): array
    {
        $rate             = $this->currencyService->getRate($fromCurrency, $toCurrency);
        $convertedAmount  = $this->currencyService->convert($amount, $fromCurrency, $toCurrency);
        $needsConversion  = $fromCurrency !== $toCurrency;

        return [
            'original_amount'     => $amount,
            'original_currency'   => $fromCurrency,
            'converted_amount'    => $convertedAmount,
            'buyer_currency'      => $toCurrency,
            'rate'                => $rate,
            'needs_conversion'    => $needsConversion,
            'formatted_original'  => $this->currencyService->formatAmount($amount, $fromCurrency),
            'formatted_converted' => $this->currencyService->formatAmount($convertedAmount, $toCurrency),
        ];
    }
}
