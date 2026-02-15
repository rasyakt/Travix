<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('airlines', function (Blueprint $table) {
            $table->id();
            $table->string('iata_code', 2)->unique(); // e.g., GA, SQ
            $table->string('icao_code', 3)->unique(); // e.g., GIA, SIA
            $table->string('name'); // e.g., Garuda Indonesia
            $table->string('country');
            $table->string('logo_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('iata_code');
            $table->index('icao_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('airlines');
    }
};
