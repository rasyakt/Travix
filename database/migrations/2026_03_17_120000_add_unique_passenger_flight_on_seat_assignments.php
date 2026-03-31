<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicates = DB::table('seat_assignments')
            ->select('booking_passenger_id', 'flight_id', DB::raw('MIN(id) as keep_id'), DB::raw('COUNT(*) as total'))
            ->groupBy('booking_passenger_id', 'flight_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('seat_assignments')
                ->where('booking_passenger_id', $duplicate->booking_passenger_id)
                ->where('flight_id', $duplicate->flight_id)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }

        Schema::table('seat_assignments', function (Blueprint $table) {
            $table->unique(['booking_passenger_id', 'flight_id'], 'seat_assignments_passenger_flight_unique');
        });
    }

    public function down(): void
    {
        Schema::table('seat_assignments', function (Blueprint $table) {
            $table->dropUnique('seat_assignments_passenger_flight_unique');
        });
    }
};
