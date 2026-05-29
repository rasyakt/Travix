@extends('admin.layouts.admin')
@section('title', 'User Management')
@section('subtitle', 'Manage all registered users')

@section('content')
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-tv-text">All Users</h3>
            <a href="{{ route('admin.users.create') }}" class="btn-tv-primary text-xs py-2 px-4">+ Add User</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Bookings</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatar_url }}" alt="" class="w-9 h-9 rounded-lg object-cover">
                                    <div>
                                        <p class="text-sm font-bold text-tv-text">{{ $user->name }}</p>
                                        <p class="text-[10px] text-tv-muted">{{ $user->phone ?? 'No phone' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-tv-text">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->role === 'admin')
                                    <span class="tv-badge bg-purple-50 text-purple-600">Admin</span>
                                @else
                                    <span class="tv-badge bg-gray-100 text-gray-600">User</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-tv-text">{{ $user->bookings_count ?? $user->bookings()->count() }}</td>
                            <td class="px-6 py-4 text-xs text-tv-muted">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="p-2 text-tv-primary hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">{{ $users->links() }}</div>
        @endif
    </div>
@endsection
