<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_flight_id')->constrained()->cascadeOnDelete();
            $table->enum('title', ['Mr', 'Mrs', 'Ms', 'Miss', 'Dr'])->default('Mr');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('nationality');
            $table->string('passport_number')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->string('id_number')->nullable(); // KTP/ID card
            $table->enum('passenger_type', ['adult', 'child', 'infant'])->default('adult');
            $table->string('special_assistance')->nullable();
            $table->timestamps();
            
            $table->index(['booking_id', 'booking_flight_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_passengers');
    }
};
