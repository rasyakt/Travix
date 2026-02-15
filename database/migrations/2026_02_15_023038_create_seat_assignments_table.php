<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seat_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_passenger_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flight_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seat_map_id')->constrained()->cascadeOnDelete();
            $table->string('seat_number');
            $table->decimal('extra_fee', 10, 2)->default(0);
            $table->enum('assignment_type', ['auto', 'manual', 'checkin'])->default('manual');
            $table->timestamp('assigned_at');
            $table->timestamps();
            
            $table->unique(['flight_id', 'seat_map_id']);
            $table->index(['booking_passenger_id', 'flight_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_assignments');
    }
};
