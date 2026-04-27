@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('profile.show') }}" class="inline-flex items-center text-sm text-tv-primary hover:underline mb-4">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Profile
            </a>
            <h1 class="text-3xl font-extrabold text-tv-text">Edit Profile</h1>
            <p class="text-tv-muted mt-1">Update your personal information</p>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">
                <p class="font-medium mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Avatar Upload --}}
            <div class="tv-card p-6 mb-6">
                <h3 class="text-lg font-bold text-tv-text mb-4">Profile Picture</h3>
                
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <img id="avatarPreview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                            class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                        
                        @if($user->google_id && filter_var($user->avatar, FILTER_VALIDATE_URL))
                            <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-md border border-gray-200">
                                <svg class="w-4 h-4" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-1">
                        @if($user->google_id && filter_var($user->avatar, FILTER_VALIDATE_URL))
                            <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-xs text-blue-700">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    Currently using Google profile picture
                                </p>
                            </div>
                        @endif
                        
                        <label class="btn-tv-outline cursor-pointer inline-block px-4 py-2 text-sm">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $user->google_id && filter_var($user->avatar, FILTER_VALIDATE_URL) ? 'Change to Custom Photo' : 'Upload Photo' }}
                            <input type="file" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                        </label>
                        <p class="text-xs text-tv-muted mt-2">JPG, PNG. Max 2MB</p>
                    </div>
                </div>
            </div>

            {{-- Personal Information --}}
            <div class="tv-card p-6 mb-6">
                <h3 class="text-lg font-bold text-tv-text mb-6">Personal Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="tv-label mb-2">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="tv-input @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="tv-label mb-2">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="tv-input @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="tv-label mb-2">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="tv-input @error('phone') border-red-500 @enderror"
                            placeholder="+62 812 3456 7890">
                        @error('phone')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="tv-label mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth" 
                            value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                            class="tv-input @error('date_of_birth') border-red-500 @enderror">
                        @error('date_of_birth')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="tv-label mb-2">Gender</label>
                        <select name="gender" class="tv-input @error('gender') border-red-500 @enderror">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="tv-label mb-2">Nationality (ISO Code)</label>
                        <input type="text" name="nationality" value="{{ old('nationality', $user->nationality) }}"
                            class="tv-input @error('nationality') border-red-500 @enderror"
                            placeholder="ID" maxlength="2">
                        @error('nationality')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-tv-muted mt-1">2-letter country code (e.g., ID, US, SG)</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="tv-label mb-2">Passport Number</label>
                        <input type="text" name="passport_number" value="{{ old('passport_number', $user->passport_number) }}"
                            class="tv-input @error('passport_number') border-red-500 @enderror"
                            placeholder="A12345678">
                        @error('passport_number')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3">
                <a href="{{ route('profile.show') }}" class="flex-1 btn-tv-outline py-3 text-center">
                    Cancel
                </a>
                <button type="submit" class="flex-1 btn-tv-accent py-3">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">`;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
