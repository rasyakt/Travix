<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('payment_code')->unique();
            $table->enum('payment_method', ['credit_card', 'bank_transfer', 'e_wallet', 'virtual_account', 'qris'])->nullable();
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'processing', 'success', 'failed', 'expired', 'cancelled'])->default('pending');
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_snap_token')->nullable();
            $table->text('midtrans_redirect_url')->nullable();
            $table->json('payment_details')->nullable(); // Store payment gateway response
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('payment_code');
            $table->index('midtrans_order_id');
            $table->index('status');
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
