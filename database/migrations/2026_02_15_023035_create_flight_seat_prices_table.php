<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_seat_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id')->constrained()->cascadeOnDelete();
            $table->foreignId('travel_class_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 10, 2);
            $table->integer('available_seats');
            $table->integer('total_seats');
            $table->timestamps();
            
            $table->unique(['flight_id', 'travel_class_id']);
            $table->index('flight_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_seat_prices');
    }
};
