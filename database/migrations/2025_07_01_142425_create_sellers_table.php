<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('shop_name');
            $table->string('shop_slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(10.00);
            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
            $table->decimal('balance', 10, 2)->default(0);
            $table->string('eppay_wallet_address')->nullable();
            $table->json('settings')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            $table->index('shop_slug');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};