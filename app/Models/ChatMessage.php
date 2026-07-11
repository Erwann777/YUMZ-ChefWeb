<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'chat_room_id',
        'sender_id',
        'message',
        'attachment_path',
        'attachment_type',
        'is_deleted',
        'edited_at',
        'reply_to_id',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'edited_at'  => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'reply_to_id');
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment_path ? asset('storage/' . $this->attachment_path) : null;
    }

    public function isImage(): bool
    {
        return $this->attachment_type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->attachment_type === 'video';
    }

    public function canEdit(): bool
    {
        return !$this->is_deleted
            && !$this->attachment_type
            && $this->created_at->diffInSeconds(now()) <= 30;
    }

    /**
     * Return a compact array for use as a "reply preview" in another message.
     */
    public function replyPreview(): array
    {
        if ($this->is_deleted) {
            return ['id' => $this->id, 'sender_name' => '?', 'snippet' => 'This message was deleted', 'type' => 'deleted'];
        }
        return [
            'id'          => $this->id,
            'sender_name' => $this->sender?->name ?? 'Unknown',
            'snippet'     => $this->attachment_type
                                ? ($this->attachment_type === 'image' ? ' Photo' : ' Video')
                                : \Str::limit($this->message ?? '', 80),
            'type'        => $this->attachment_type ?? 'text',
        ];
    }
}
