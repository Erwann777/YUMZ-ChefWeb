<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    protected $fillable = ['customer_id', 'cooker_id'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function cooker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cooker_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at');
    }

    public function latestMessage(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->latest()->limit(1);
    }

    /**
     * Get the other participant in the chat.
     */
    public function getPartner(User $user): User
    {
        return $user->id === $this->customer_id ? $this->cooker : $this->customer;
    }

    /**
     * Check if a given user is authorized to access this room.
     */
    public function hasParticipant(User $user): bool
    {
        return $user->id === $this->customer_id || $user->id === $this->cooker_id;
    }
}
