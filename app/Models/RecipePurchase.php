<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipePurchase extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'customer_id', 'recipe_id', 'amount_paid', 'created_at',
        'rating', 'review', 'rated_at',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'created_at' => 'datetime',
        'rated_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
