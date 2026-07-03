@extends('admin.layouts.admin')

@section('title', 'Edit User — CookSpace Admin')
@section('page-title', 'Edit User')

@section('content')
<div class="mb-7 anim-in">
    <h1 class="text-2xl font-bold text-cs-text-primary mb-1">✏️ Edit User</h1>
    <p class="text-cs-text-secondary text-sm">Update user information</p>
</div>

<div class="admin-card max-w-[560px] p-8 anim-in anim-d1">
    <div class="flex items-center gap-3 p-4 bg-admin-bg border border-admin-border rounded-lg mb-6">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold text-white flex-shrink-0
            {{ $targetUser->role === 'customer' ? 'bg-gradient-to-br from-blue-400 to-blue-600' : '' }}
            {{ $targetUser->role === 'cooker' ? 'bg-gradient-to-br from-orange-400 to-orange-600' : '' }}
            {{ $targetUser->role === 'admin' ? 'bg-gradient-to-br from-admin-accent to-[#00b159]' : '' }}">
            {{ strtoupper(substr($targetUser->name, 0, 1)) }}
        </div>
        <div>
            <div class="font-medium text-cs-text-primary text-sm">{{ $targetUser->name }}</div>
            <div class="text-xs text-cs-text-secondary">Joined {{ $targetUser->created_at->format('d M Y, H:i') }}</div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $targetUser) }}" id="edit-user-form">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label for="name" class="block text-xs font-semibold text-cs-text-secondary mb-1.5 tracking-wide uppercase">Name</label>
            <input
                type="text"
                name="name"
                id="name"
                class="w-full px-4 py-3 bg-white border rounded-lg text-cs-text-primary text-sm font-sans outline-none transition-all focus:ring-3 {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500/8' : 'border-admin-border focus:border-admin-accent focus:ring-admin-accent-glow' }}"
                value="{{ old('name', $targetUser->name) }}"
                required
            >
            @error('name')
                <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-5">
            <label for="email" class="block text-xs font-semibold text-cs-text-secondary mb-1.5 tracking-wide uppercase">Email</label>
            <input
                type="email"
                name="email"
                id="email"
                class="w-full px-4 py-3 bg-white border rounded-lg text-cs-text-primary text-sm font-sans outline-none transition-all focus:ring-3 {{ $errors->has('email') ? 'border-red-500 focus:ring-red-500/8' : 'border-admin-border focus:border-admin-accent focus:ring-admin-accent-glow' }}"
                value="{{ old('email', $targetUser->email) }}"
                required
            >
            @error('email')
                <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-5">
            <label for="role" class="block text-xs font-semibold text-cs-text-secondary mb-1.5 tracking-wide uppercase">Role</label>
            <select name="role" id="role" class="w-full px-4 py-3 bg-white border border-admin-border rounded-lg text-cs-text-primary text-sm font-sans outline-none cursor-pointer transition-all focus:border-admin-accent focus:ring-3 focus:ring-admin-accent-glow">
                <option value="customer" {{ old('role', $targetUser->role) === 'customer' ? 'selected' : '' }}>🛒 Customer</option>
                <option value="cooker" {{ old('role', $targetUser->role) === 'cooker' ? 'selected' : '' }}>👨‍🍳 Cooker</option>
                <option value="admin" {{ old('role', $targetUser->role) === 'admin' ? 'selected' : '' }}>🛡️ Admin</option>
            </select>
            @error('role')
                <div class="text-xs text-red-500 mt-1.5">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="btn btn-primary" id="btn-save">💾 Save Changes</button>
            <a href="{{ route('admin.users') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection
