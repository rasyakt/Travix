<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('booking_code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('trip_type', ['one_way', 'round_trip', 'multi_city'])->default('one_way');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('base_fare', 12, 2);
            $table->decimal('taxes_fees', 10, 2)->default(0);
            $table->decimal('baggage_fee', 10, 2)->default(0);
            $table->decimal('seat_fee', 10, 2)->default(0);
            $table->integer('total_passengers')->default(1);
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->timestamp('booking_date');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index('booking_code');
            $table->index('user_id');
            $table->index('status');
            $table->index('booking_date');
        });

        Schema::create('booking_flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flight_id')->constrained()->cascadeOnDelete();
            $table->foreignId('travel_class_id')->constrained()->cascadeOnDelete();
            $table->integer('sequence')->default(1); // For multi-city: 1, 2, 3...
            $table->enum('segment_type', ['outbound', 'return', 'segment'])->default('outbound');
            $table->integer('passenger_count');
            $table->decimal('price_per_passenger', 10, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
            
            $table->index(['booking_id', 'sequence']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_flights');
        Schema::dropIfExists('bookings');
    }
};
