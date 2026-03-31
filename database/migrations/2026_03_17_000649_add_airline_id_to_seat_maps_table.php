<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('seat_maps', function (Blueprint $table) {
            $table->foreignId('airline_id')->nullable()->after('aircraft_id')->constrained()->nullOnDelete();
            // Drop the old generic unique (aircraft_id, seat_number) and replace with one
            // that scopes uniqueness per airline (NULL = generic fallback).
            $table->dropUnique(['aircraft_id', 'seat_number']);
            $table->unique(['airline_id', 'aircraft_id', 'seat_number'], 'seat_maps_airline_aircraft_seat_unique');
            $table->index(['airline_id', 'aircraft_id', 'travel_class_id'], 'seat_maps_airline_aircraft_class_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seat_maps', function (Blueprint $table) {
            $table->dropIndex('seat_maps_airline_aircraft_class_idx');
            $table->dropUnique('seat_maps_airline_aircraft_seat_unique');
            $table->dropForeign(['airline_id']);
            $table->dropColumn('airline_id');
            $table->unique(['aircraft_id', 'seat_number']);
        });
    }
};
