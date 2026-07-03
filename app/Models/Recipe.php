<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    protected $fillable = [
        'cooker_id', 'title', 'description', 'image_path',
        'ingredients', 'steps', 'price', 'is_published',
        'category', 'is_halal', 'currency',
    ];

    protected $casts = [
        'price'        => 'decimal:2',
        'is_published' => 'boolean',
        'is_halal'     => 'boolean',
    ];

    public function cooker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cooker_id');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(RecipePurchase::class);
    }

    /**
     * Check if a customer has purchased this recipe.
     */
    public function isPurchasedBy(?User $user): bool
    {
        if (!$user) return false;
        if ($user->id === $this->cooker_id) return true; // Cooker owns it
        return $this->purchases()->where('customer_id', $user->id)->exists();
    }

    /**
     * Scope: only published recipes.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get currency symbol for this recipe's price.
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

    /**
     * Get only rated purchases.
     */
    public function reviews()
    {
        return $this->hasMany(RecipePurchase::class, 'recipe_id')->whereNotNull('rating');
    }

    /**
     * Get average rating of the recipe.
     */
    public function averageRating(): float
    {
        return (float) ($this->reviews()->avg('rating') ?? 0);
    }
}
