<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Fetch all notifications for the current user via AJAX.
     */
    public function index()
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(function ($n) {
                return [
                    'id'        => $n->id,
                    'data'      => $n->data,
                    'read'      => !is_null($n->read_at),
                    'read_at'   => $n->read_at?->toISOString(),
                    'created_at'=> $n->created_at->toISOString(),
                    'time_ago'  => $n->created_at->diffForHumans(),
                ];
            });

        $unreadCount = $user->unreadNotifications()->count();

        $unreadChatCount = $user->unreadNotifications
            ->filter(fn($n) => isset($n->data['type']) && $n->data['type'] === 'chat')
            ->pluck('data.sender_id')
            ->unique()
            ->count();

        return response()->json([
            'notifications'     => $notifications,
            'unread_count'      => $unreadCount,
            'unread_chat_count' => $unreadChatCount,
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    /**
     * Mark a single notification as read and redirect to the URL.
     */
    public function markRead(string $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            $url = $notification->data['url'] ?? route('dashboard');
            return redirect($url);
        }

        return redirect()->route('dashboard');
    }
}
