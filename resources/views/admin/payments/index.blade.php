@extends('admin.layouts.admin')
@section('title', 'Payment Management')
@section('subtitle', 'View and manage all payments')

@section('content')
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <form method="GET" class="flex items-center gap-2">
                <input type="text" name="search" placeholder="Search payments..." class="tv-input py-2 px-3 text-sm w-48" value="{{ request('search') }}">
                <select name="status" class="tv-input py-2 px-3 text-sm w-32">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                <button type="submit" class="btn-tv-primary text-xs py-2 px-3">Filter</button>
                <a href="{{ route('admin.payments.index') }}" class="text-xs text-tv-muted hover:text-tv-primary">Reset</a>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Payment Code</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Booking</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-tv-muted uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($payments as $payment)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-mono font-bold text-tv-text">{{ $payment->payment_code }}</td>
                            <td class="px-6 py-4 text-sm font-mono text-tv-text">{{ $payment->booking->display_booking_code ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-tv-text">{{ $payment->booking->contact_name ?? 'N/A' }}</p>
                                <p class="text-[10px] text-tv-muted">{{ $payment->booking->contact_email ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm capitalize text-tv-text">{{ str_replace('_', ' ', $payment->payment_method ?? '-') }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-tv-text">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="tv-badge {{ match($payment->status) { 'success' => 'bg-emerald-50 text-emerald-600', 'pending' => 'bg-amber-50 text-amber-600', 'processing' => 'bg-blue-50 text-blue-600', 'failed' => 'bg-red-50 text-red-600', 'expired' => 'bg-gray-100 text-gray-600', default => 'bg-gray-100 text-gray-600' } }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-tv-muted">{{ $payment->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="p-2 text-tv-primary hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">{{ $payments->links() }}</div>
        @endif
    </div>
@endsection
