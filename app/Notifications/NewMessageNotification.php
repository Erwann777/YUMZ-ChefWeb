<?php

namespace App\Notifications;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ChatMessage $message,
        public ChatRoom $room
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        $sender = $this->message->sender;
        $snippet = $this->message->message
            ? (strlen($this->message->message) > 60 ? substr($this->message->message, 0, 60) . '…' : $this->message->message)
            : ($this->message->attachment_type === 'image' ? '📷 Sent a photo' : '🎥 Sent a video');

        return [
            'type'        => 'chat',
            'icon'        => '💬',
            'title'       => 'New message from ' . $sender->name,
            'body'        => $snippet,
            'url'         => url('/chat/' . $this->room->id),
            'sender_id'   => $sender->id,
            'room_id'     => $this->room->id,
        ];
    }

    public function toBroadcast(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}
