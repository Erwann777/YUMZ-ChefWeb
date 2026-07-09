<?php

namespace App\Notifications;

use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ServiceOrder $order,
        public User $cooker
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'order_done',
            'icon'       => '✅',
            'title'      => 'Your food is ready! 🍽️',
            'body'       => "{$this->cooker->name} has finished your order: {$this->order->service->title}",
            'url'        => url('/dashboard?tab=orders'),
            'order_id'   => $this->order->id,
        ];
    }

    public function toBroadcast(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}
