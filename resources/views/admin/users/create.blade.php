@extends('admin.layouts.admin')
@section('title', 'Create User')
@section('subtitle', 'Add a new user to the system')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="tv-label">Full Name</label>
                    <input type="text" name="name" class="tv-input" value="{{ old('name') }}" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="tv-label">Email Address</label>
                    <input type="email" name="email" class="tv-input" value="{{ old('email') }}" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Password</label>
                        <input type="password" name="password" class="tv-input" required>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="tv-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="tv-input" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="tv-label">Phone</label>
                        <input type="text" name="phone" class="tv-input" value="{{ old('phone') }}">
                    </div>
                    <div>
                        <label class="tv-label">Nationality</label>
                        <input type="text" name="nationality" class="tv-input" value="{{ old('nationality') }}">
                    </div>
                </div>
                <div>
                    <label class="tv-label">Role</label>
                    <select name="role" class="tv-input" required>
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center gap-3 pt-3">
                    <button type="submit" class="btn-tv-primary">Create User</button>
                    <a href="{{ route('admin.users.index') }}" class="btn-tv-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
