<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Economy, Business, First
            $table->string('code', 1); // Y, C, F
            $table->decimal('price_multiplier', 4, 2)->default(1.00);
            $table->integer('baggage_allowance_kg')->default(20);
            $table->text('amenities')->nullable(); // JSON or comma-separated
            $table->integer('priority_boarding')->default(0);
            $table->timestamps();
            
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_classes');
    }
};
