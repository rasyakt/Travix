@extends('admin.layouts.admin')
@section('title', 'Aircraft Management')
@section('subtitle', 'Manage aircraft types')

@section('content')
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-tv-text">Aircraft Types</h3>
            <a href="{{ route('admin.aircraft.create') }}" class="btn-tv-primary text-xs py-2 px-4">+ Add Aircraft</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Model</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Manufacturer</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">IATA Code</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Capacity</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Range (km)</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($aircraft as $a)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-tv-text">{{ $a->model }}</td>
                            <td class="px-6 py-4 text-sm text-tv-text">{{ $a->manufacturer->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm font-mono font-bold text-tv-text">{{ $a->iata_code }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-tv-text">{{ $a->typical_seating_capacity }}</td>
                            <td class="px-6 py-4 text-sm text-tv-text">{{ number_format($a->max_range_km) ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.aircraft.edit', $a->id) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.aircraft.instances', $a->id) }}" class="p-2 text-tv-primary hover:bg-blue-50 rounded-lg transition-colors" title="Instances">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </a>
                                    <form action="{{ route('admin.aircraft.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus tipe pesawat ini?')">
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
        @if($aircraft->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">{{ $aircraft->links() }}</div>
        @endif
    </div>
@endsection
