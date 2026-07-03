<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'target_id',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * The user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The target user affected by the action.
     */
    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_id');
    }

    /**
     * Log an activity.
     */
    public static function log(string $action, string $description, ?int $userId = null, ?int $targetId = null, ?string $ip = null): self
    {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'target_id' => $targetId,
            'ip_address' => $ip,
            'created_at' => now(),
        ]);
    }

    /**
     * Scope: filter by action type.
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Get the icon for this action type.
     */
    public function getIconAttribute(): string
    {
        return match ($this->action) {
            'login' => '🔑',
            'logout' => '🚪',
            'register' => '📝',
            'role_changed' => '🔄',
            'user_updated' => '✏️',
            'user_deleted' => '🗑️',
            'recipe_created' => '🥘',
            'recipe_deleted' => '🗑️',
            'recipe_purchased' => '💳',
            'service_created' => '🍽️',
            'service_deleted' => '🗑️',
            'service_ordered' => '📦',
            'order_status_updated' => '🔄',
            'user_suspended' => '🚫',
            'content_moderated' => '🛡️',
            default => '📋',
        };
    }

    /**
     * Get the color class for this action type.
     */
    public function getColorClassAttribute(): string
    {
        return match ($this->action) {
            'login' => 'green',
            'logout' => 'gray',
            'register' => 'blue',
            'role_changed' => 'orange',
            'user_updated' => 'purple',
            'user_deleted' => 'red',
            'recipe_created' => 'green',
            'recipe_deleted' => 'red',
            'recipe_purchased' => 'blue',
            'service_created' => 'green',
            'service_deleted' => 'red',
            'service_ordered' => 'purple',
            'order_status_updated' => 'orange',
            'user_suspended' => 'red',
            'content_moderated' => 'purple',
            default => 'gray',
        };
    }
}
