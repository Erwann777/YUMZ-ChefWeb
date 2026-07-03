<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CookingService extends Model
{
    protected $fillable = [
        'cooker_id', 'title', 'description', 'price', 'is_available',
        'image_path', 'category', 'is_halal', 'currency',
    ];

    protected $casts = [
        'price'        => 'decimal:2',
        'is_available' => 'boolean',
        'is_halal'     => 'boolean',
    ];

    public function cooker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cooker_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'service_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'service_id')->whereNotNull('rating');
    }

    public function averageRating(): float
    {
        return (float) ($this->reviews()->avg('rating') ?? 0);
    }

    public function getImageUrl(): ?string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return null;
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Get currency symbol for this service's price.
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
     * Get formatted price with correct currency symbol.
     */
    public function getFormattedPriceAttribute(): string
    {
        $symbol = $this->getCurrencySymbol();
        return match($this->currency ?? 'IDR') {
            'IDR'   => $symbol . ' ' . number_format($this->price, 0, ',', '.'),
            default => $symbol . number_format($this->price, 2, '.', ','),
        };
    }
}
