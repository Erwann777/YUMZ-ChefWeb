<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrder extends Model
{
    protected $fillable = [
        'customer_id', 'service_id', 'cooker_id',
        'status', 'notes', 'total_price',
        'rating', 'review', 'rated_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'rated_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(CookingService::class, 'service_id');
    }

    public function cooker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cooker_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '',
            'confirmed' => '',
            'completed' => '',
            'cancelled' => '',
            default => '',
        };
    }

    public function getFormattedPriceAttribute(): string
    {
        $currency = $this->customer->currency ?? 'IDR';
        return match($currency) {
            'SGD' => 'S$' . number_format($this->total_price, 2, '.', ','),
            'MYR' => 'RM' . number_format($this->total_price, 2, '.', ','),
            default => 'Rp ' . number_format($this->total_price, 0, ',', '.'),
        };
    }
}
