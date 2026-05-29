@extends('admin.layouts.admin')
@section('title', 'Schedule Management')
@section('subtitle', 'Manage flight schedules')

@section('content')
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-tv-text">All Schedules</h3>
            <a href="{{ route('admin.schedules.create') }}" class="btn-tv-primary text-xs py-2 px-4">+ Add Schedule</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Flight</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Airline</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Route</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Days</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Active</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($schedules as $schedule)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-mono font-bold text-tv-text">{{ $schedule->flight_number }}</td>
                            <td class="px-6 py-4 text-sm text-tv-text">{{ $schedule->airline->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-tv-text">{{ $schedule->originAirport->iata_code ?? '?' }} → {{ $schedule->destinationAirport->iata_code ?? '?' }}</td>
                            <td class="px-6 py-4 text-sm text-tv-text">{{ $schedule->departure_time->format('H:i') }} - {{ $schedule->arrival_time->format('H:i') }}</td>
                            <td class="px-6 py-4 text-sm text-tv-text">{{ $schedule->duration_minutes }} min</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-1">
                                    @foreach(['0' => 'M','1' => 'T','2' => 'W','3' => 'T','4' => 'F','5' => 'S','6' => 'S'] as $day => $label)
                                        <span class="w-5 h-5 rounded text-[8px] font-bold flex items-center justify-center {{ in_array((string)$day, $schedule->operating_days ?? []) ? 'bg-tv-primary text-white' : 'bg-gray-100 text-gray-400' }}">{{ $label }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-tv-text">Rp {{ number_format($schedule->base_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($schedule->is_active)
                                    <span class="tv-badge bg-emerald-50 text-emerald-600">Yes</span>
                                @else
                                    <span class="tv-badge bg-gray-100 text-gray-600">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
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
        @if($schedules->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">{{ $schedules->links() }}</div>
        @endif
    </div>
@endsection
