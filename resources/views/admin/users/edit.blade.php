@extends('admin.layouts.admin')
@section('title', 'Edit User')
@section('subtitle', 'Edit user account details')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <div>
                    <label class="tv-label">Full Name</label>
                    <input type="text" name="name" class="tv-input" value="{{ old('name', $user->name) }}" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="tv-label">Email Address</label>
                    <input type="email" name="email" class="tv-input" value="{{ old('email', $user->email) }}" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">New Password <span class="text-tv-muted font-normal">(optional)</span></label>
                        <input type="password" name="password" class="tv-input">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="tv-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="tv-input">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Phone</label>
                        <input type="text" name="phone" class="tv-input" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div>
                        <label class="tv-label">Nationality</label>
                        <input type="text" name="nationality" class="tv-input" value="{{ old('nationality', $user->nationality) }}">
                    </div>
                </div>
                <div>
                    <label class="tv-label">Role</label>
                    <select name="role" class="tv-input" required>
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="btn-tv-primary">Update User</button>
                    <a href="{{ route('admin.users.index') }}" class="btn-tv-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
