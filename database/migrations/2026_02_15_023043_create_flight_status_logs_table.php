<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('flight_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id')->constrained()->cascadeOnDelete();
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->string('source')->nullable(); // 'admin_manual', 'aviationstack_api', 'system_auto'
            $table->text('raw_data')->nullable(); // JSON data from API if applicable
            $table->timestamp('changed_at');
            $table->timestamps();

            $table->index('flight_id');
            $table->index('changed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_status_logs');
    }
};
