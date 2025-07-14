<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->string('transaction_id')->unique();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->index(['seller_id', 'status']);
        });

        // Add payout fields to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payout_status', ['pending', 'completed'])->default('pending')->after('payment_status');
            $table->foreignId('payout_id')->nullable()->after('payout_status')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payout_status', 'payout_id']);
        });
        
        Schema::dropIfExists('payouts');
    }
};