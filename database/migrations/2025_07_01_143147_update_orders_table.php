<?php

// database/migrations/2025_07_01_143147_update_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // For SQLite, we need to recreate the table
            Schema::create('orders_new', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('seller_id')->nullable();
                $table->string('customer_email');
                $table->string('customer_name');
                $table->decimal('total_amount', 10, 2);
                $table->decimal('subtotal', 10, 2);
                $table->decimal('shipping_cost', 10, 2)->default(0);
                $table->decimal('tax_amount', 10, 2)->default(0);
                $table->decimal('commission_amount', 10, 2)->default(0);
                $table->string('payment_id')->nullable();
                $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
                $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending');
                $table->json('items');
                $table->string('shipping_method')->nullable();
                $table->json('shipping_address')->nullable();
                $table->json('billing_address')->nullable();
                $table->string('tracking_number')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                
                $table->index(['user_id', 'status']);
                $table->index(['seller_id', 'status']);
            });

            // Copy existing data
            $oldOrders = DB::table('orders')->get();
            foreach ($oldOrders as $order) {
                DB::table('orders_new')->insert([
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => null,
                    'seller_id' => DB::table('products')->first()->seller_id ?? null,
                    'customer_email' => $order->customer_email,
                    'customer_name' => $order->customer_name,
                    'total_amount' => $order->total_amount,
                    'subtotal' => $order->total_amount, // Use total_amount as subtotal for existing orders
                    'shipping_cost' => 0,
                    'tax_amount' => 0,
                    'commission_amount' => $order->total_amount * 0.1, // 10% default commission
                    'payment_id' => $order->payment_id,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'items' => $order->items,
                    'shipping_method' => null,
                    'shipping_address' => null,
                    'billing_address' => null,
                    'tracking_number' => null,
                    'notes' => null,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                ]);
            }

            // Drop old table and rename new one
            Schema::dropIfExists('orders');
            Schema::rename('orders_new', 'orders');
        } else {
            // For MySQL/PostgreSQL
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
                $table->unsignedBigInteger('seller_id')->nullable()->after('user_id');
                $table->decimal('subtotal', 10, 2)->after('total_amount');
                $table->decimal('shipping_cost', 10, 2)->default(0)->after('subtotal');
                $table->decimal('tax_amount', 10, 2)->default(0)->after('shipping_cost');
                $table->decimal('commission_amount', 10, 2)->default(0)->after('tax_amount');
                $table->string('shipping_method')->nullable()->after('items');
                $table->json('shipping_address')->nullable()->after('shipping_method');
                $table->json('billing_address')->nullable()->after('shipping_address');
                $table->string('tracking_number')->nullable()->after('billing_address');
                $table->text('notes')->nullable()->after('tracking_number');
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('set null');
                $table->index(['user_id', 'status']);
                $table->index(['seller_id', 'status']);
            });
            
            // Update existing records with subtotal
            DB::table('orders')->update(['subtotal' => DB::raw('total_amount')]);
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // Recreate original structure
            Schema::create('orders_original', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->string('customer_email');
                $table->string('customer_name');
                $table->decimal('total_amount', 10, 2);
                $table->string('payment_id')->nullable();
                $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
                $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending');
                $table->json('items');
                $table->timestamps();
            });

            // Copy back essential data
            $orders = DB::table('orders')->get();
            foreach ($orders as $order) {
                DB::table('orders_original')->insert([
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_email' => $order->customer_email,
                    'customer_name' => $order->customer_name,
                    'total_amount' => $order->total_amount,
                    'payment_id' => $order->payment_id,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'items' => $order->items,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                ]);
            }

            Schema::dropIfExists('orders');
            Schema::rename('orders_original', 'orders');
        } else {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropForeign(['seller_id']);
                $table->dropColumn([
                    'user_id', 'seller_id', 'subtotal', 'shipping_cost', 
                    'tax_amount', 'commission_amount', 'shipping_method',
                    'shipping_address', 'billing_address', 'tracking_number', 'notes'
                ]);
            });
        }
    }
};