<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aircraft_manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., Boeing, Airbus
            $table->string('country');
            $table->timestamps();
        });

        Schema::create('aircraft', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manufacturer_id')->constrained('aircraft_manufacturers')->cascadeOnDelete();
            $table->string('model'); // e.g., 737-800, A320neo
            $table->string('iata_code')->nullable(); // e.g., 738, 32N
            $table->integer('typical_seating_capacity');
            $table->integer('max_range_km')->nullable();
            $table->decimal('cruise_speed_kmh', 8, 2)->nullable();
            $table->string('legroom')->nullable(); // e.g., 30-32 inches
            $table->json('amenities')->nullable(); // e.g., ["WiFi", "Power", "Entertainment"]
            $table->timestamps();

            $table->index('model');
            $table->index('iata_code');
        });

        Schema::create('aircraft_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airline_id')->constrained()->cascadeOnDelete();
            $table->foreignId('aircraft_id')->constrained()->cascadeOnDelete();
            $table->string('registration_number')->unique(); // e.g., PK-GFA
            $table->string('name')->nullable(); // Aircraft nickname
            $table->year('manufacture_year')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('registration_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aircraft_instances');
        Schema::dropIfExists('aircraft');
        Schema::dropIfExists('aircraft_manufacturers');
    }
};
