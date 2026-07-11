<?php

namespace App\Notifications;

use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ServiceOrder $order,
        public User $customer
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'order',
            'icon'       => '',
            'title'      => 'New order from ' . $this->customer->name,
            'body'       => "Order #{$this->order->id}: {$this->order->service->title}",
            'url'        => url('/cooker/dashboard'),
            'order_id'   => $this->order->id,
        ];
    }

    public function toBroadcast(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}
