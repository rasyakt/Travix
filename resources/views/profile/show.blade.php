@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-tv-text">My Profile</h1>
            <p class="text-tv-muted mt-1">Kelola informasi profil dan preferensi akun Anda</p>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                {{-- Profile Card --}}
                <div class="tv-card p-6 text-center mb-6">
                    <div class="relative inline-block mb-4">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                            class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                        
                        @if($user->google_id)
                            <div class="absolute bottom-0 right-0 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-md border border-gray-200">
                                <svg class="w-4 h-4" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <h2 class="text-xl font-bold text-tv-text mb-1">{{ $user->name }}</h2>
                    <p class="text-sm text-tv-muted mb-4">{{ $user->email }}</p>
                    
                    <a href="{{ route('profile.edit') }}" class="btn-tv-outline w-full py-2.5 text-sm">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Profile
                    </a>
                </div>

                {{-- Stats Card --}}
                <div class="tv-card p-6">
                    <h3 class="font-bold text-tv-text mb-4 text-sm uppercase tracking-wide">Booking Statistics</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-tv-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-tv-muted">Total Bookings</p>
                                    <p class="text-lg font-bold text-tv-text">{{ $user->bookings_count }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-tv-muted">Active Bookings</p>
                                    <p class="text-lg font-bold text-tv-text">{{ $user->active_bookings_count }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-tv-muted">Completed</p>
                                    <p class="text-lg font-bold text-tv-text">{{ $user->completed_bookings_count }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Personal Information --}}
                <div class="tv-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-tv-text">Personal Information</h3>
                        <a href="{{ route('profile.edit') }}" class="text-sm text-tv-primary hover:underline font-medium">Edit</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="tv-label text-[10px] mb-1">Full Name</label>
                            <p class="text-sm font-medium text-tv-text">{{ $user->name }}</p>
                        </div>
                        
                        <div>
                            <label class="tv-label text-[10px] mb-1">Email Address</label>
                            <p class="text-sm font-medium text-tv-text">{{ $user->email }}</p>
                        </div>
                        
                        <div>
                            <label class="tv-label text-[10px] mb-1">Phone Number</label>
                            <p class="text-sm font-medium text-tv-text">{{ $user->phone ?? '-' }}</p>
                        </div>
                        
                        <div>
                            <label class="tv-label text-[10px] mb-1">Date of Birth</label>
                            <p class="text-sm font-medium text-tv-text">
                                {{ $user->date_of_birth ? $user->date_of_birth->format('d M Y') : '-' }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="tv-label text-[10px] mb-1">Gender</label>
                            <p class="text-sm font-medium text-tv-text">{{ $user->gender ? ucfirst($user->gender) : '-' }}</p>
                        </div>
                        
                        <div>
                            <label class="tv-label text-[10px] mb-1">Nationality</label>
                            <p class="text-sm font-medium text-tv-text">{{ $user->nationality ?? '-' }}</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="tv-label text-[10px] mb-1">Passport Number</label>
                            <p class="text-sm font-medium text-tv-text">{{ $user->passport_number ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Account Security --}}
                <div class="tv-card p-6">
                    <h3 class="text-lg font-bold text-tv-text mb-6">Account Security</h3>

                    <div class="space-y-4">
                        @if($user->google_id)
                            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl border border-blue-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-tv-text">Google Account Connected</p>
                                        <p class="text-xs text-tv-muted">Login menggunakan akun Google</p>
                                    </div>
                                </div>
                                <span class="tv-badge-green text-[10px]">Active</span>
                            </div>
                        @endif

                        @if($user->password)
                            <div class="flex items-center justify-between p-4 bg-tv-bg rounded-xl border border-tv-border">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-tv-border">
                                        <svg class="w-5 h-5 text-tv-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-tv-text">Password</p>
                                        <p class="text-xs text-tv-muted">Terakhir diubah beberapa waktu lalu</p>
                                    </div>
                                </div>
                                <button onclick="document.getElementById('changePasswordModal').classList.remove('hidden'); document.getElementById('changePasswordModal').classList.add('flex');" 
                                    class="text-sm text-tv-primary hover:underline font-medium">
                                    Change
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Change Password Modal --}}
    <div id="changePasswordModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold text-tv-text mb-4">Change Password</h3>
            
            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="tv-label mb-2">Current Password</label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password" required
                                class="tv-input pr-12 @error('current_password') border-red-500 @enderror">
                            <button type="button" onclick="togglePasswordVisibility('current_password', this)"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-tv-muted hover:text-tv-primary transition-colors">
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.815 7.815L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="tv-label mb-2">New Password</label>
                        <div class="relative">
                            <input type="password" id="new_password" name="password" required
                                class="tv-input pr-12 @error('password') border-red-500 @enderror">
                            <button type="button" onclick="togglePasswordVisibility('new_password', this)"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-tv-muted hover:text-tv-primary transition-colors">
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.815 7.815L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="tv-label mb-2">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" id="new_password_confirmation" name="password_confirmation" required class="tv-input pr-12">
                            <button type="button" onclick="togglePasswordVisibility('new_password_confirmation', this)"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-tv-muted hover:text-tv-primary transition-colors">
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.815 7.815L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('changePasswordModal').classList.add('hidden'); document.getElementById('changePasswordModal').classList.remove('flex');"
                        class="flex-1 btn-tv-outline py-2.5">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 btn-tv-accent py-2.5">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($errors->any() && ($errors->has('current_password') || $errors->has('password')))
        <script>
            (function() {
                const modal = document.getElementById('changePasswordModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            })();
        </script>
    @endif

    <script>
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            
            const eyeIcon = btn.querySelector('.eye-icon');
            const eyeOffIcon = btn.querySelector('.eye-off-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOffIcon.classList.add('hidden');
                eyeIcon.classList.remove('hidden');
            }
        }
    </script>
@endsection
