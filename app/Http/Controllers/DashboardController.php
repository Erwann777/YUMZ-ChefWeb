<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CurrencyService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private readonly CurrencyService $cs) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $search = $request->input('search');
        $viewerCurrency = strtoupper($user?->currency ?? 'IDR');


        // 1. Hot Recommendations & Best Sellers
        // Cooking Services with rating 4-5 stars, prioritized, and ordered by order count
        $hotRecommendations = \App\Models\CookingService::available()
            ->with('cooker')
            ->withCount(['orders as rated_sales' => function ($query) {
                $query->where('rating', '>=', 4);
            }])
            ->withCount('orders')
            ->orderByDesc('rated_sales')
            ->orderByDesc('orders_count')
            ->take(6)
            ->get();

        // 2. Semua Makanan (All Foods / Services) - with search query and random/ordered fallback
        $allServicesQuery = \App\Models\CookingService::available()->with('cooker');
        if ($search) {
            $allServicesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('cooker', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        } else {
            // Default random as per request
            $allServicesQuery->inRandomOrder();
        }
        $services = $allServicesQuery->take(16)->get();

        // 2b. Semua Resep Rahasia - with search query and random/ordered fallback
        $allRecipesQuery = \App\Models\Recipe::published()->with('cooker');
        if ($search) {
            $allRecipesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('cooker', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        } else {
            $allRecipesQuery->inRandomOrder();
        }
        $recipes = $allRecipesQuery->take(16)->get();

        // Special Search Results at the top of the dashboard
        $searchedFoods = collect();
        $searchedCookers = collect();
        if ($search) {
            $searchedServices = \App\Models\CookingService::available()
                ->where('title', 'like', "%{$search}%")
                ->with('cooker')
                ->get();
            $searchedRecipes = \App\Models\Recipe::published()
                ->where('title', 'like', "%{$search}%")
                ->with('cooker')
                ->get();
            $searchedFoods = $searchedServices->concat($searchedRecipes);

            $searchedCookers = User::where('role', 'cooker')
                ->where('name', 'like', "%{$search}%")
                ->with(['cookingServices', 'recipes'])
                ->get();
        }

        // 3. Ranked Cookers (Top 10-20 with highest rating and highest sales based on services)
        $cookersQuery = User::where('role', 'cooker')
            ->with(['cookingServices' => function ($q) {
                $q->available()->with('orders');
            }]);

        if ($search) {
            $cookersQuery->where('name', 'like', "%{$search}%");
        }

        $allCookers = $cookersQuery->get();

        $rankedCookers = $allCookers->map(function ($cooker) {
            $cookerServices = $cooker->cookingServices;
            $totalSales = 0;
            $totalRatingSum = 0;
            $ratedOrdersCount = 0;

            foreach ($cookerServices as $service) {
                $orders = $service->orders;
                $totalSales += $orders->count();
                foreach ($orders as $order) {
                    if ($order->rating !== null) {
                        $totalRatingSum += $order->rating;
                        $ratedOrdersCount++;
                    }
                }
            }

            $cooker->calculated_sales = $totalSales;
            $cooker->calculated_rating = $ratedOrdersCount > 0 ? round($totalRatingSum / $ratedOrdersCount, 1) : 0.0;
            return $cooker;
        });

        // Sort by rating desc, then sales desc
        $rankedCookers = $rankedCookers->sort(function ($a, $b) {
            if ($b->calculated_rating !== $a->calculated_rating) {
                return $b->calculated_rating <=> $a->calculated_rating;
            }
            if ($b->calculated_sales !== $a->calculated_sales) {
                return $b->calculated_sales <=> $a->calculated_sales;
            }
            return $b->id <=> $a->id;
        })->take(15)->values(); // Limit to top 15 (between 10-20)

        // 4. Customer's Purchased Recipes (Kept as fallback or transaction list)
        $purchasedRecipes = [];
        if ($user) {
            $purchasedRecipes = \App\Models\Recipe::whereIn('id', $user->recipePurchases()->pluck('recipe_id'))
                ->with('cooker')
                ->latest()
                ->get();
        }

        // 5. Customer's Service Orders placed
        $serviceOrders = [];
        if ($user) {
            $serviceOrders = \App\Models\ServiceOrder::where('customer_id', $user->id)
                ->with(['service', 'cooker'])
                ->latest()
                ->get();
        }

        return view('dashboard', [
            'user'               => $user,
            'cookers'            => $rankedCookers,
            'recipes'            => $recipes,
            'services'           => $services,
            'searchedFoods'      => $searchedFoods,
            'searchedCookers'    => $searchedCookers,
            'hotRecommendations' => $hotRecommendations,
            'purchasedRecipes'   => $purchasedRecipes,
            'serviceOrders'      => $serviceOrders,
            'search'             => $search,
            'cs'                 => $this->cs,
            'viewerCurrency'     => $viewerCurrency,
        ]);
    }

    /**
     * Get the live status of an order for AJAX tracking.
     */
    public function getOrderStatus(Request $request, \App\Models\ServiceOrder $order)
    {
        if ($order->customer_id !== $request->user()->id && $order->cooker_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'id' => $order->id,
            'status' => $order->status,
            'status_label' => ucfirst($order->status),
            'status_badge' => $order->status_badge,
            'service_title' => $order->service->title,
            'cooker_name' => $order->cooker->name,
            'notes' => $order->notes,
            'updated_at' => $order->updated_at->toIso8601String(),
        ]);
    }
}
