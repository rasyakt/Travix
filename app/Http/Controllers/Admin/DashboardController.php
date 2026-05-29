<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Flight;
use App\Models\Payment;
use App\Models\Airline;
use App\Models\Airport;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::count();
        $totalUsers = User::count();
        $totalFlights = Flight::count();
        $totalRevenue = Payment::where('status', 'success')->sum('amount');
        $totalAirlines = Airline::count();
        $totalAirports = Airport::count();

        $recentBookings = Booking::with('user')
            ->latest()
            ->take(10)
            ->get();

        $bookingsByStatus = Booking::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $monthlyRevenue = Payment::where('status', 'success')
            ->where('paid_at', '>=', now()->startOfYear())
            ->select(DB::raw("EXTRACT(MONTH from paid_at) as month"), DB::raw('SUM(amount) as total'))
            ->groupBy(DB::raw("EXTRACT(MONTH from paid_at)"))
            ->pluck('total', 'month');

        $latestUsers = User::latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'totalBookings', 'totalUsers', 'totalFlights', 'totalRevenue',
            'totalAirlines', 'totalAirports', 'recentBookings',
            'bookingsByStatus', 'monthlyRevenue', 'latestUsers'
        ));
    }
}
