<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flight_id')->constrained()->cascadeOnDelete();
            $table->timestamp('checked_in_at');
            $table->string('check_in_method')->default('online'); // online, kiosk, counter
            $table->boolean('baggage_checked')->default(false);
            $table->enum('status', ['completed', 'cancelled'])->default('completed');
            $table->timestamps();
            
            $table->unique(['booking_id', 'flight_id']);
            $table->index('checked_in_at');
        });

        Schema::create('boarding_passes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('check_in_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_passenger_id')->constrained()->cascadeOnDelete();
            $table->string('boarding_pass_number')->unique();
            $table->string('seat_number');
            $table->string('gate')->nullable();
            $table->time('boarding_time')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->text('qr_code_data')->nullable();
            $table->string('barcode')->nullable();
            $table->enum('status', ['active', 'used', 'cancelled'])->default('active');
            $table->timestamp('generated_at');
            $table->timestamps();
            
            $table->index('boarding_pass_number');
            $table->index(['check_in_id', 'booking_passenger_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_passes');
        Schema::dropIfExists('check_ins');
    }
};
