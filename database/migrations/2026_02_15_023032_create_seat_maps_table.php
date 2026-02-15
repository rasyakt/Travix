<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seat_maps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aircraft_id')->constrained()->cascadeOnDelete();
            $table->foreignId('travel_class_id')->constrained()->cascadeOnDelete();
            $table->string('seat_number'); // e.g., 12A, 1F
            $table->integer('row_number');
            $table->string('column_letter', 1); // A-K
            $table->enum('position', ['window', 'middle', 'aisle']);
            $table->boolean('is_exit_row')->default(false);
            $table->boolean('is_available')->default(true);
            $table->decimal('extra_price', 10, 2)->default(0); // Extra fee for premium seats
            $table->timestamps();
            
            $table->unique(['aircraft_id', 'seat_number']);
            $table->index(['aircraft_id', 'travel_class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_maps');
    }
};
