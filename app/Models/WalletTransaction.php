<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'currency',
        'reference_type',
        'reference_id',
        'original_amount',
        'original_currency',
        'exchange_rate',
        'description',
    ];

    protected $casts = [
        'amount'          => 'decimal:2',
        'original_amount' => 'decimal:2',
        'exchange_rate'   => 'decimal:6',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check whether this transaction involved a currency conversion.
     */
    public function hadConversion(): bool
    {
        return $this->original_currency && $this->original_currency !== $this->currency;
    }

    /**
     * Get a human-readable type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'credit' ? 'Masuk' : 'Keluar';
    }

    /**
     * Get reference type label.
     */
    public function getReferenceLabelAttribute(): string
    {
        return match ($this->reference_type) {
            'recipe_purchase' => '🍽️ Pembelian Resep',
            'service_order'   => '👨‍🍳 Pemesanan Service',
            'initial_credit'  => '🎁 Saldo Awal',
            'topup'           => '💰 Top-Up',
            'sale_credit'     => '💵 Hasil Penjualan',
            'refund'          => '↩️ Pengembalian Dana',
            default           => '📋 Transaksi',
        };
    }
}
