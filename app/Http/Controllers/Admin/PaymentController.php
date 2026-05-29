<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('booking.user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_code', 'like', "%{$search}%")
                  ->orWhere('midtrans_order_id', 'like', "%{$search}%")
                  ->orWhereHas('booking', function ($q) use ($search) {
                      $q->where('contact_email', 'like', "%{$search}%")
                        ->orWhere('contact_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->latest()->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::with('booking.user', 'booking.flights', 'booking.passengers')->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function markSuccess($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status === PaymentStatus::SUCCESS->value) {
            return back()->with('info', 'Pembayaran ini sudah berstatus success.');
        }

        $payment->update([
            'status' => PaymentStatus::SUCCESS->value,
            'paid_at' => now(),
        ]);

        $payment->booking->update(['status' => 'confirmed']);

        return back()->with('success', 'Pembayaran berhasil ditandai sebagai berhasil.');
    }

    public function markFailed($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status === PaymentStatus::SUCCESS->value) {
            return back()->with('error', 'Tidak dapat mengubah status pembayaran yang sudah berhasil.');
        }

        $payment->update([
            'status' => PaymentStatus::FAILED->value,
        ]);

        return back()->with('success', 'Pembayaran ditandai sebagai gagal.');
    }
}
