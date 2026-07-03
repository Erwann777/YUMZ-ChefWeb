@extends('layouts.app')

@section('title', 'My Profile — CookSpace')
@section('body-class', 'cs-bg')

@section('content')
<div class="max-w-4xl mx-auto mt-20 mb-12 animate-fadeInUp">
    <!-- Success/Error Notification -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm shadow-sm">
            <p class="font-bold mb-1">Input errors occurred:</p>
            <ul class="list-disc list-inside text-xs m-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Sidebar: Profile Pic & Info -->
        <div class="md:col-span-1 flex flex-col gap-6">
            <!-- Profile Photo Card -->
            <div class="bg-white border border-[#E8DDD2] rounded-3xl p-6 text-center shadow-[0_2px_12px_rgba(44,24,16,0.03)]">
                <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                    @csrf
                    <!-- Photo Container with Edit Overlay -->
                    <div class="relative w-32 h-32 mx-auto mb-4 group cursor-pointer" onclick="document.getElementById('photoInput').click()">
                        @if($user->profile_photo_path)
                            <img src="{{ $user->getProfilePhotoUrl() }}" alt="{{ $user->name }}" id="avatarPreview" class="w-full h-full rounded-full object-cover border-4 border-[#C67C4E] shadow-md transition-transform duration-300 group-hover:scale-[1.02]">
                        @else
                            <div id="avatarFallback" class="w-full h-full rounded-full bg-gradient-to-br from-[#C67C4E] to-[#8B4513] flex items-center justify-center text-white text-4xl font-bold border-4 border-[#C67C4E] shadow-md transition-transform duration-300 group-hover:scale-[1.02]">
                                {{ $user->getInitials() }}
                            </div>
                        @endif

                        <!-- Photo Edit Icon Overlay -->
                        <div class="absolute inset-0 bg-black/40 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center text-white text-xs font-semibold">
                            Change Photo 📸
                        </div>
                    </div>

                    <!-- Hidden file input -->
                    <input type="file" name="photo" id="photoInput" class="hidden" accept="image/*" onchange="previewAndSubmit(this)">
                </form>

                <h3 class="text-lg font-bold text-[#2C1810] mb-0.5 truncate">{{ $user->name }}</h3>
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wide inline-block mb-4
                    @if($user->isCooker())
                        bg-[#C67C4E]/12 text-[#C67C4E] border border-[#C67C4E]/20
                    @elseif($user->isAdmin())
                        bg-red-500/12 text-red-600 border border-red-500/20
                    @else
                        bg-blue-500/12 text-blue-600 border border-blue-500/20
                    @endif">
                    @if($user->isCooker())
                        👨‍🍳 Cooker
                    @elseif($user->isAdmin())
                        🛡️ Admin
                    @else
                        🛒 Customer
                    @endif
                </span>

                <!-- Bio info -->
                <p class="text-xs text-[#7A6248] italic mb-4 max-w-[200px] mx-auto">
                    {{ $user->bio ?? '"No short bio added yet."' }}
                </p>

                <div class="border-t border-[#E8DDD2] pt-4 text-left flex flex-col gap-2.5">
                    <div class="flex justify-between text-[0.72rem] text-[#7A6248]">
                        <span>Email:</span>
                        <strong class="text-[#2C1810] select-all">{{ $user->email }}</strong>
                    </div>
                    @if($user->phone)
                        <div class="flex justify-between text-[0.72rem] text-[#7A6248]">
                            <span>Phone No:</span>
                            <strong class="text-[#2C1810]">{{ $user->phone }}</strong>
                        </div>
                    @endif
                    <div class="flex justify-between text-[0.72rem] text-[#7A6248]">
                        <span>Joined:</span>
                        <strong class="text-[#2C1810]">{{ $user->created_at->format('d M Y') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Stats/Activity Card -->
            <div class="bg-white border border-[#E8DDD2] rounded-3xl p-5 shadow-[0_2px_12px_rgba(44,24,16,0.03)]">
                <h4 class="text-xs font-bold text-[#2C1810] uppercase tracking-wider mb-3.5 pb-2 border-b border-[#E8DDD2]">📊 Account Activity</h4>
                
                @if($user->isCustomer())
                    <div class="flex flex-col gap-3">
                        <div class="flex justify-between items-center bg-[#F5EFE6]/50 p-2.5 rounded-xl border border-slate-100">
                            <span class="text-xs text-[#7A6248]">🥘 Purchased Recipes</span>
                            <span class="text-sm font-bold text-[#2C1810]">{{ $recipePurchasesCount }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-[#F5EFE6]/50 p-2.5 rounded-xl border border-slate-100">
                            <span class="text-xs text-[#7A6248]">📦 Cooking Services Ordered</span>
                            <span class="text-sm font-bold text-[#2C1810]">{{ $serviceOrdersCount }}</span>
                        </div>
                    </div>
                @endif

                @if($user->isCooker())
                    <div class="flex flex-col gap-3">
                        <div class="flex justify-between items-center bg-[#C67C4E]/5 p-2.5 rounded-xl border border-[#C67C4E]/10">
                            <span class="text-xs text-[#7A6248]">🥘 My Recipes</span>
                            <span class="text-sm font-bold text-[#C67C4E]">{{ $recipesCount }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-[#C67C4E]/5 p-2.5 rounded-xl border border-[#C67C4E]/10">
                            <span class="text-xs text-[#7A6248]">👨‍🍳 My Services</span>
                            <span class="text-sm font-bold text-[#C67C4E]">{{ $servicesCount }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-[#C67C4E]/5 p-2.5 rounded-xl border border-[#C67C4E]/10">
                            <span class="text-xs text-[#7A6248]">📦 Orders Received</span>
                            <span class="text-sm font-bold text-[#C67C4E]">{{ $ordersCount }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Form: Edit Details -->
        <div class="md:col-span-2">
            <div class="bg-white border border-[#E8DDD2] rounded-3xl p-6 sm:p-8 shadow-[0_2px_12px_rgba(44,24,16,0.03)]">
                <h2 class="text-xl font-bold text-[#2C1810] mb-1">👤 Personal Detail Information</h2>
                <p class="text-xs text-[#7A6248] mb-6">Update your profile to build a trusted identity on CookSpace.</p>

                <form action="{{ route('profile.update') }}" method="POST" class="flex flex-col gap-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Lengkap -->
                        <div class="flex flex-col gap-1.5">
                            <label for="name" class="text-xs font-bold text-[#2C1810] uppercase tracking-wider">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-2.5 border border-[#E8DDD2] rounded-xl text-sm bg-white text-[#2C1810] outline-none focus:border-[#C67C4E] focus:ring-1 focus:ring-[#C67C4E]">
                        </div>

                        <!-- Email Address -->
                        <div class="flex flex-col gap-1.5">
                            <label for="email" class="text-xs font-bold text-[#2C1810] uppercase tracking-wider">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-2.5 border border-[#E8DDD2] rounded-xl text-sm bg-white text-[#2C1810] outline-none focus:border-[#C67C4E] focus:ring-1 focus:ring-[#C67C4E]">
                        </div>

                        <!-- Phone Number -->
                        <div class="flex flex-col gap-1.5">
                            <label for="phone" class="text-xs font-bold text-[#2C1810] uppercase tracking-wider">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 081234567890"
                                class="w-full px-4 py-2.5 border border-[#E8DDD2] rounded-xl text-sm bg-white text-[#2C1810] outline-none focus:border-[#C67C4E] focus:ring-1 focus:ring-[#C67C4E]">
                        </div>

                        <!-- User Role (Read Only) -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-[#2C1810] uppercase tracking-wider">User Type (Role)</label>
                            <input type="text" value="{{ $user->getRoleLabel() }}" readonly
                                class="w-full px-4 py-2.5 border border-[#E8DDD2] bg-[#F5EFE6] text-[#7A6248] rounded-xl text-sm outline-none cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Bio -->
                    <div class="flex flex-col gap-1.5">
                        <label for="bio" class="text-xs font-bold text-[#2C1810] uppercase tracking-wider">Bio / Short Biography</label>
                        <textarea name="bio" id="bio" rows="3" placeholder="Write a little culinary story or describe yourself..."
                            class="w-full px-4 py-2.5 border border-[#E8DDD2] rounded-xl text-sm bg-white text-[#2C1810] outline-none resize-none focus:border-[#C67C4E] focus:ring-1 focus:ring-[#C67C4E]">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <div class="border-t border-[#E8DDD2] pt-4 mt-2">
                        <h3 class="text-sm font-bold text-[#2C1810] mb-1">🔑 Change Password (Optional)</h3>
                        <p class="text-[0.7rem] text-[#7A6248] mb-4">Leave blank if you do not want to change your account password.</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Password Baru -->
                            <div class="flex flex-col gap-1.5">
                                <label for="password" class="text-xs font-bold text-[#2C1810] uppercase tracking-wider">New Password</label>
                                <input type="password" name="password" id="password" placeholder="Minimum 8 characters"
                                    class="w-full px-4 py-2.5 border border-[#E8DDD2] rounded-xl text-sm bg-white text-[#2C1810] outline-none focus:border-[#C67C4E] focus:ring-1 focus:ring-[#C67C4E]">
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="flex flex-col gap-1.5">
                                <label for="password_confirmation" class="text-xs font-bold text-[#2C1810] uppercase tracking-wider">Confirm New Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat new password"
                                    class="w-full px-4 py-2.5 border border-[#E8DDD2] rounded-xl text-sm bg-white text-[#2C1810] outline-none focus:border-[#C67C4E] focus:ring-1 focus:ring-[#C67C4E]">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="self-start mt-4 px-8 py-3 bg-[#C67C4E] text-white font-semibold text-sm rounded-xl hover:bg-[#B06A3E] transition-colors shadow-sm cursor-pointer border-none">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Realtime image preview and automatic submission
    function previewAndSubmit(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Update preview image
                const previewImg = document.getElementById('avatarPreview');
                const fallbackDiv = document.getElementById('avatarFallback');

                if (previewImg) {
                    previewImg.src = e.target.result;
                } else if (fallbackDiv) {
                    // If previously fallback was used, replace fallback with a new image element dynamically
                    const parent = fallbackDiv.parentNode;
                    const newImg = document.createElement('img');
                    newImg.src = e.target.result;
                    newImg.id = 'avatarPreview';
                    newImg.alt = 'Preview';
                    newImg.className = 'w-full h-full rounded-full object-cover border-4 border-[#C67C4E] shadow-md transition-transform duration-300 group-hover:scale-[1.02]';
                    parent.replaceChild(newImg, fallbackDiv);
                }
                
                // Show a quick loading state & submit form
                setTimeout(() => {
                    document.getElementById('photoForm').submit();
                }, 400);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
