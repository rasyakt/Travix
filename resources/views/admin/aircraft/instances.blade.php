@extends('admin.layouts.admin')
@section('title', 'Aircraft Instances')
@section('subtitle', $aircraft->full_name . ' - Manage fleet instances')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-tv-text">Fleet Instances</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Reg Number</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Airline</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Year</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Active</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($aircraft->instances as $instance)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-mono font-bold text-tv-text">{{ $instance->registration_number }}</td>
                                    <td class="px-6 py-4 text-sm text-tv-text">{{ $instance->airline->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-tv-text">{{ $instance->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-tv-text">{{ $instance->manufacture_year ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($instance->is_active)
                                            <span class="tv-badge bg-emerald-50 text-emerald-600">Active</span>
                                        @else
                                            <span class="tv-badge bg-gray-100 text-gray-600">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.aircraft.instances.destroy', [$aircraft->id, $instance->id]) }}" method="POST" onsubmit="return confirm('Hapus instance ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-8 text-center text-sm text-tv-muted">No instances found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-tv-text mb-4">Add Instance</h3>
                <form action="{{ route('admin.aircraft.instances.store', $aircraft->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="tv-label">Airline</label>
                        <select name="airline_id" class="tv-input text-sm" required>
                            <option value="">Select Airline</option>
                            @foreach($airlines as $airline)
                                <option value="{{ $airline->id }}">{{ $airline->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="tv-label">Registration Number</label>
                        <input type="text" name="registration_number" class="tv-input text-sm" required placeholder="PK-GNE">
                    </div>
                    <div>
                        <label class="tv-label">Name (optional)</label>
                        <input type="text" name="name" class="tv-input text-sm" placeholder="City of Jakarta">
                    </div>
                    <div>
                        <label class="tv-label">Manufacture Year</label>
                        <input type="number" name="manufacture_year" class="tv-input text-sm" min="1900" max="{{ date('Y') }}">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-4 h-4 text-tv-primary rounded border-tv-border">
                        <label for="is_active" class="text-sm font-medium text-tv-text">Active</label>
                    </div>
                    <button type="submit" class="btn-tv-primary w-full text-xs py-2">Add Instance</button>
                </form>
            </div>
        </div>
    </div>
@endsection
