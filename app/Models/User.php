<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_suspended',
        'profile_photo_path',
        'bio',
        'phone',
        'country',
        'currency',
        'wallet_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'wallet_balance'    => 'decimal:2',
            'is_suspended'      => 'boolean',
        ];
    }

    public function isSuspended(): bool
    {
        return (bool) $this->is_suspended;
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a cooker.
     */
    public function isCooker(): bool
    {
        return $this->role === 'cooker';
    }

    /**
     * Check if the user is a customer.
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Get the profile photo URL or fallback to null (use initials in blade).
     */
    public function getProfilePhotoUrl(): ?string
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        return null;
    }

    /**
     * Get a human-readable role label.
     */
    public function getRoleLabel(): string
    {
        return match($this->role) {
            'admin'    => 'Admin',
            'cooker'   => 'Cooker',
            'customer' => 'Customer',
            default    => 'User',
        };
    }

    /**
     * Get the first letter(s) for the avatar initials.
     */
    public function getInitials(): string
    {
        $parts = explode(' ', trim($this->name));
        if (count($parts) >= 2) {
            return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    // ── Marketplace Relations ──

    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'cooker_id');
    }

    public function cookingServices()
    {
        return $this->hasMany(CookingService::class, 'cooker_id');
    }

    public function recipePurchases()
    {
        return $this->hasMany(RecipePurchase::class, 'customer_id');
    }

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class, 'customer_id');
    }

    public function cookerOrders()
    {
        return $this->hasMany(ServiceOrder::class, 'cooker_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'cooker_id', 'follower_id')->withTimestamps();
    }

    public function followingCookers()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'cooker_id')->withTimestamps();
    }

    public function isFollowing(User $cooker): bool
    {
        return $this->followingCookers()->where('cooker_id', $cooker->id)->exists();
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function customerChatRooms()
    {
        return $this->hasMany(ChatRoom::class, 'customer_id');
    }

    public function cookerChatRooms()
    {
        return $this->hasMany(ChatRoom::class, 'cooker_id');
    }

    /**
     * Get total cooker service sales.
     */
    public function totalSalesCount(): int
    {
        return $this->cookerOrders()->count();
    }

    /**
     * Get average cooker rating from reviews on their services.
     */
    public function averageCookerRating(): float
    {
        return (float) ($this->cookerOrders()->whereNotNull('rating')->avg('rating') ?? 0);
    }

    // ── Currency / Wallet Helpers ──

    /**
     * Get the currency symbol for display.
     */
    public function getCurrencySymbol(): string
    {
        return match($this->currency ?? 'IDR') {
            'SGD' => 'S$',
            'MYR' => 'RM',
            default => 'Rp',
        };
    }

    /**
     * Get the country flag emoji.
     */
    public function getCountryFlag(): string
    {
        return match($this->country ?? 'ID') {
            'SG' => '🇸🇬',
            'MY' => '🇲🇾',
            default => '🇮🇩',
        };
    }

    /**
     * Get the country name.
     */
    public function getCountryName(): string
    {
        return match($this->country ?? 'ID') {
            'SG' => 'Singapore',
            'MY' => 'Malaysia',
            default => 'Indonesia',
        };
    }

    public function getFormattedWalletBalance(): string
    {
        return $this->formatPrice((float) ($this->wallet_balance ?? 0));
    }

    /**
     * Format a given amount according to the user's currency.
     */
    public function formatPrice(float $amount): string
    {
        $symbol = $this->getCurrencySymbol();
        return match($this->currency ?? 'IDR') {
            'IDR' => $symbol . ' ' . number_format($amount, 0, ',', '.'),
            default => $symbol . number_format($amount, 2, '.', ','),
        };
    }

    /**
     * Get initial wallet balance for a given country (for new registrations).
     */
    public static function getInitialWalletBalance(string $country): float
    {
        return match($country) {
            'SG' => 1000.00,
            'MY' => 3000.00,
            default => 10000000.00, // Indonesia
        };
    }

    /**
     * Get currency code from country code.
     */
    public static function getCurrencyFromCountry(string $country): string
    {
        return match($country) {
            'SG' => 'SGD',
            'MY' => 'MYR',
            default => 'IDR',
        };
    }
}
