@extends('admin.layouts.admin')
@section('title', 'Airport Management')
@section('subtitle', 'Manage airports')

@section('content')
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-tv-text">All Airports</h3>
            <a href="{{ route('admin.airports.create') }}" class="btn-tv-primary text-xs py-2 px-4">+ Add Airport</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Airport</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">IATA/ICAO</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">City</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Country</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Active</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($airports as $airport)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-tv-text">{{ $airport->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm font-mono font-bold text-tv-text">{{ $airport->iata_code }} / {{ $airport->icao_code }}</td>
                            <td class="px-6 py-4 text-sm text-tv-text">{{ $airport->city }}</td>
                            <td class="px-6 py-4 text-sm text-tv-text">{{ $airport->country }}</td>
                            <td class="px-6 py-4">
                                @if($airport->is_active)
                                    <span class="tv-badge bg-emerald-50 text-emerald-600">Active</span>
                                @else
                                    <span class="tv-badge bg-gray-100 text-gray-600">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.airports.edit', $airport->id) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.airports.destroy', $airport->id) }}" method="POST" onsubmit="return confirm('Hapus bandara ini?')">
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
        @if($airports->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">{{ $airports->links() }}</div>
        @endif
    </div>
@endsection
