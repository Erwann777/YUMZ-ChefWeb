<?php

use App\Models\ChatRoom;
use Illuminate\Support\Facades\Broadcast;

// Laravel default user notification channel
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private chat room channel — only accessible to participants
Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    $room = ChatRoom::find($roomId);
    return $room && $room->hasParticipant($user);
});
