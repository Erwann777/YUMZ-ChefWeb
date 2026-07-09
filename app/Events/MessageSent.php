<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ChatMessage $message)
    {
        $this->message->load(['sender', 'replyTo.sender']);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->message->chat_room_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        $sender = $this->message->sender;
        return [
            'id'              => $this->message->id,
            'chat_room_id'    => $this->message->chat_room_id,
            'sender_id'       => $this->message->sender_id,
            'sender_name'     => $sender->name,
            'sender_initials' => $sender->getInitials(),
            'sender_photo'    => $sender->getProfilePhotoUrl(),
            'message'         => $this->message->message,
            'attachment_url'  => $this->message->attachment_url,
            'attachment_type' => $this->message->attachment_type,
            'created_at'      => $this->message->created_at->toISOString(),
            'created_at_ts'   => $this->message->created_at->timestamp,
            'time'            => $this->message->created_at->format('H:i'),
            'is_deleted'      => false,
            'edited_at'       => null,
            'reply_to'        => $this->message->replyTo ? $this->message->replyTo->replyPreview() : null,
        ];
    }
}
