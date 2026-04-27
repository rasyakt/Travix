<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_insurances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('insurance_provider')->default('Travix Insurance');
            $table->string('policy_number')->unique();
            $table->enum('coverage_type', ['basic', 'standard', 'premium'])->default('basic');
            $table->decimal('coverage_amount', 15, 2);
            $table->decimal('premium_amount', 10, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('beneficiary_name');
            $table->string('beneficiary_relationship')->nullable();
            $table->json('policy_details')->nullable();
            $table->enum('status', ['active', 'expired', 'claimed', 'cancelled'])->default('active');
            $table->timestamps();
            
            $table->index(['booking_id', 'status']);
        });

        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('points_earned')->default(0);
            $table->integer('points_used')->default(0);
            $table->enum('transaction_type', ['earned', 'redemption', 'expired', 'bonus', 'refund'])->default('earned');
            $table->text('description')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'expires_at']);
        });

        Schema::create('meal_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_passenger_id')->constrained()->cascadeOnDelete();
            $table->string('meal_type')->default('REGULAR');
            $table->text('special_request')->nullable();
            $table->json('dietary_restrictions')->nullable();
            $table->json('allergies')->nullable();
            $table->timestamps();
            
            $table->index('booking_passenger_id');
        });

        Schema::create('special_assistances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_passenger_id')->constrained()->cascadeOnDelete();
            $table->string('assistance_type');
            $table->string('wheelchair_type')->nullable();
            $table->text('medical_condition')->nullable();
            $table->boolean('service_animal')->default(false);
            $table->json('special_equipment')->nullable();
            $table->text('additional_notes')->nullable();
            $table->boolean('approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->index(['booking_passenger_id', 'approved']);
        });

        // Add preferred_currency to users table
        if (!Schema::hasColumn('users', 'preferred_currency')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('preferred_currency', 3)->default('IDR')->after('email');
                $table->string('loyalty_tier')->default('bronze')->after('preferred_currency');
                $table->integer('total_loyalty_points')->default(0)->after('loyalty_tier');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('special_assistances');
        Schema::dropIfExists('meal_preferences');
        Schema::dropIfExists('loyalty_points');
        Schema::dropIfExists('travel_insurances');
        
        if (Schema::hasColumn('users', 'preferred_currency')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['preferred_currency', 'loyalty_tier', 'total_loyalty_points']);
            });
        }
    }
};
