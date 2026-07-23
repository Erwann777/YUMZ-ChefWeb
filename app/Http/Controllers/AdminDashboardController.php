<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\CookingService;
use App\Models\Recipe;
use App\Models\RecipePurchase;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminDashboardController extends Controller
{
    /**
     * Admin overview dashboard.
     */
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalCookers = User::where('role', 'cooker')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRecipes = Recipe::count();
        $totalServices = CookingService::count();
        $totalOrders = ServiceOrder::count();
        $pendingOrders = ServiceOrder::where('status', 'pending')->count();
        $totalPurchases = RecipePurchase::count();
        $suspendedUsers = User::where('is_suspended', true)->count();

        $recipeRevenue = (float) RecipePurchase::sum('amount_paid');
        $serviceRevenue = (float) ServiceOrder::where('status', '!=', 'cancelled')->sum('total_price');
        $totalRevenue = $recipeRevenue + $serviceRevenue;

        $recentActivities = ActivityLog::with('user')->latest('created_at')->take(8)->get();

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData[] = [
                'label' => $date->format('d M'),
                'count' => User::whereDate('created_at', $date->toDateString())->count(),
            ];
        }

        $revenueChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateStr = $date->toDateString();
            $dayRecipe = (float) RecipePurchase::whereDate('created_at', $dateStr)->sum('amount_paid');
            $dayService = (float) ServiceOrder::whereDate('created_at', $dateStr)
                ->where('status', '!=', 'cancelled')
                ->sum('total_price');
            $revenueChartData[] = [
                'label' => $date->format('d M'),
                'amount' => $dayRecipe + $dayService,
            ];
        }

        $orderStatusCounts = [
            'pending' => ServiceOrder::where('status', 'pending')->count(),
            'confirmed' => ServiceOrder::where('status', 'confirmed')->count(),
            'completed' => ServiceOrder::where('status', 'completed')->count(),
            'cancelled' => ServiceOrder::where('status', 'cancelled')->count(),
        ];

        $topCookers = User::where('role', 'cooker')
            ->withCount(['recipes', 'cookingServices', 'cookerOrders'])
            ->orderByDesc('cooker_orders_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'user' => $request->user(),
            'totalUsers' => $totalUsers,
            'totalCustomers' => $totalCustomers,
            'totalCookers' => $totalCookers,
            'totalAdmins' => $totalAdmins,
            'totalRecipes' => $totalRecipes,
            'totalServices' => $totalServices,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'totalPurchases' => $totalPurchases,
            'suspendedUsers' => $suspendedUsers,
            'totalRevenue' => $totalRevenue,
            'recentActivities' => $recentActivities,
            'chartData' => $chartData,
            'revenueChartData' => $revenueChartData,
            'orderStatusCounts' => $orderStatusCounts,
            'topCookers' => $topCookers,
        ]);
    }

    // ===================== [READ] =====================
    /**
     * User management page.
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($request->input('status') === 'suspended') {
            $query->where('is_suspended', true);
        } elseif ($request->input('status') === 'active') {
            $query->where('is_suspended', false);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users', [
            'user' => $request->user(),
            'users' => $users,
            'search' => $search ?? null,
            'roleFilter' => $role ?? null,
            'statusFilter' => $request->input('status'),
        ]);
    }

    // ===================== [READ] =====================
    /**
     * Show user detail page.
     */
    public function showUser(Request $request, User $targetUser)
    {
        $targetUser->loadCount(['recipes', 'cookingServices', 'recipePurchases', 'serviceOrders', 'cookerOrders', 'walletTransactions']);

        $recentTransactions = $targetUser->walletTransactions()->latest()->take(10)->get();
        $recentPurchases = $targetUser->recipePurchases()->with('recipe')->latest('created_at')->take(5)->get();
        $recentOrders = $targetUser->serviceOrders()->with('service')->latest()->take(5)->get();

        return view('admin.show-user', [
            'user' => $request->user(),
            'targetUser' => $targetUser,
            'recentTransactions' => $recentTransactions,
            'recentPurchases' => $recentPurchases,
            'recentOrders' => $recentOrders,
        ]);
    }

    // ===================== [READ] =====================
    /**
     * Show edit user form.
     */
    public function editUser(Request $request, User $targetUser)
    {
        return view('admin.edit-user', [
            'user' => $request->user(),
            'targetUser' => $targetUser,
        ]);
    }

    // ===================== [UPDATE] =====================
    /**
     * Update a user.
     */
    public function updateUser(Request $request, User $targetUser)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($targetUser->id)],
            'role' => ['required', 'in:customer,cooker,admin'],
        ]);

        $changes = [];
        if ($targetUser->name !== $validated['name']) {
            $changes[] = "name: {$targetUser->name} → {$validated['name']}";
        }
        if ($targetUser->email !== $validated['email']) {
            $changes[] = "email: {$targetUser->email} → {$validated['email']}";
        }
        if ($targetUser->role !== $validated['role']) {
            $oldRole = $targetUser->role;
            $changes[] = "role: {$oldRole} → {$validated['role']}";

            ActivityLog::log(
                'role_changed',
                "Role changed for {$targetUser->name}: {$oldRole} → {$validated['role']}",
                $request->user()->id,
                $targetUser->id,
                $request->ip()
            );
        }

        $targetUser->update($validated);

        if (!empty($changes)) {
            ActivityLog::log(
                'user_updated',
                "Admin updated {$targetUser->name}: " . implode(', ', $changes),
                $request->user()->id,
                $targetUser->id,
                $request->ip()
            );
        }

        return redirect()->route('admin.users')->with('success', "User {$targetUser->name} successfully updated.");
    }

    // ===================== [UPDATE] =====================
    /**
     * Toggle user suspension status.
     */
    public function toggleSuspendUser(Request $request, User $targetUser)
    {
        if ($targetUser->id === $request->user()->id) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        if ($targetUser->isAdmin() && !$targetUser->is_suspended) {
            return back()->with('error', 'You cannot suspend another admin account.');
        }

        $targetUser->update(['is_suspended' => !$targetUser->is_suspended]);
        $action = $targetUser->is_suspended ? 'suspended' : 'unsuspended';

        ActivityLog::log(
            'user_suspended',
            "Admin {$action} user: {$targetUser->name} ({$targetUser->email})",
            $request->user()->id,
            $targetUser->id,
            $request->ip()
        );

        return back()->with('success', "User {$targetUser->name} has been {$action}.");
    }

    // ===================== [DELETE] =====================
    /**
     * Delete a user.
     */
    public function deleteUser(Request $request, User $targetUser)
    {
        if ($targetUser->id === $request->user()->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $targetUser->name;
        $email = $targetUser->email;
        $role = $targetUser->role;

        ActivityLog::log(
            'user_deleted',
            "Admin deleted user: {$name} ({$email}, role: {$role})",
            $request->user()->id,
            $targetUser->id,
            $request->ip()
        );

        $targetUser->delete();

        return redirect()->route('admin.users')->with('success', "User {$name} successfully deleted.");
    }

    // ===================== [READ] =====================
    /**
     * All service orders.
     */
    public function orders(Request $request)
    {
        $query = ServiceOrder::with(['customer', 'cooker', 'service'])->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('cooker', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('service', fn ($q) => $q->where('title', 'like', "%{$search}%"));
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders', [
            'user' => $request->user(),
            'orders' => $orders,
            'statusFilter' => $status ?? null,
            'search' => $search ?? null,
        ]);
    }

    // ===================== [UPDATE] =====================
    /**
     * Update order status (admin override).
     */
    public function updateOrderStatus(Request $request, ServiceOrder $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
            $this->processOrderCancellationRefund($order);
        }

        $order->update(['status' => $newStatus]);

        ActivityLog::log(
            'order_status_updated',
            "Admin updated order #{$order->id} status: {$oldStatus} → {$newStatus}",
            $request->user()->id,
            $order->customer_id,
            $request->ip()
        );

        return back()->with('success', "Order #{$order->id} status updated to " . ucfirst($newStatus) . '.');
    }

    // ===================== [READ] =====================
    /**
     * Content management (recipes & services).
     */
    public function content(Request $request)
    {
        $tab = $request->input('tab', 'recipes');
        $search = $request->input('search');

        $recipes = collect();
        $services = collect();

        if ($tab === 'recipes') {
            $query = Recipe::with('cooker')->withCount('purchases')->latest();
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('cooker', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                });
            }
            if ($request->input('filter') === 'published') {
                $query->where('is_published', true);
            } elseif ($request->input('filter') === 'draft') {
                $query->where('is_published', false);
            }
            $recipes = $query->paginate(15)->withQueryString();
        } else {
            $query = CookingService::with('cooker')->withCount('orders')->latest();
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('cooker', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                });
            }
            if ($request->input('filter') === 'available') {
                $query->where('is_available', true);
            } elseif ($request->input('filter') === 'unavailable') {
                $query->where('is_available', false);
            }
            $services = $query->paginate(15)->withQueryString();
        }

        return view('admin.content', [
            'user' => $request->user(),
            'tab' => $tab,
            'recipes' => $recipes,
            'services' => $services,
            'search' => $search,
            'contentFilter' => $request->input('filter'),
        ]);
    }

    // ===================== [UPDATE] =====================
    /**
     * Toggle recipe publish status.
     */
    public function toggleRecipePublish(Request $request, Recipe $recipe)
    {
        $recipe->update(['is_published' => !$recipe->is_published]);
        $status = $recipe->is_published ? 'published' : 'unpublished';

        ActivityLog::log(
            'content_moderated',
            "Admin {$status} recipe: {$recipe->title} (by {$recipe->cooker->name})",
            $request->user()->id,
            $recipe->cooker_id,
            $request->ip()
        );

        return back()->with('success', "Recipe \"{$recipe->title}\" has been {$status}.");
    }

    // ===================== [UPDATE] =====================
    /**
     * Toggle service availability.
     */
    public function toggleServiceAvailability(Request $request, CookingService $service)
    {
        $service->update(['is_available' => !$service->is_available]);
        $status = $service->is_available ? 'enabled' : 'disabled';

        ActivityLog::log(
            'content_moderated',
            "Admin {$status} service: {$service->title} (by {$service->cooker->name})",
            $request->user()->id,
            $service->cooker_id,
            $request->ip()
        );

        return back()->with('success', "Service \"{$service->title}\" has been {$status}.");
    }

    // ===================== [DELETE] =====================
    /**
     * Delete a recipe (admin).
     */
    public function deleteRecipe(Request $request, Recipe $recipe)
    {
        $title = $recipe->title;
        $cookerName = $recipe->cooker->name;

        ActivityLog::log(
            'recipe_deleted',
            "Admin deleted recipe: {$title} (by {$cookerName})",
            $request->user()->id,
            $recipe->cooker_id,
            $request->ip()
        );

        $recipe->delete();

        return back()->with('success', "Recipe \"{$title}\" has been deleted.");
    }

    // ===================== [DELETE] =====================
    /**
     * Delete a service (admin).
     */
    public function deleteService(Request $request, CookingService $service)
    {
        $title = $service->title;
        $cookerName = $service->cooker->name;

        ActivityLog::log(
            'service_deleted',
            "Admin deleted service: {$title} (by {$cookerName})",
            $request->user()->id,
            $service->cooker_id,
            $request->ip()
        );

        $service->delete();

        return back()->with('success', "Service \"{$title}\" has been deleted.");
    }

    // ===================== [READ] =====================
    /**
     * Wallet transactions audit.
     */
    public function transactions(Request $request)
    {
        $query = WalletTransaction::with('user')->latest();

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($ref = $request->input('reference')) {
            $query->where('reference_type', $ref);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(20)->withQueryString();

        $totalCredits = (float) WalletTransaction::where('type', 'credit')->sum('amount');
        $totalDebits = (float) WalletTransaction::where('type', 'debit')->sum('amount');

        return view('admin.transactions', [
            'user' => $request->user(),
            'transactions' => $transactions,
            'typeFilter' => $type ?? null,
            'referenceFilter' => $ref ?? null,
            'search' => $search ?? null,
            'totalCredits' => $totalCredits,
            'totalDebits' => $totalDebits,
        ]);
    }

    // ===================== [READ] =====================
    /**
     * Activity log page.
     */
    public function activityLog(Request $request)
    {
        $query = ActivityLog::with('user')->latest('created_at');

        if ($action = $request->input('action')) {
            $query->where('action', $action);
        }

        $activities = $query->paginate(20)->withQueryString();

        return view('admin.activity-log', [
            'user' => $request->user(),
            'activities' => $activities,
            'actionFilter' => $action ?? null,
        ]);
    }

    /**
     * Process wallet refund when an order is cancelled.
     */
    private function processOrderCancellationRefund(ServiceOrder $order): void
    {
        $buyerDebitTx = WalletTransaction::where('reference_type', 'service_order')
            ->where('reference_id', $order->id)
            ->where('type', 'debit')
            ->first();

        $sellerCreditTx = WalletTransaction::where('reference_type', 'sale_credit')
            ->where('reference_id', $order->id)
            ->where('type', 'credit')
            ->first();

        if (!$buyerDebitTx || !$sellerCreditTx) {
            return;
        }

        DB::transaction(function () use ($order, $buyerDebitTx, $sellerCreditTx) {
            $buyer = $buyerDebitTx->user;
            $seller = $sellerCreditTx->user;

            $buyer->increment('wallet_balance', $buyerDebitTx->amount);
            $seller->decrement('wallet_balance', $sellerCreditTx->amount);

            WalletTransaction::create([
                'user_id' => $buyer->id,
                'type' => 'credit',
                'amount' => $buyerDebitTx->amount,
                'currency' => $buyerDebitTx->currency,
                'reference_type' => 'refund',
                'reference_id' => $order->id,
                'description' => "Refund for cancellation of order #{$order->id}",
            ]);

            WalletTransaction::create([
                'user_id' => $seller->id,
                'type' => 'debit',
                'amount' => $sellerCreditTx->amount,
                'currency' => $sellerCreditTx->currency,
                'reference_type' => 'refund',
                'reference_id' => $order->id,
                'description' => "Reversal of funds for cancelled order #{$order->id}",
            ]);
        });
    }
}
