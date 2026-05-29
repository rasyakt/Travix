@extends('admin.layouts.admin')
@section('title', 'Payment Details')
@section('subtitle', $payment->payment_code)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-tv-text mb-4">Payment Information</h3>
                <div class="grid grid-cols-2 gap-y-4">
                    <div><span class="tv-label text-[10px]">Payment Code</span><p class="text-sm font-bold font-mono">{{ $payment->payment_code }}</p></div>
                    <div><span class="tv-label text-[10px]">Status</span>
                        <span class="tv-badge {{ match($payment->status) { 'success' => 'bg-emerald-50 text-emerald-600', 'pending' => 'bg-amber-50 text-amber-600', 'processing' => 'bg-blue-50 text-blue-600', 'failed' => 'bg-red-50 text-red-600', default => 'bg-gray-100 text-gray-600' } }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                    <div><span class="tv-label text-[10px]">Method</span><p class="text-sm font-bold capitalize">{{ str_replace('_', ' ', $payment->payment_method ?? '-') }}</p></div>
                    <div><span class="tv-label text-[10px]">Amount</span><p class="text-sm font-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p></div>
                    <div><span class="tv-label text-[10px]">Created</span><p class="text-sm font-bold">{{ $payment->created_at->format('M d, Y H:i') }}</p></div>
                    <div><span class="tv-label text-[10px]">Paid At</span><p class="text-sm font-bold">{{ $payment->paid_at ? $payment->paid_at->format('M d, Y H:i') : '-' }}</p></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-tv-text mb-4">Booking Information</h3>
                <div class="grid grid-cols-2 gap-y-4">
                    <div><span class="tv-label text-[10px]">Booking Code</span><p class="text-sm font-bold font-mono">{{ $payment->booking->display_booking_code ?? 'N/A' }}</p></div>
                    <div><span class="tv-label text-[10px]">Status</span><span class="tv-badge">{{ ucfirst($payment->booking->status) }}</span></div>
                    <div><span class="tv-label text-[10px]">Customer</span><p class="text-sm font-bold">{{ $payment->booking->contact_name ?? 'N/A' }}</p></div>
                    <div><span class="tv-label text-[10px]">Email</span><p class="text-sm font-bold">{{ $payment->booking->contact_email ?? 'N/A' }}</p></div>
                    <div><span class="tv-label text-[10px]">Phone</span><p class="text-sm font-bold">{{ $payment->booking->contact_phone ?? 'N/A' }}</p></div>
                    <div><span class="tv-label text-[10px]">Total Passengers</span><p class="text-sm font-bold">{{ $payment->booking->total_passengers }}</p></div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-tv-text mb-4">Actions</h3>
                <div class="space-y-3">
                    @if($payment->status !== 'success')
                        <form action="{{ route('admin.payments.mark-success', $payment->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full btn-tv-primary text-xs py-2.5" onclick="return confirm('Mark payment as successful?')">
                                Mark as Success
                            </button>
                        </form>
                    @endif
                    @if($payment->status === 'pending' || $payment->status === 'processing')
                        <form action="{{ route('admin.payments.mark-failed', $payment->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full border border-red-200 text-red-600 font-bold py-2.5 rounded-xl text-xs hover:bg-red-50 transition-colors" onclick="return confirm('Mark payment as failed?')">
                                Mark as Failed
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if($payment->payment_details)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h3 class="font-bold text-tv-text mb-4">Payment Details</h3>
                    <pre class="text-[10px] text-tv-muted whitespace-pre-wrap font-mono">{{ json_encode($payment->payment_details, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif
        </div>
    </div>
@endsection
