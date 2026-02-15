<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airline_id')->constrained()->cascadeOnDelete();
            $table->foreignId('aircraft_id')->constrained()->cascadeOnDelete();
            $table->foreignId('origin_airport_id')->constrained('airports')->cascadeOnDelete();
            $table->foreignId('destination_airport_id')->constrained('airports')->cascadeOnDelete();
            $table->string('flight_number')->unique(); // e.g., GA-123
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->integer('duration_minutes');
            $table->json('operating_days')->nullable(); // [1,2,3,4,5] for Mon-Fri
            $table->date('valid_from');
            $table->date('valid_until');
            $table->decimal('base_price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('flight_number');
            $table->index(['origin_airport_id', 'destination_airport_id']);
        });

        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('aircraft_instance_id')->nullable()->constrained()->nullOnDelete();
            $table->string('flight_number'); // Can override schedule flight number
            $table->date('flight_date');
            $table->dateTime('departure_datetime');
            $table->dateTime('arrival_datetime');
            $table->enum('status', ['scheduled', 'active', 'boarding', 'departed', 'in_air', 'landed', 'arrived', 'delayed', 'cancelled'])->default('scheduled');
            $table->integer('available_seats');
            $table->decimal('current_price', 10, 2); // Dynamic pricing
            $table->string('gate')->nullable();
            $table->string('terminal')->nullable();
            $table->timestamps();

            $table->unique(['schedule_id', 'flight_date']);
            $table->index('flight_number');
            $table->index('flight_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
        Schema::dropIfExists('schedules');
    }
};
