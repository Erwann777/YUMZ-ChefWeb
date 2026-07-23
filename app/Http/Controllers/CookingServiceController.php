<?php

namespace App\Http\Controllers;

use App\Models\CookingService;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CookingServiceController extends Controller
{
    /**
    * Display the services dashboard for customers.
    */
    // ===================== [READ] =====================
    public function index()
    {
        // Hot services (rating 4-5 and high sales)
        $hotRecommendations = CookingService::available()
            ->with('cooker')
            ->withCount(['orders as rated_sales' => function ($query) {
                $query->where('rating', '>=', 4);
            }])
            ->withCount('orders')
            ->orderByDesc('rated_sales')
            ->orderByDesc('orders_count')
            ->take(6)
            ->get();

        // All services with optional search
        $servicesQuery = CookingService::available()->with('cooker');
        $search = request('search');
        if ($search) {
            $servicesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('cooker', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        } else {
            $servicesQuery->inRandomOrder();
        }
        $services = $servicesQuery->get();

        // Top cookers
        $cookers = User::where('role', 'cooker')
            ->with(['cookingServices' => function ($q) {
                $q->available()->with('orders');
            }])
            ->get()
            ->map(function ($cooker) {
                $totalSales = $cooker->cookingServices->sum(function ($service) {
                    return $service->orders->count();
                });
                $ratingSum = 0;
                $ratedCount = 0;
                foreach ($cooker->cookingServices as $service) {
                    foreach ($service->orders as $order) {
                        if ($order->rating !== null) {
                            $ratingSum += $order->rating;
                            $ratedCount++;
                        }
                    }
                }
                $cooker->calculated_sales = $totalSales;
                $cooker->calculated_rating = $ratedCount > 0 ? round($ratingSum / $ratedCount, 1) : 0.0;
                return $cooker;
            })
            ->sortByDesc(function ($c) {
                return [$c->calculated_rating, $c->calculated_sales];
            })
            ->take(15);

        return view('dashboard', [
            'user' => request()->user(),
            'hotRecommendations' => $hotRecommendations,
            'services' => $services,
            'cookers' => $cookers,
        ]);
    }
    // ===================== [READ] =====================
    public function create()
    {
        return view('cooker.services.create', [
            'user' => Auth::user(),
        ]);
    }

    // ===================== [CREATE] =====================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'category' => ['required', 'string', 'in:indonesia,malaysian,chinese,japanese,korean,thailand,indian,italian,american,french,british,dessert'],
            'is_halal' => ['required', 'boolean'],
        ]);

        $imagePath = $request->file('image')->store('services', 'public');

        $service = CookingService::create([
            'cooker_id'   => Auth::id(),
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'price'       => $validated['price'],
            'image_path'  => $imagePath,
            'category'    => $validated['category'],
            'is_halal'    => $validated['is_halal'],
            'currency'    => Auth::user()->currency ?? 'IDR', // Auto-set from cooker's country
        ]);

        ActivityLog::log(
            'service_created',
            Auth::user()->name . " created service: {$service->title}",
            Auth::id(), null, $request->ip()
        );

        return redirect()->route('cooker.dashboard')->with('success', "Service \"{$service->title}\" created successfully!");
    }

    // ===================== [READ] =====================
    public function edit(CookingService $service)
    {
        if ($service->cooker_id !== Auth::id()) {
            abort(403);
        }

        return view('cooker.services.edit', [
            'user' => Auth::user(),
            'service' => $service,
        ]);
    }

    // ===================== [UPDATE] =====================
    public function update(Request $request, CookingService $service)
    {
        if ($service->cooker_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_available' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'category' => ['required', 'string', 'in:indonesia,malaysian,chinese,japanese,korean,thailand,indian,italian,american,french,british,dessert'],
            'is_halal' => ['required', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($service->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($service->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }

        $service->update([
            'title'        => $validated['title'],
            'description'  => $validated['description'],
            'price'        => $validated['price'],
            'is_available' => $request->boolean('is_available', true),
            'image_path'   => $validated['image_path'] ?? $service->image_path,
            'category'     => $validated['category'],
            'is_halal'     => $validated['is_halal'],
            'currency'     => Auth::user()->currency ?? 'IDR',
        ]);

        return redirect()->route('cooker.dashboard')->with('success', "Service \"{$service->title}\" updated successfully!");
    }

    // ===================== [DELETE] =====================
    public function destroy(Request $request, CookingService $service)
    {
        if ($service->cooker_id !== Auth::id()) {
            abort(403);
        }

        if ($service->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($service->image_path);
        }

        $title = $service->title;

        ActivityLog::log(
            'service_deleted',
            Auth::user()->name . " deleted service: {$title}",
            Auth::id(), null, $request->ip()
        );

        $service->delete();

        return redirect()->route('cooker.dashboard')->with('success', "Service \"{$title}\" deleted successfully.");
    }
}
