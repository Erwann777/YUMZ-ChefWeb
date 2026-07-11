@extends('layouts.app')
@section('title', isset($room) ? 'Chat — ' . $partner->name . ' · Yumz' : 'Messages · Yumz')

@section('styles')
<style>
/* ══════════════════════════════════════════════════
   RESET & ROOT
══════════════════════════════════════════════════ */
html, body { height: 100%; overflow: hidden; }

.chat-shell {
    position: fixed;
    inset: 0;
    top: 64px;  /* navbar height */
    display: flex;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: #EAE0D4;
}

/* ══════════════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════════════ */
.s-panel {
    width: 340px;
    min-width: 280px;
    max-width: 380px;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-right: 1px solid #E0D5C8;
    flex-shrink: 0;
    z-index: 10;
}
@media (max-width: 768px) {
    .s-panel {
        position: absolute;
        inset: 0;
        width: 100%;
        max-width: 100%;
        transform: translateX(0);
        transition: transform .3s ease;
        z-index: 20;
    }
    .s-panel.hide-mobile { transform: translateX(-100%); }
}

.s-header {
    padding: 1rem 1.1rem;
    background: linear-gradient(135deg, #C67C4E 0%, #9A5A2E 100%);
    display: flex; align-items: center; gap: .7rem;
    flex-shrink: 0;
}
.s-header-title { font-size: 1.05rem; font-weight: 700; color: #fff; }

.s-search {
    padding: .55rem .9rem;
    border-bottom: 1px solid #EFE9E0;
    flex-shrink: 0;
}
.s-search input {
    width: 100%; border: 1.5px solid #EDE5DA; background: #FAF6F0;
    border-radius: 22px; padding: .38rem .9rem; font-size: .82rem;
    color: #2C1810; outline: none; transition: border .18s;
}
.s-search input:focus { border-color: #C67C4E; }
.s-search input::placeholder { color: #BBA88C; }

.s-list {
    flex: 1; overflow-y: auto;
}
.s-list::-webkit-scrollbar { width: 4px; }
.s-list::-webkit-scrollbar-thumb { background: #E0D5C8; border-radius: 4px; }

.s-item {
    display: flex; align-items: center; gap: .75rem;
    padding: .78rem 1rem;
    border-bottom: 1px solid #F5EFE8;
    cursor: pointer; text-decoration: none;
    transition: background .12s;
    position: relative;
}
.s-item:hover  { background: #FDF6EF; }
.s-item.active { background: #F5EDE3; border-left: 3px solid #C67C4E; }

.s-av {
    width: 46px; height: 46px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, #C67C4E, #8B4513);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 700; font-size: .9rem; overflow: hidden;
}
.s-av img { width: 100%; height: 100%; object-fit: cover; }

.s-info { flex: 1; min-width: 0; }
.s-name {
    font-size: .875rem; font-weight: 600; color: #1E1209;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.s-snippet {
    font-size: .73rem; color: #9A8070; margin-top: 2px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.s-right { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }
.s-time { font-size: .67rem; color: #BBA88C; }
.s-dot {
    width: 9px; height: 9px; border-radius: 50%;
    background: #C67C4E; flex-shrink: 0;
}

/* ══════════════════════════════════════════════════
   CHAT PANEL (right side)
══════════════════════════════════════════════════ */
.c-panel {
    flex: 1; display: flex; flex-direction: column; overflow: hidden;
    background: #EAE0D4;
}

/* Empty welcome state */
.c-welcome {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: .7rem; color: #B89880;
    background: linear-gradient(160deg, #FFFBF7, #FFF6EE);
}
.c-welcome .big-icon { font-size: 4.5rem; filter: drop-shadow(0 6px 24px rgba(198,124,78,.2)); }
.c-welcome h2 { font-size: 1.35rem; font-weight: 700; color: #2C1810; }
.c-welcome p  { font-size: .88rem; color: #9A7B5A; max-width: 260px; text-align: center; line-height: 1.55; }

/* ── Chat header ── */
.c-header {
    background: linear-gradient(135deg, #C67C4E 0%, #9A5A2E 100%);
    padding: .7rem 1.15rem; display: flex; align-items: center; gap: .85rem;
    flex-shrink: 0; box-shadow: 0 2px 12px rgba(44,24,16,.22);
}
.c-back {
    display: none; color: rgba(255,255,255,.85); cursor: pointer;
    background: none; border: none; padding: 0 .3rem 0 0;
    flex-shrink: 0;
}
@media (max-width: 768px) { .c-back { display: flex; align-items: center; } }

.c-hav {
    width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
    background: rgba(255,255,255,.25); overflow: hidden;
    border: 2px solid rgba(255,255,255,.35);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 700; font-size: .9rem;
}
.c-hav img { width: 100%; height: 100%; object-fit: cover; }

.c-hinfo { flex: 1; }
.c-hname { font-weight: 700; color: #fff; font-size: .97rem; }
.c-hrole { font-size: .68rem; color: rgba(255,255,255,.7); margin-top: 1px; }

.c-hbtn {
    font-size: .73rem; background: rgba(255,255,255,.16); color: #fff;
    border: 1px solid rgba(255,255,255,.28); padding: .32rem .9rem;
    border-radius: 20px; text-decoration: none; font-weight: 600;
    transition: background .18s; flex-shrink: 0;
}
.c-hbtn:hover { background: rgba(255,255,255,.3); color: #fff; }

/* ── Messages area ── */
.c-msgs {
    flex: 1; overflow-y: auto; padding: .9rem 1.1rem 5.5rem;
    display: flex; flex-direction: column; gap: .15rem;
}
.c-msgs::-webkit-scrollbar { width: 5px; }
.c-msgs::-webkit-scrollbar-thumb { background: rgba(198,124,78,.28); border-radius: 10px; }

/* Date pill */
.date-pill {
    align-self: center;
    background: rgba(255,255,255,.78); backdrop-filter: blur(8px);
    border: 1px solid rgba(198,124,78,.14);
    color: #6B5D52; font-size: .67rem; font-weight: 500;
    padding: .18rem .85rem; border-radius: 20px;
    margin: .45rem 0; box-shadow: 0 1px 4px rgba(0,0,0,.06);
}

/* ── Message row ── */
.msg-row {
    display: flex; align-items: flex-end; gap: .35rem;
    max-width: 72%;
    animation: fadeUp .16s ease;
}
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.msg-row.sent     { align-self: flex-end;   flex-direction: row-reverse; }
.msg-row.received { align-self: flex-start; }

/* Small avatar (received) */
.m-av {
    width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, #C67C4E, #8B4513);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .57rem; font-weight: 700;
    overflow: hidden; margin-bottom: 22px;
}
.m-av img { width: 100%; height: 100%; object-fit: cover; }

/* Bubble column (bubble + side actions) */
.b-col {
    display: flex; align-items: center; gap: .28rem;
}
.msg-row.sent     .b-col { flex-direction: row-reverse; }
.msg-row.received .b-col { flex-direction: row; }

/* Side action (⋯ button) — hidden, appears on hover */
.b-actions {
    opacity: 0; transition: opacity .15s; flex-shrink: 0;
}
.msg-row:hover .b-actions { opacity: 1; }
.msg-row.show-actions .b-actions { opacity: 1 !important; }

.action-btn {
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(255,255,255,.9); border: 1px solid #DDD4C9;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: .75rem; color: #7A6B5D;
    box-shadow: 0 1px 4px rgba(0,0,0,.1);
    transition: all .15s; position: relative;
}
.action-btn:hover { background: #C67C4E; color: #fff; border-color: #C67C4E; transform: scale(1.1); }

/* Context menu */
.ctx-menu {
    display: none; position: absolute;
    top: calc(100% + 6px);
    background: #fff; border-radius: 10px;
    border: 1px solid #EDE5DA;
    box-shadow: 0 6px 28px rgba(44,24,16,.14);
    z-index: 300; overflow: hidden; min-width: 130px;
}
.msg-row.sent     .ctx-menu { right: 0; left: auto; }
.msg-row.received .ctx-menu { left: 0; right: auto; }
.ctx-menu.open { display: block; animation: ctxIn .13s ease; }
@keyframes ctxIn {
    from { opacity: 0; transform: scale(.93) translateY(-4px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
.ctx-item {
    padding: .52rem .88rem; font-size: .8rem; color: #2C1810;
    cursor: pointer; transition: background .1s; white-space: nowrap;
    display: flex; align-items: center; gap: .4rem;
}
.ctx-item:hover { background: #FDF5EE; }
.ctx-item.danger { color: #e53e3e; }
.ctx-item.danger:hover { background: #FFF5F5; }

/* Bubble wrap */
.b-wrap { display: flex; flex-direction: column; }
.msg-row.sent     .b-wrap { align-items: flex-end; }
.msg-row.received .b-wrap { align-items: flex-start; }

/* The bubble itself */
.bubble {
    padding: .52rem .85rem; border-radius: 14px;
    font-size: .875rem; line-height: 1.52;
    max-width: 100%; word-break: break-word;
    position: relative;
}
.msg-row.sent .bubble {
    background: linear-gradient(135deg, #D89A6E, #C67C4E);
    color: #fff; border-bottom-right-radius: 3px;
    box-shadow: 0 2px 6px rgba(0,0,0,.13);
}
.msg-row.received .bubble {
    background: #fff; color: #1E1209;
    border-bottom-left-radius: 3px;
    box-shadow: 0 1px 4px rgba(0,0,0,.09);
}
.bubble.deleted {
    background: #F5EFE6 !important;
    color: #9A8070 !important;
    font-style: italic;
    border: 1px dashed #D4C4B0;
}

/* Reply quote inside bubble */
.rq {
    border-radius: 7px; padding: .28rem .65rem;
    margin-bottom: .35rem; font-size: .75rem;
    cursor: pointer; transition: opacity .15s;
    border-left: 3px solid; display: flex; flex-direction: column; gap: 2px;
}
.msg-row.sent .rq {
    background: rgba(0,0,0,.12); border-color: rgba(255,255,255,.5);
    color: rgba(255,255,255,.9);
}
.msg-row.received .rq {
    background: #F5EDE3; border-color: #C67C4E; color: #5C4232;
}
.rq:hover { opacity: .82; }
.rq-name { font-weight: 700; font-size: .72rem; }
.rq-text { font-size: .73rem; opacity: .9; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 240px; }

/* Media */
.media-bubble { padding: 3px !important; background: transparent !important; }
.chat-img {
    display: block; width: 100%; max-width: 300px; border-radius: 12px;
    cursor: zoom-in; object-fit: cover; height: auto;
}
.video-wrap {
    width: 100%; max-width: 300px; border-radius: 12px;
    overflow: hidden; background: #000; aspect-ratio: 16/9;
}
.chat-video { width: 100%; height: 100%; display: block; outline: none; object-fit: contain; }
.media-cap { padding: .32rem .62rem .28rem; font-size: .84rem; line-height: 1.4; }

/* Edit inline */
.edit-wrap { display: none; }
.edit-wrap.on { display: flex; flex-direction: column; gap: .3rem; }
.edit-ta {
    padding: .42rem .7rem; border-radius: 9px;
    border: 1.5px solid #C67C4E; font-size: .875rem; font-family: inherit;
    resize: none; min-width: 160px; max-width: 280px;
    outline: none; background: #fff; color: #2C1810; line-height: 1.45;
}
.edit-row { display: flex; gap: .3rem; align-items: center; }
.edit-save {
    padding: .22rem .7rem; border-radius: 6px; font-size: .73rem; font-weight: 600;
    border: none; cursor: pointer; background: #C67C4E; color: #fff; transition: background .15s;
}
.edit-save:hover { background: #a05a2a; }
.edit-cancel {
    padding: .22rem .7rem; border-radius: 6px; font-size: .73rem; font-weight: 600;
    border: none; cursor: pointer; background: #F0E8DC; color: #7A6B5D;
}
.edit-timer { font-size: .63rem; color: #BBA88C; }
.edit-timer.red { color: #e53e3e; font-weight: 700; }

/* Message meta row */
.m-meta { display: flex; align-items: center; gap: .22rem; margin-top: 3px; padding: 0 2px; }
.msg-row.sent     .m-meta { justify-content: flex-end; }
.msg-row.received .m-meta { justify-content: flex-start; }
.m-time   { font-size: .63rem; color: rgba(107,93,82,.65); }
.m-edited { font-size: .6rem; color: rgba(107,93,82,.5); font-style: italic; }

/* ── Bottom input zone ── */
.c-input-zone { flex-shrink: 0; }

.reply-bar {
    display: none; align-items: center; gap: .6rem;
    padding: .5rem 1rem; background: #F5EDE3;
    border-top: 1px solid #EDE5DA; border-bottom: 1px solid #EDE5DA;
}
.reply-bar.show { display: flex; }
.reply-bar-inner {
    flex: 1; border-left: 3px solid #C67C4E; padding-left: .6rem; min-width: 0;
}
.rb-name { font-size: .73rem; font-weight: 700; color: #C67C4E; }
.rb-text { font-size: .72rem; color: #7A6B5D; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.rb-cancel {
    width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
    background: rgba(198,124,78,.12); border: none; color: #C67C4E;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: .85rem; transition: background .15s;
}
.rb-cancel:hover { background: rgba(198,124,78,.26); }

.fp-bar {
    display: none; align-items: center; gap: .6rem;
    padding: .45rem 1rem; background: #FDF5EE;
    border-top: 1px solid #EDE5DA; font-size: .8rem; color: #5C4232;
}
.fp-bar.show { display: flex; }
.fp-thumb { width: 48px; height: 38px; object-fit: cover; border-radius: 7px; border: 1px solid #EDE5DA; }
.fp-rm { margin-left: auto; background: none; border: none; color: #C67C4E; cursor: pointer; font-size: .82rem; font-weight: 700; }
.fp-rm:hover { color: #8B4513; }

.c-input-bar {
    background: #EFE5D7; padding: .65rem .95rem;
    display: flex; align-items: flex-end; gap: .55rem;
}

/* Attach popup */
.att-wrap { position: relative; flex-shrink: 0; }
.att-btn {
    width: 40px; height: 40px; border-radius: 50%;
    background: #fff; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: #7A6B5D; font-weight: 300;
    box-shadow: 0 1px 5px rgba(0,0,0,.1);
    transition: background .18s, color .18s, transform .25s;
}
.att-btn.open { background: #C67C4E; color: #fff; transform: rotate(45deg); }

.att-popup {
    display: none; position: absolute; bottom: calc(100% + 10px);
    background: #fff; border-radius: 14px; border: 1px solid #EDE5DA;
    box-shadow: 0 6px 28px rgba(44,24,16,.16); overflow: hidden;
    z-index: 300; min-width: 150px;
}
.att-popup.open { display: block; animation: popUp .16s ease; }
@keyframes popUp {
    from { opacity: 0; transform: translateY(8px) scale(.94); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
.att-opt {
    display: flex; align-items: center; gap: .65rem;
    padding: .65rem 1rem; font-size: .83rem; color: #2C1810;
    font-weight: 500; cursor: pointer; transition: background .12s; position: relative;
}
.att-opt:hover { background: #FDF5EE; }
.att-opt input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
.att-ic {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0;
}
.att-opt:first-child .att-ic { background: #FDE8D8; }
.att-opt:last-child  .att-ic  { background: #D8E8FD; }

/* Text input */
.msg-input-wrap { flex: 1; }
#msg-text {
    width: 100%; padding: .58rem .95rem;
    background: #fff; border: none; border-radius: 22px;
    font-size: .9rem; color: #2C1810; resize: none;
    line-height: 1.42; max-height: 120px; overflow-y: auto;
    font-family: inherit; box-shadow: 0 1px 4px rgba(0,0,0,.07);
    transition: box-shadow .18s;
}
#msg-text:focus { outline: none; box-shadow: 0 1px 12px rgba(198,124,78,.22); }
#msg-text::placeholder { color: #BBA88C; }

/* Send button */
.send-btn {
    width: 42px; height: 42px; border-radius: 50%;
    background: linear-gradient(135deg, #C67C4E, #9A5A2E);
    border: none; cursor: pointer; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 10px rgba(198,124,78,.42);
    transition: transform .18s, box-shadow .18s;
}
.send-btn:hover { transform: scale(1.1); box-shadow: 0 4px 18px rgba(198,124,78,.58); }
.send-btn svg { color: #fff; width: 18px; height: 18px; }
.send-spin {
    display: none; width: 18px; height: 18px;
    border: 2px solid rgba(255,255,255,.4); border-top-color: #fff;
    border-radius: 50%; animation: spin .6s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Lightbox ── */
#lbox {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.92); z-index: 9999;
    align-items: center; justify-content: center;
    backdrop-filter: blur(10px);
}
#lbox.open { display: flex; }
#lbox img { max-width: 92vw; max-height: 92vh; border-radius: 8px; box-shadow: 0 12px 60px rgba(0,0,0,.5); }
#lbox-close {
    position: absolute; top: 1.1rem; right: 1.1rem;
    color: #fff; background: rgba(255,255,255,.15); border: none;
    width: 38px; height: 38px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 1.1rem; transition: background .2s;
}
#lbox-close:hover { background: rgba(255,255,255,.3); }

/* ── In-chat toast ── */
.c-toast {
    position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%) translateY(16px);
    background: #2C1810; color: #fff; padding: .42rem 1.2rem;
    border-radius: 20px; font-size: .78rem; opacity: 0;
    transition: all .22s; pointer-events: none; z-index: 8000; white-space: nowrap;
}
.c-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
</style>
@endsection

@section('main-class', '')
@section('content')

@php
    $hasRoom = isset($room);
    $activeRoomId = $hasRoom ? $room->id : null;
    $partnerName = $hasRoom ? $partner->name : '';
    $partnerAvatar = ($hasRoom && $partner->profile_photo_path) ? $partner->getProfilePhotoUrl() : null;
    $partnerInitials = $hasRoom ? $partner->getInitials() : '';
    $partnerRole = $hasRoom ? ($partner->isCooker() ? ' Cooker' : ' Customer') : '';
    $partnerProfileUrl = ($hasRoom && $user->isCustomer() && $partner->isCooker()) ? route('cookers.show', $partner) : null;
@endphp

<div class="chat-shell">

    {{-- ═══════════════ SIDEBAR ═══════════════ --}}
    <div class="mt-4 s-panel {{ $hasRoom ? 'hide-mobile' : '' }}" id="s-panel">
        <div class="s-header">
            <span style="font-size:1.3rem;"></span>
            <span class="s-header-title">Messages</span>
        </div>
        <div class="s-search">
            <input type="text" id="s-search-input" placeholder="Search conversations…" oninput="filterRooms(this.value)">
        </div>
        <div class="s-list" id="s-list">
            @forelse($rooms as $sideRoom)
                @php
                    $sp = $user->isCooker() ? $sideRoom->customer : $sideRoom->cooker;
                    $lm = $sideRoom->messages->last();
                    $lmSnippet = '';
                    $lmTs = null;
                    if ($lm) {
                        $lmTs = $lm->created_at->timestamp;
                        if ($lm->is_deleted)                          $lmSnippet = 'Deleted';
                        elseif ($lm->attachment_type === 'image')     $lmSnippet = ' Photo';
                        elseif ($lm->attachment_type === 'video')     $lmSnippet = ' Video';
                        else                                           $lmSnippet = Str::limit($lm->message ?? '', 35);
                    }
                    $isUnread  = in_array($sideRoom->id, $unreadRoomIds);
                    $isActive  = $hasRoom && $sideRoom->id === $room->id;
                @endphp
                <a href="{{ route('chat.show', $sideRoom) }}"
                   id="s-room-{{ $sideRoom->id }}"
                   class="s-item {{ $isActive ? 'active' : '' }}"
                   data-room-id="{{ $sideRoom->id }}"
                   data-name="{{ strtolower($sp->name) }}"
                   onclick="loadChatRoom(event, {{ $sideRoom->id }}, '{{ route('chat.show', $sideRoom) }}')">
                    <div class="s-av">
                        @if($sp->profile_photo_path)
                            <img src="{{ $sp->getProfilePhotoUrl() }}" alt="{{ $sp->name }}">
                        @else
                            {{ $sp->getInitials() }}
                        @endif
                    </div>
                    <div class="s-info">
                        <div class="s-name">{{ $sp->name }}</div>
                        <div class="s-snippet" id="s-snip-{{ $sideRoom->id }}" style="{{ !$hasRoom ? 'display:none;' : '' }}">{{ $lmSnippet ?: 'No messages yet' }}</div>
                    </div>
                    <div class="s-right">
                        <span class="s-time" id="s-time-{{ $sideRoom->id }}" data-ts="{{ $lmTs }}" style="{{ !$hasRoom ? 'display:none;' : '' }}">
                            {{-- rendered by JS using local time --}}
                        </span>
                        @if($isUnread)
                            <span class="s-dot" id="s-dot-{{ $sideRoom->id }}"></span>
                        @else
                            <span class="s-dot" id="s-dot-{{ $sideRoom->id }}" style="display:none;"></span>
                        @endif
                    </div>
                </a>
            @empty
                <div style="padding:2rem;text-align:center;color:#9A7B5A;font-size:.83rem;">No conversations yet</div>
            @endforelse
        </div>
    </div>

    {{-- ═══════════════ CHAT PANEL ═══════════════ --}}
    <div class="mt-4 c-panel" id="c-panel" style="display: {{ $hasRoom ? 'flex' : 'none' }};">

        {{-- Header --}}
        <div class="c-header">
            <button class="c-back" id="c-back-btn" title="Back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>
            <div class="c-hav" id="c-header-avatar">
                @if($partnerAvatar)
                    <img src="{{ $partnerAvatar }}" alt="{{ $partnerName }}">
                @else
                    {{ $partnerInitials }}
                @endif
            </div>
            <div class="c-hinfo">
                <div class="c-hname" id="c-header-name">{{ $partnerName }}</div>
                <div class="c-hrole" id="c-header-role">{{ $partnerRole }}</div>
            </div>
            <a href="{{ $partnerProfileUrl ?? '#' }}" class="c-hbtn" id="c-header-profile-btn" style="display: {{ $partnerProfileUrl ? 'inline-block' : 'none' }};">View Profile</a>
        </div>

        {{-- Messages --}}
        <div class="c-msgs" id="msgs">
            @if($hasRoom)
                @php $lastDate = null; @endphp
                @foreach($room->messages as $msg)
                    @php
                        $isSent  = $msg->sender_id === $user->id;
                        $msgDate = $msg->created_at->toDateString();
                        $attach  = $msg->attachment_type ?? '';
                        $sender  = $isSent ? 'You' : $msg->sender->name;
                    @endphp

                    @if($msgDate !== $lastDate)
                        <div class="date-pill">
                            {{ $msg->created_at->isToday() ? 'Today' : ($msg->created_at->isYesterday() ? 'Yesterday' : $msg->created_at->format('d M Y')) }}
                        </div>
                        @php $lastDate = $msgDate; @endphp
                    @endif

                    <div class="msg-row {{ $isSent ? 'sent' : 'received' }}"
                         id="msg-{{ $msg->id }}"
                         data-id="{{ $msg->id }}"
                         data-ts="{{ $msg->created_at->timestamp }}"
                         data-mine="{{ $isSent ? '1' : '0' }}"
                         data-deleted="{{ $msg->is_deleted ? '1' : '0' }}"
                         data-sender="{{ addslashes($sender) }}"
                         data-attach="{{ $attach }}">

                        @if(!$isSent)
                            <div class="m-av">
                                @if($msg->sender->profile_photo_path)
                                    <img src="{{ $msg->sender->getProfilePhotoUrl() }}" alt="{{ $msg->sender->name }}">
                                @else {{ $msg->sender->getInitials() }} @endif
                            </div>
                        @endif

                        <div class="b-wrap">
                            <div class="b-col">
                                {{-- Bubble --}}
                                @if($msg->is_deleted)
                                    <div class="bubble deleted"> This message was deleted</div>

                                @elseif($msg->isImage())
                                    <div class="bubble media-bubble">
                                        @if($msg->replyTo)
                                            <div class="rq" onclick="scrollToMsg({{ $msg->replyTo->id }})">
                                                <span class="rq-name">{{ $msg->replyTo->sender?->name ?? 'Unknown' }}</span>
                                                <span class="rq-text">{{ $msg->replyTo->is_deleted ? ' Deleted' : ($msg->replyTo->attachment_type === 'image' ? ' Photo' : ($msg->replyTo->attachment_type === 'video' ? ' Video' : Str::limit($msg->replyTo->message ?? '', 60))) }}</span>
                                            </div>
                                        @endif
                                        <img src="{{ $msg->attachment_url }}" class="chat-img" onclick="openLbox(this.src)" alt="Photo">
                                        @if($msg->message) <div class="media-cap">{{ $msg->message }}</div> @endif
                                    </div>

                                @elseif($msg->isVideo())
                                    <div class="bubble media-bubble">
                                        @if($msg->replyTo)
                                            <div class="rq" onclick="scrollToMsg({{ $msg->replyTo->id }})">
                                                <span class="rq-name">{{ $msg->replyTo->sender?->name ?? 'Unknown' }}</span>
                                                <span class="rq-text">{{ $msg->replyTo->is_deleted ? ' Deleted' : ($msg->replyTo->attachment_type === 'image' ? ' Photo' : ($msg->replyTo->attachment_type === 'video' ? ' Video' : Str::limit($msg->replyTo->message ?? '', 60))) }}</span>
                                            </div>
                                        @endif
                                        <div class="video-wrap">
                                            <video class="chat-video" controls preload="metadata"><source src="{{ $msg->attachment_url }}"></video>
                                        </div>
                                        @if($msg->message) <div class="media-cap">{{ $msg->message }}</div> @endif
                                    </div>

                                @else
                                    <div class="bubble" id="bbl-{{ $msg->id }}">
                                        @if($msg->replyTo)
                                            <div class="rq" onclick="scrollToMsg({{ $msg->replyTo->id }})">
                                                <span class="rq-name">{{ $msg->replyTo->sender?->name ?? 'Unknown' }}</span>
                                                <span class="rq-text">{{ $msg->replyTo->is_deleted ? ' Deleted' : ($msg->replyTo->attachment_type === 'image' ? ' Photo' : ($msg->replyTo->attachment_type === 'video' ? ' Video' : Str::limit($msg->replyTo->message ?? '', 60))) }}</span>
                                            </div>
                                        @endif
                                        <span id="bbl-txt-{{ $msg->id }}">{{ $msg->message }}</span>
                                    </div>
                                @endif

                                {{-- ⋯ options button --}}
                                <div class="b-actions">
                                    <div style="position:relative;">
                                        <button class="action-btn" onclick="toggleCtx(event,{{ $msg->id }})" title="Options">⋯</button>
                                        <div class="ctx-menu" id="ctx-{{ $msg->id }}">
                                            <div class="ctx-item" onclick="doReply({{ $msg->id }})">↩ Reply</div>
                                            @if($isSent && !$msg->is_deleted)
                                                <div class="ctx-item danger" onclick="deleteMsg({{ $msg->id }})">🗑 Delete</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="m-meta">
                                <span class="m-time" data-ts="{{ $msg->created_at->timestamp }}"></span>
                                @if($msg->edited_at) <span class="m-edited">(edited)</span> @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Input zone --}}
        <div class="c-input-zone">
            <div class="reply-bar" id="reply-bar">
                <div class="reply-bar-inner">
                    <div class="rb-name" id="rb-name">Replying to…</div>
                    <div class="rb-text" id="rb-text"></div>
                </div>
                <button class="rb-cancel" onclick="clearReply()"></button>
            </div>

            <div class="fp-bar" id="fp-bar">
                <span id="fp-icon"></span>
                <img id="fp-thumb" class="fp-thumb" src="" alt="" style="display:none">
                <span id="fp-name" style="font-size:.78rem;color:#5C4232;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></span>
                <button class="fp-rm" onclick="clearFile()"> Remove</button>
            </div>

            <div class="c-input-bar">
                <div class="att-wrap">
                    <button class="att-btn" id="att-btn" onclick="toggleAtt()" title="Attach">+</button>
                    <div class="att-popup" id="att-popup">
                        <label class="att-opt">
                            <span class="att-ic"></span><span>Photo</span>
                            <input type="file" id="img-input" accept="image/*" onchange="handleFile(this,'image')">
                        </label>
                        <label class="att-opt">
                            <span class="att-ic"></span><span>Video</span>
                            <input type="file" id="vid-input" accept="video/*" onchange="handleFile(this,'video')">
                        </label>
                    </div>
                </div>

                <div class="msg-input-wrap">
                    <textarea id="msg-text" placeholder="Type a message…" rows="1"
                        onkeydown="handleKey(event)" oninput="autoH(this)"></textarea>
                </div>

                <button class="send-btn" id="send-btn" onclick="sendMsg()">
                    <svg id="send-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>
                    </svg>
                    <div class="send-spin" id="send-spin"></div>
                </button>
            </div>
        </div>
    </div>

    {{-- Welcome panel --}}
    <div class="c-panel c-welcome" id="c-welcome" style="display: {{ $hasRoom ? 'none' : 'flex' }};">
        <div class="big-icon"></div>
        <h2>Your messages</h2>
        <p>Pick a conversation from the left to start chatting</p>
    </div>

</div>{{-- /chat-shell --}}

<div id="lbox">
    <button id="lbox-close" onclick="closeLbox()"></button>
    <img id="lbox-img" src="" alt="">
</div>
<div class="c-toast" id="c-toast"></div>

@endsection

@section('scripts')

{{-- ─── Always-on sidebar timestamp renderer ─────────────────────────── --}}
<script>
/* Format unix-ts → HH:MM in *browser* local time */
function localHM(ts) {
    if (!ts) return '';
    const d = new Date(ts * 1000);
    return String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0');
}

/* Render all sidebar timestamps on page-load */
document.querySelectorAll('.s-time[data-ts]').forEach(el => {
    const ts = parseInt(el.dataset.ts);
    if (ts) el.textContent = localHM(ts);
});

/* Search filter */
function filterRooms(q) {
    const lq = q.toLowerCase().trim();
    document.querySelectorAll('.s-item').forEach(item => {
        const name = item.dataset.name || '';
        item.style.display = (!lq || name.includes(lq)) ? '' : 'none';
    });
}
</script>

<script>
/* ── Variables & Constants ────────────────────────────────────────── */
const USER_ID = {{ $user->id }};
const CSRF    = '{{ csrf_token() }}';

let ROOM_ID        = @json($activeRoomId);
let SEND_URL       = ROOM_ID ? '{{ route('chat.send', $activeRoomId ?: 0) }}' : '';
let POLL_URL       = ROOM_ID ? '{{ route('api.chat.messages', $activeRoomId ?: 0) }}' : '';
const DEL_BASE     = '{{ url('/chat/messages') }}';
const EDT_BASE     = '{{ url('/chat/messages') }}';

let lastMsgId       = ROOM_ID ? {{ isset($room) ? ($room->messages->last()?->id ?? 0) : 0 }} : 0;
let selFile         = null;
let replyToId       = null;
let editTimers      = {};
let openCtxId       = null;
let echoOn          = false;
let pollIv          = null;
let curEchoChannel  = ROOM_ID ? `chat.${ROOM_ID}` : null;

/* ── Scroll to bottom ──────────────────────────────────────────────── */
function scrollBot(smooth = true) {
    const a = document.getElementById('msgs');
    if (a) a.scrollTo({ top: a.scrollHeight, behavior: smooth ? 'smooth' : 'auto' });
}
if (ROOM_ID) {
    scrollBot(false);
}

/* ── Render msg timestamps ─────────────────────────────────────────── */
function renderTS() {
    document.querySelectorAll('.m-time[data-ts]').forEach(el => {
        el.textContent = localHM(parseInt(el.dataset.ts));
    });
}
renderTS();

/* ── Toast ─────────────────────────────────────────────────────────── */
function showToast(msg, ms = 2600) {
    const t = document.getElementById('c-toast');
    if (!t) return;
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), ms);
}

/* ── Attach popup ───────────────────────────────────────────────────── */
function toggleAtt() {
    const btn   = document.getElementById('att-btn');
    const popup = document.getElementById('att-popup');
    const isOpen = popup.classList.contains('open');
    closeAtt();
    if (!isOpen) { popup.classList.add('open'); btn.classList.add('open'); }
}
function closeAtt() {
    document.getElementById('att-popup')?.classList.remove('open');
    document.getElementById('att-btn')?.classList.remove('open');
}

/* ── Context menus ──────────────────────────────────────────────────── */
function toggleCtx(e, id) {
    e.stopPropagation();
    const menu = document.getElementById('ctx-' + id);
    const was  = menu?.classList.contains('open');
    closeCtx();
    if (!was && menu) { menu.classList.add('open'); openCtxId = id; }
}
function closeCtx() {
    document.querySelectorAll('.ctx-menu.open').forEach(m => m.classList.remove('open'));
    openCtxId = null;
}

/* Global click outside */
document.addEventListener('click', e => {
    const attWrap = document.getElementById('att-btn')?.closest('.att-wrap');
    if (attWrap && !attWrap.contains(e.target)) closeAtt();
    if (openCtxId && !e.target.closest('.ctx-menu') && !e.target.closest('.action-btn')) closeCtx();
});

// Toggle actions on bubble tap for mobile touch screen users
document.getElementById('msgs')?.addEventListener('click', e => {
    if (window.innerWidth > 768) return;
    const row = e.target.closest('.msg-row');
    if (!row) return;

    // Ignore if clicked on the action buttons or context menus
    if (e.target.closest('.action-btn') || e.target.closest('.ctx-menu')) {
        return;
    }

    const wasActive = row.classList.contains('show-actions');
    document.querySelectorAll('.msg-row.show-actions').forEach(el => el.classList.remove('show-actions'));
    if (!wasActive) {
        row.classList.add('show-actions');
    }
});

/* ESC closes everything */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeLbox(); closeAtt(); closeCtx(); clearReply(); }
});

/* Mobile back button */
document.getElementById('c-back-btn')?.addEventListener('click', () => {
    document.getElementById('s-panel')?.classList.remove('hide-mobile');
    document.getElementById('c-panel')?.style.setProperty('display', 'none');
    document.getElementById('c-welcome')?.style.setProperty('display', 'none');
});

/* ── File handling ──────────────────────────────────────────────────── */
function handleFile(input, type) {
    closeAtt();
    if (!input.files.length) return;
    const file = input.files[0];
    if (file.size > 50 * 1024 * 1024) { showToast(' Max file size is 50 MB.'); input.value = ''; return; }
    selFile = file;
    document.getElementById('fp-bar').classList.add('show');
    document.getElementById('fp-name').textContent = file.name;
    document.getElementById('fp-icon').textContent = type === 'image' ? '' : '';
    if (type === 'image') {
        const r = new FileReader();
        r.onload = ev => { const img = document.getElementById('fp-thumb'); img.src = ev.target.result; img.style.display = ''; };
        r.readAsDataURL(file);
    } else {
        document.getElementById('fp-thumb').style.display = 'none';
    }
    document.getElementById('msg-text').focus();
}
function clearFile() {
    selFile = null;
    document.getElementById('fp-bar').classList.remove('show');
    document.getElementById('fp-thumb').style.display = 'none';
    ['img-input','vid-input'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
}

/* ── Reply ──────────────────────────────────────────────────────────── */
function setReply(id, name, text) {
    replyToId = id;
    document.getElementById('rb-name').textContent = 'Replying to ' + name;
    document.getElementById('rb-text').textContent = text;
    document.getElementById('reply-bar').classList.add('show');
    document.getElementById('msg-text').focus();
}
function doReply(id) {
    closeCtx();
    const row = document.getElementById('msg-' + id);
    if (!row) return;
    const deleted = row.dataset.deleted === '1';
    const sender  = row.dataset.sender || 'Unknown';
    let snippet   = '';
    if (deleted)                         snippet = ' This message was deleted';
    else if (row.dataset.attach === 'image') snippet = ' Photo';
    else if (row.dataset.attach === 'video') snippet = ' Video';
    else { const sp = document.getElementById('bbl-txt-' + id); snippet = sp ? sp.textContent : ''; }
    setReply(id, sender, snippet);
}
function clearReply() {
    replyToId = null;
    document.getElementById('reply-bar').classList.remove('show');
}
function scrollToMsg(id) {
    const el = document.getElementById('msg-' + id);
    if (!el) return;
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    el.style.transition = 'background .3s';
    el.style.background = 'rgba(198,124,78,.18)';
    setTimeout(() => el.style.background = '', 900);
}

/* ── Textarea helpers ───────────────────────────────────────────────── */
function autoH(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}
function handleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMsg(); }
}


/* ── Send ───────────────────────────────────────────────────────────── */
async function sendMsg() {
    if (!ROOM_ID) return;
    const ta = document.getElementById('msg-text');
    const txt = ta.value.trim();
    if (!txt && !selFile) return;

    const btn   = document.getElementById('send-btn');
    const icon  = document.getElementById('send-icon');
    const spin  = document.getElementById('send-spin');
    icon.style.display = 'none'; spin.style.display = 'block'; btn.disabled = true;

    const fd = new FormData();
    fd.append('_token', CSRF);
    if (txt)      fd.append('message', txt);
    if (selFile)  fd.append('attachment', selFile);
    if (replyToId) fd.append('reply_to_id', replyToId);

    try {
        const res  = await fetch(SEND_URL, { method: 'POST', body: fd });
        const data = await res.json();
        if (res.ok) {
            appendMsg(data, true);
            lastMsgId = data.id;
            ta.value = ''; ta.style.height = 'auto';
            clearFile(); clearReply();
        } else { showToast(' ' + (data.error || 'Send failed.')); }
    } catch { showToast(' Network error.'); }
    finally { icon.style.display = ''; spin.style.display = 'none'; btn.disabled = false; }
}

/* ── Append message to DOM ──────────────────────────────────────────── */
function esc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

function appendMsg(msg, isMine) {
    if (document.getElementById('msg-' + msg.id)) return;

    const area = document.getElementById('msgs');
    const ts   = msg.created_at_ts || Math.floor(Date.now() / 1000);
    const timeLbl = localHM(ts);

    /* avatar */
    let avHtml = '';
    if (!isMine) {
        avHtml = msg.sender_photo
            ? `<div class="m-av"><img src="${esc(msg.sender_photo)}" alt="${esc(msg.sender_name||'')}"></div>`
            : `<div class="m-av">${esc(msg.sender_initials||'?')}</div>`;
    }

    /* reply quote */
    let qHtml = '';
    if (msg.reply_to) {
        qHtml = `<div class="rq" onclick="scrollToMsg(${msg.reply_to.id})">
            <span class="rq-name">${esc(msg.reply_to.sender_name||'Unknown')}</span>
            <span class="rq-text">${esc(msg.reply_to.snippet||'')}</span>
        </div>`;
    }

    /* bubble */
    let bHtml = '';
    if (msg.is_deleted) {
        bHtml = `<div class="bubble deleted"> This message was deleted</div>`;
    } else if (msg.attachment_type === 'image' && msg.attachment_url) {
        bHtml = `<div class="bubble media-bubble">${qHtml}
            <img src="${esc(msg.attachment_url)}" class="chat-img" onclick="openLbox(this.src)" alt="Photo">
            ${msg.message ? `<div class="media-cap">${esc(msg.message)}</div>` : ''}
        </div>`;
    } else if (msg.attachment_type === 'video' && msg.attachment_url) {
        bHtml = `<div class="bubble media-bubble">${qHtml}
            <div class="video-wrap"><video class="chat-video" controls preload="metadata"><source src="${esc(msg.attachment_url)}"></video></div>
            ${msg.message ? `<div class="media-cap">${esc(msg.message)}</div>` : ''}
        </div>`;
    } else {
        bHtml = `<div class="bubble" id="bbl-${msg.id}">${qHtml}<span id="bbl-txt-${msg.id}">${esc(msg.message||'')}</span></div>`;
    }

    const editedLbl = msg.edited_at ? `<span class="m-edited">(edited)</span>` : '';

    let ctxItems = `<div class="ctx-item" onclick="doReply(${msg.id})">↩ Reply</div>`;
    if (isMine && !msg.is_deleted) {
        ctxItems += `<div class="ctx-item danger" onclick="deleteMsg(${msg.id})">🗑 Delete</div>`;
    }

    const row = document.createElement('div');
    row.className = 'msg-row ' + (isMine ? 'sent' : 'received');
    row.id = 'msg-' + msg.id;
    row.dataset.id      = msg.id;
    row.dataset.ts      = ts;
    row.dataset.mine    = isMine ? '1' : '0';
    row.dataset.deleted = msg.is_deleted ? '1' : '0';
    row.dataset.sender  = isMine ? 'You' : (msg.sender_name || 'Unknown');
    row.dataset.attach  = msg.attachment_type || '';

    row.innerHTML = `${avHtml}
        <div class="b-wrap">
            <div class="b-col">
                ${bHtml}
                <div class="b-actions">
                    <div style="position:relative;">
                        <button class="action-btn" onclick="toggleCtx(event,${msg.id})">⋯</button>
                        <div class="ctx-menu" id="ctx-${msg.id}">${ctxItems}</div>
                    </div>
                </div>
            </div>
            <div class="m-meta">
                <span class="m-time" data-ts="${ts}">${timeLbl}</span>
                ${editedLbl}
            </div>
        </div>`;

    area.appendChild(row);
    updateSidebarSnippet(msg, ts);
    scrollBot();
}

/* ── Generalized Update Sidebar Room ───────────────────────────────── */
function updateSidebarRoom(roomId, snippet, ts, isUnread) {
    const snipEl = document.getElementById('s-snip-' + roomId);
    const timeEl = document.getElementById('s-time-' + roomId);
    const dotEl  = document.getElementById('s-dot-' + roomId);
    
    if (snipEl) {
        snipEl.textContent = snippet;
    }
    if (timeEl) {
        timeEl.dataset.ts = ts;
        timeEl.textContent = localHM(ts);
    }
    if (dotEl) {
        dotEl.style.display = (isUnread && roomId !== ROOM_ID) ? '' : 'none';
    }
    
    /* Move this room to top of sidebar */
    const roomEl = document.getElementById('s-room-' + roomId);
    const list   = document.getElementById('s-list');
    if (roomEl && list && roomEl.parentNode === list) {
        list.prepend(roomEl);
    }
}

/* ── Update sidebar snippet & time ─────────────────────────────────── */
function updateSidebarSnippet(msg, ts) {
    let snippet = '';
    if (msg.is_deleted)                          snippet = 'Deleted';
    else if (msg.attachment_type === 'image')    snippet = ' Photo';
    else if (msg.attachment_type === 'video')    snippet = ' Video';
    else {
        const t = msg.message || '';
        snippet = t.length > 35 ? t.substring(0, 35) + '…' : t;
    }
    updateSidebarRoom(ROOM_ID, snippet, ts, false);
}

/* Listen to chat notifications to sync other rooms in the sidebar */
document.addEventListener('chat-notification-received', (e) => {
    const data = e.detail;
    if (data && data.room_id) {
        const body = data.body || '';
        const ts = data.created_at_ts || Math.floor(Date.now() / 1000);
        updateSidebarRoom(data.room_id, body, ts, true);
    }
});

/* ── Delete message ─────────────────────────────────────────────────── */
async function deleteMsg(id) {
    closeCtx();
    if (!confirm('Delete this message?')) return;
    try {
        const res  = await fetch(`${DEL_BASE}/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF, Accept: 'application/json' } });
        const data = await res.json();
        if (res.ok) { markDeleted(id); showToast('🗑 Deleted'); }
        else showToast(' ' + (data.error || 'Delete failed.'));
    } catch { showToast(' Network error.'); }
}

function markDeleted(id) {
    const row = document.getElementById('msg-' + id);
    if (!row) return;
    row.dataset.deleted = '1'; row.removeAttribute('data-attach');
    const bbl = row.querySelector('.bubble');
    if (bbl) { bbl.className = 'bubble deleted'; bbl.innerHTML = ' This message was deleted'; }
    const ctx = document.getElementById('ctx-' + id);
    if (ctx) ctx.innerHTML = `<div class="ctx-item" onclick="doReply(${id})">↩ Reply</div>`;
    /* Update sidebar if it was last msg */
    const msgs = document.getElementById('msgs');
    if (msgs?.lastElementChild?.id === 'msg-' + id) {
        const snipEl = document.getElementById('s-snip-' + ROOM_ID);
        if (snipEl) snipEl.textContent = 'Deleted';
    }
}

/* ── Lightbox ───────────────────────────────────────────────────────── */
function openLbox(src) {
    document.getElementById('lbox-img').src = src;
    document.getElementById('lbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLbox() {
    document.getElementById('lbox')?.classList.remove('open');
    document.body.style.overflow = '';
}
document.getElementById('lbox')?.addEventListener('click', e => { if (e.target === e.currentTarget) closeLbox(); });

/* ── AJAX Load Chat Room (True SPA Navigation) ───────────────────────── */
async function loadChatRoom(e, roomId, url) {
    if (e) {
        e.preventDefault();
    }
    
    // Toggle active state in sidebar
    document.querySelectorAll('.s-list .s-item').forEach(item => item.classList.remove('active'));
    const activeItem = document.getElementById('s-room-' + roomId);
    if (activeItem) {
        activeItem.classList.add('active');
        // Hide unread dot
        const dot = document.getElementById('s-dot-' + roomId);
        if (dot) dot.style.display = 'none';
    }

    // Reveal all sidebar snippets & times now that a room is open
    document.querySelectorAll('.s-snippet, .s-time').forEach(el => el.style.display = '');

    try {
        const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) throw new Error('Failed to load chat');
        const data = await res.json();

        // Update globals
        ROOM_ID   = data.room_id;
        SEND_URL  = `/chat/${ROOM_ID}/messages`;
        POLL_URL  = `/api/chat/${ROOM_ID}/messages`;
        
        // Update URL & Title
        history.pushState({ roomId: ROOM_ID, url: url }, '', url);
        document.title = 'Chat — ' + data.partner.name + ' · Yumz';

        // Update Header Avatar
        const headerAv = document.getElementById('c-header-avatar');
        if (data.partner.avatar) {
            headerAv.innerHTML = `<img src="${esc(data.partner.avatar)}" alt="${esc(data.partner.name)}">`;
        } else {
            headerAv.innerHTML = esc(data.partner.initials);
        }

        // Update Header Info
        document.getElementById('c-header-name').textContent = data.partner.name;
        document.getElementById('c-header-role').textContent = data.partner.role;

        // Profile link
        const profileBtn = document.getElementById('c-header-profile-btn');
        if (data.partner.profile_url) {
            profileBtn.href = data.partner.profile_url;
            profileBtn.style.display = 'inline-block';
        } else {
            profileBtn.style.display = 'none';
        }

        // Fill Messages
        const area = document.getElementById('msgs');
        area.innerHTML = '';
        
        lastMsgId = 0;
        let lastDate = null;

        data.messages.forEach(msg => {
            lastMsgId = Math.max(lastMsgId, msg.id);

            // Add date pill if date changes
            const dateStr = new Date(msg.created_at_ts * 1000).toDateString();
            if (dateStr !== lastDate) {
                const now = new Date();
                const yesterday = new Date();
                yesterday.setDate(now.getDate() - 1);

                let label = '';
                const msgDate = new Date(msg.created_at_ts * 1000);
                if (msgDate.toDateString() === now.toDateString()) {
                    label = 'Today';
                } else if (msgDate.toDateString() === yesterday.toDateString()) {
                    label = 'Yesterday';
                } else {
                    label = msgDate.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                }

                const dp = document.createElement('div');
                dp.className = 'date-pill';
                dp.textContent = label;
                area.appendChild(dp);
                lastDate = dateStr;
            }

            appendMsg(msg, msg.sender_id === USER_ID);
        });

        // Hide welcome panel, show chat panel
        document.getElementById('c-welcome').style.setProperty('display', 'none');
        const cPanel = document.getElementById('c-panel');
        cPanel.style.setProperty('display', 'flex');

        // Scroll to bottom
        scrollBot(false);

        // Hide mobile sidebar if needed
        const sPanel = document.getElementById('s-panel');
        if (sPanel) sPanel.classList.add('hide-mobile');

        // Setup Echo private channel
        setupEcho();

    } catch (err) {
        console.error(err);
        showToast(' Error loading chat room');
    }
}

/* ── Setup Echo for dynamic room ────────────────────────────────────── */
function setupEcho() {
    if (typeof window.Echo !== 'undefined') {
        try {
            // Leave previous room channel
            if (curEchoChannel) {
                window.Echo.leave(curEchoChannel);
            }
            
            curEchoChannel = `chat.${ROOM_ID}`;
            const ch = window.Echo.private(curEchoChannel);

            ch.listen('.message.sent', data => {
                if (data.sender_id !== USER_ID) {
                    appendMsg(data, false);
                    lastMsgId = data.id;
                    echoOn = true;
                }
            });

            ch.listen('.message.deleted', data => markDeleted(data.id));

            ch.listen('.message.edited', data => {
                const span = document.getElementById('bbl-txt-' + data.id);
                if (span) span.textContent = data.message;
                const meta = document.getElementById('msg-' + data.id)?.querySelector('.m-meta');
                if (meta && !meta.querySelector('.m-edited')) {
                    const lbl = document.createElement('span');
                    lbl.className = 'm-edited'; lbl.textContent = '(edited)'; meta.appendChild(lbl);
                }
            });

            echoOn = true;
        } catch(e) {
            console.warn('[Chat] Echo failed, using polling fallback', e);
            startPoll();
        }
    } else {
        startPoll();
    }
    
    // Reset/restart polling with new POLL_URL
    if (pollIv) {
        clearInterval(pollIv);
        pollIv = null;
    }
    setTimeout(() => { if (!echoOn) startPoll(); }, 5000);
}

/* ── Polling fallback ───────────────────────────────────────────────── */
function startPoll() {
    if (pollIv || !POLL_URL) return;
    pollIv = setInterval(async () => {
        try {
            const res  = await fetch(`${POLL_URL}?last_id=${lastMsgId}`);
            const msgs = await res.json();
            msgs.forEach(m => {
                if (!document.getElementById('msg-' + m.id)) {
                    appendMsg(m, m.sender_id === USER_ID);
                    lastMsgId = Math.max(lastMsgId, m.id);
                }
            });
        } catch {}
    }, 3000);
}

// Initial Echo setup if room is already loaded
if (ROOM_ID) {
    setupEcho();
}

// Handle Browser Forward/Back Navigation
window.addEventListener('popstate', e => {
    if (e.state && e.state.roomId) {
        loadChatRoom(null, e.state.roomId, e.state.url);
    } else {
        // Reset to welcome panel
        ROOM_ID = null;
        SEND_URL = '';
        POLL_URL = '';
        document.getElementById('c-panel').style.setProperty('display', 'none');
        document.getElementById('c-welcome').style.setProperty('display', 'flex');
        document.getElementById('s-panel').classList.remove('hide-mobile');
        document.querySelectorAll('.s-list .s-item').forEach(item => item.classList.remove('active'));
    }
});
</script>

@endsection
