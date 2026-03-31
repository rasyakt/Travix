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
        Schema::create('airline_cabin_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airline_id')->constrained()->cascadeOnDelete();
            $table->foreignId('aircraft_id')->constrained()->cascadeOnDelete();
            $table->foreignId('travel_class_id')->constrained()->cascadeOnDelete();
            $table->integer('start_row');
            $table->integer('end_row');
            $table->json('columns');        // e.g. ["A","C","D","F"]
            $table->string('layout_code'); // e.g. '2-2', '3-3', '2-4-2'
            $table->json('exit_rows')->nullable(); // e.g. [15, 16]
            $table->decimal('extra_price_exit', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['airline_id', 'aircraft_id', 'travel_class_id'], 'unique_airline_aircraft_class');
            $table->index(['airline_id', 'aircraft_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airline_cabin_profiles');
    }
};
