<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->string('iata_code', 3)->unique(); // e.g., CGK, SIN
            $table->string('icao_code', 4)->unique(); // e.g., WIII, WSSS
            $table->string('name'); // e.g., Soekarno-Hatta International Airport
            $table->string('city');
            $table->string('country');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('timezone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('iata_code');
            $table->index('icao_code');
            $table->index('city');
            $table->index('country');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('airports');
    }
};
