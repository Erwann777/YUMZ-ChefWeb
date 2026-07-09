<?php

namespace App\Http\Controllers;

use App\Events\MessageDeleted;
use App\Events\MessageEdited;
use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $relation = $user->isCooker() ? 'cooker_id' : 'customer_id';
        $with     = $user->isCooker() ? ['customer', 'messages' => fn($q) => $q->latest()->limit(1)]
                                      : ['cooker',   'messages' => fn($q) => $q->latest()->limit(1)];

        $rooms = ChatRoom::where($relation, $user->id)
            ->with($with)
            ->orderByDesc(fn($q) =>
                $q->select('created_at')->from('chat_messages')
                  ->whereColumn('chat_room_id', 'chat_rooms.id')->latest()->limit(1)
            )->get();

        $unreadRoomIds = $user->unreadNotifications
            ->filter(fn($n) => isset($n->data['type']) && $n->data['type'] === 'chat')
            ->pluck('data.room_id')
            ->unique()
            ->toArray();

        return view('chat.index', compact('user', 'rooms', 'unreadRoomIds'));
    }

    public function show(ChatRoom $room)
    {
        $user = Auth::user();
        if (!$room->hasParticipant($user)) abort(403);

        // Mark chat notifications for this room as read immediately
        $user->unreadNotifications
            ->filter(fn($n) => isset($n->data['room_id']) && (int)$n->data['room_id'] === $room->id)
            ->each(fn($n) => $n->markAsRead());

        $room->load(['customer', 'cooker', 'messages.sender', 'messages.replyTo.sender']);
        $partner = $room->getPartner($user);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'room_id' => $room->id,
                'partner' => [
                    'name' => $partner->name,
                    'role' => $partner->isCooker() ? '👨‍🍳 Cooker' : '🧑 Customer',
                    'avatar' => $partner->profile_photo_path ? $partner->getProfilePhotoUrl() : null,
                    'initials' => $partner->getInitials(),
                    'profile_url' => ($user->isCustomer() && $partner->isCooker()) ? route('cookers.show', $partner) : null,
                ],
                'messages' => $room->messages->map(fn($msg) => $this->msgToArray($msg, $user)),
            ]);
        }

        $sideRelation = $user->isCooker() ? 'cooker_id' : 'customer_id';
        $rooms = ChatRoom::where($sideRelation, $user->id)
            ->with($user->isCooker() ? ['customer', 'messages' => fn($q) => $q->latest()->limit(1)]
                                     : ['cooker',   'messages' => fn($q) => $q->latest()->limit(1)])
            ->orderByDesc(fn($q) =>
                $q->select('created_at')->from('chat_messages')
                  ->whereColumn('chat_room_id', 'chat_rooms.id')->latest()->limit(1)
            )->get();

        $unreadRoomIds = $user->unreadNotifications
            ->filter(fn($n) => isset($n->data['type']) && $n->data['type'] === 'chat')
            ->pluck('data.room_id')
            ->unique()
            ->toArray();

        return view('chat.index', compact('user', 'room', 'partner', 'rooms', 'unreadRoomIds'));
    }

    public function startChat(User $cooker)
    {
        $user = Auth::user();
        if ($user->id === $cooker->id)           return back()->with('error', 'You cannot chat with yourself.');
        if ($user->isCooker() && !$user->isAdmin()) return back()->with('error', 'Cookers cannot initiate chats.');

        $room = ChatRoom::firstOrCreate(['customer_id' => $user->id, 'cooker_id' => $cooker->id]);
        return redirect()->route('chat.show', $room);
    }

    public function sendMessage(Request $request, ChatRoom $room)
    {
        $user = Auth::user();
        if (!$room->hasParticipant($user)) abort(403);

        $request->validate([
            'message'     => ['nullable', 'string', 'max:2000'],
            'attachment'  => ['nullable', 'file', 'mimes:jpeg,png,gif,webp,mp4,mov,avi,webm', 'max:51200'],
            'reply_to_id' => ['nullable', 'integer', 'exists:chat_messages,id'],
        ]);

        if (!$request->filled('message') && !$request->hasFile('attachment')) {
            return response()->json(['error' => 'Message or attachment is required.'], 422);
        }

        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $mime = $file->getMimeType();
            if (str_starts_with($mime, 'image/')) {
                $attachmentType = 'image';
                $attachmentPath = $file->store('chat_attachments', 'public');
            } elseif (str_starts_with($mime, 'video/')) {
                $attachmentType = 'video';
                $attachmentPath = $file->store('chat_attachments', 'public');
            }
        }

        $message = ChatMessage::create([
            'chat_room_id'    => $room->id,
            'sender_id'       => $user->id,
            'message'         => $request->input('message'),
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
            'reply_to_id'     => $request->input('reply_to_id'),
        ]);

        $message->load(['sender', 'replyTo.sender']);

        try { broadcast(new MessageSent($message))->toOthers(); } catch (\Exception) {}

        $recipient = $room->getPartner($user);
        try { $recipient->notify(new NewMessageNotification($message, $room)); } catch (\Exception) {}

        return response()->json($this->msgToArray($message, $user));
    }

    public function deleteMessage(ChatMessage $message)
    {
        $user = Auth::user();
        if ($message->sender_id !== $user->id) return response()->json(['error' => 'Unauthorized.'], 403);

        $message->update(['is_deleted' => true, 'message' => null]);

        try { broadcast(new MessageDeleted($message))->toOthers(); } catch (\Exception) {}

        return response()->json(['success' => true, 'id' => $message->id]);
    }

    public function editMessage(Request $request, ChatMessage $message)
    {
        $user = Auth::user();
        if ($message->sender_id !== $user->id)     return response()->json(['error' => 'Unauthorized.'], 403);
        if ($message->is_deleted)                  return response()->json(['error' => 'Cannot edit a deleted message.'], 422);
        if ($message->attachment_type)             return response()->json(['error' => 'Cannot edit media messages.'], 422);
        if ($message->created_at->diffInSeconds(now()) > 30)
            return response()->json(['error' => 'Edit window has expired (30 seconds).'], 422);

        $request->validate(['message' => ['required', 'string', 'max:2000']]);
        $message->update(['message' => $request->input('message'), 'edited_at' => now()]);

        try { broadcast(new MessageEdited($message))->toOthers(); } catch (\Exception) {}

        return response()->json(['success' => true, 'id' => $message->id, 'message' => $message->message, 'edited_at' => $message->edited_at->format('H:i')]);
    }

    public function getMessages(Request $request, ChatRoom $room)
    {
        $user = Auth::user();
        if (!$room->hasParticipant($user)) abort(403);

        $messages = ChatMessage::where('chat_room_id', $room->id)
            ->where('id', '>', $request->integer('last_id', 0))
            ->with(['sender', 'replyTo.sender'])
            ->orderBy('id')
            ->get()
            ->map(fn($msg) => $this->msgToArray($msg, $user));

        return response()->json($messages);
    }

    /* ── Shared serialiser ── */
    private function msgToArray(ChatMessage $msg, User $user): array
    {
        return [
            'id'              => $msg->id,
            'chat_room_id'    => $msg->chat_room_id,
            'sender_id'       => $msg->sender_id,
            'sender_name'     => $msg->sender->name,
            'sender_initials' => $msg->sender->getInitials(),
            'sender_photo'    => $msg->sender->getProfilePhotoUrl(),
            'message'         => $msg->message,
            'attachment_url'  => $msg->attachment_url,
            'attachment_type' => $msg->attachment_type,
            'time'            => $msg->created_at->format('H:i'),
            'created_at_ts'   => $msg->created_at->timestamp,
            'is_deleted'      => $msg->is_deleted,
            'edited_at'       => $msg->edited_at?->format('H:i'),
            'reply_to'        => $msg->replyTo ? $msg->replyTo->replyPreview() : null,
        ];
    }
}
