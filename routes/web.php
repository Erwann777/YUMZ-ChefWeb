<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CookerDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CookingServiceController;
use App\Http\Controllers\CookerProfileController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Api\CurrencyController;    
use App\Models\User;
use App\Models\Recipe;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $cookers = User::where('role', 'cooker')
        ->withCount(['recipes', 'cookingServices', 'followers'])
        ->latest()
        ->take(8)
        ->get();

    $services = \App\Models\CookingService::available()
        ->with('cooker')
        ->latest()
        ->take(6)
        ->get();

    $recipes = \App\Models\Recipe::published()
        ->with('cooker')
        ->latest()
        ->take(6)
        ->get();

    $featuredRecipe = \App\Models\CookingService::available()
        ->with('cooker')
        ->inRandomOrder()
        ->first();

    return view('welcome', compact('cookers', 'services', 'recipes', 'featuredRecipe'));
})->name('welcome');

// Guest routes (login & register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Profile routes
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [UserProfileController::class, 'uploadPhoto'])->name('profile.photo');

    // Wallet routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::post('/wallet/topup', [WalletController::class, 'topUp'])->name('wallet.topup');

    // Currency API routes (for AJAX)
    Route::get('/api/currency/rates', [CurrencyController::class, 'rates'])->name('api.currency.rates');
    Route::get('/api/currency/convert', [CurrencyController::class, 'convert'])->name('api.currency.convert');

    // Browse cookers & foods (accessible by all authenticated users)
    Route::get('/foods', [CookerProfileController::class, 'allFoods'])->name('foods.index');
    Route::get('/cookers', [CookerProfileController::class, 'index'])->name('cookers.index');
    Route::post('/cookers/{cooker}/toggle-follow', [CookerProfileController::class, 'toggleFollow'])->name('cookers.toggle-follow');
    Route::get('/cookers/{cooker}', [CookerProfileController::class, 'show'])->name('cookers.show');
    Route::get('/cookers/{cooker}/recipes/{recipe}', [CookerProfileController::class, 'showRecipe'])->name('cookers.recipe');
    Route::get('/cookers/{cooker}/services/{service}', [CookerProfileController::class, 'showService'])->name('cookers.service');
    Route::post('/recipes/{recipe}/purchase', [CookerProfileController::class, 'purchaseRecipe'])->name('recipes.purchase');
    Route::post('/recipes/{recipe}/rate', [CookerProfileController::class, 'rateRecipe'])->name('recipes.rate');
    Route::post('/orders/{order}/rate', [CookerProfileController::class, 'rateOrder'])->name('services.rate');
    Route::get('/cookers/{cooker}/services/{service}/order', [CookerProfileController::class, 'orderServiceForm'])->name('services.order');
    Route::post('/services/{service}/order', [CookerProfileController::class, 'orderService'])->name('services.order.store');
    Route::get('/api/orders/{order}/status', [DashboardController::class, 'getOrderStatus'])->name('api.orders.status');

    // Chat routes
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/start/{cooker}', [ChatController::class, 'startChat'])->name('chat.start');
    Route::get('/chat/{room}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{room}/messages', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/api/chat/{room}/messages', [ChatController::class, 'getMessages'])->name('api.chat.messages');
    Route::delete('/chat/messages/{message}', [ChatController::class, 'deleteMessage'])->name('chat.message.delete');
    Route::patch('/chat/messages/{message}', [ChatController::class, 'editMessage'])->name('chat.message.edit');

    // Notification routes
    Route::get('/api/notifications', [NotificationController::class, 'index'])->name('api.notifications');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');

    // Cooker routes
    Route::middleware('role:cooker')->prefix('cooker')->name('cooker.')->group(function () {
        Route::get('/dashboard', [CookerDashboardController::class, 'index'])->name('dashboard');

        // Recipe CRUD
        Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
        Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
        Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
        Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
        Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

        // Service CRUD
        Route::get('/services/create', [CookingServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [CookingServiceController::class, 'store'])->name('services.store');
        Route::get('/services/{service}/edit', [CookingServiceController::class, 'edit'])->name('services.edit');
        Route::put('/services/{service}', [CookingServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service}', [CookingServiceController::class, 'destroy'])->name('services.destroy');

        // Order status update
        Route::put('/orders/{order}/status', [CookerDashboardController::class, 'updateOrderStatus'])->name('orders.update-status');
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('/users/{targetUser}/edit', [AdminDashboardController::class, 'editUser'])->name('users.edit');
        Route::get('/users/{targetUser}', [AdminDashboardController::class, 'showUser'])->name('users.show');
        Route::put('/users/{targetUser}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
        Route::post('/users/{targetUser}/toggle-suspend', [AdminDashboardController::class, 'toggleSuspendUser'])->name('users.toggle-suspend');
        Route::delete('/users/{targetUser}', [AdminDashboardController::class, 'deleteUser'])->name('users.delete');
        Route::get('/orders', [AdminDashboardController::class, 'orders'])->name('orders');
        Route::put('/orders/{order}/status', [AdminDashboardController::class, 'updateOrderStatus'])->name('orders.update-status');
        Route::get('/content', [AdminDashboardController::class, 'content'])->name('content');
        Route::put('/recipes/{recipe}/toggle-publish', [AdminDashboardController::class, 'toggleRecipePublish'])->name('recipes.toggle-publish');
        Route::put('/services/{service}/toggle-availability', [AdminDashboardController::class, 'toggleServiceAvailability'])->name('services.toggle-availability');
        Route::delete('/recipes/{recipe}', [AdminDashboardController::class, 'deleteRecipe'])->name('recipes.delete');
        Route::delete('/services/{service}', [AdminDashboardController::class, 'deleteService'])->name('services.delete');
        Route::get('/transactions', [AdminDashboardController::class, 'transactions'])->name('transactions');
        Route::get('/activity-log', [AdminDashboardController::class, 'activityLog'])->name('activity-log');
    });
});
