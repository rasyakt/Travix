<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('baggage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_passenger_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flight_id')->constrained()->cascadeOnDelete();
            $table->enum('baggage_type', ['cabin', 'checked'])->default('checked');
            $table->integer('weight_kg');
            $table->decimal('fee', 10, 2);
            $table->string('tag_number')->nullable()->unique();
            $table->enum('status', ['pending', 'checked_in', 'loaded', 'delivered'])->default('pending');
            $table->timestamps();
            
            $table->index(['booking_passenger_id', 'flight_id']);
            $table->index('tag_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baggage');
    }
};
