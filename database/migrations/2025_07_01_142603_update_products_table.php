<?php

// database/migrations/2025_07_01_142603_update_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For SQLite, we need to recreate the table since it doesn't support adding NOT NULL columns easily
        if (DB::getDriverName() === 'sqlite') {
            // Create a temporary table with new structure
            Schema::create('products_new', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('seller_id');
                $table->unsignedBigInteger('category_id')->nullable();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('sku')->unique()->nullable();
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->string('image')->nullable();
                $table->integer('stock')->default(0);
                $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
                $table->integer('views_count')->default(0);
                $table->integer('sales_count')->default(0);
                $table->decimal('weight', 8, 2)->nullable();
                $table->json('dimensions')->nullable();
                $table->json('attributes')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->text('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->timestamps();
            });

            // Ensure we have a default seller
            $defaultSeller = \App\Models\Seller::firstOrCreate(
                ['email' => 'default@seller.com'],
                [
                    'name' => 'Default Seller',
                    'password' => bcrypt('password'),
                    'shop_name' => 'Default Shop',
                    'shop_slug' => 'default-shop',
                    'status' => 'active',
                ]
            );

            // Copy data from old table to new table
            $oldProducts = DB::table('products')->get();
            foreach ($oldProducts as $product) {
                DB::table('products_new')->insert([
                    'id' => $product->id,
                    'seller_id' => $defaultSeller->id,
                    'category_id' => null,
                    'name' => $product->name,
                    'slug' => \Illuminate\Support\Str::slug($product->name) . '-' . $product->id,
                    'sku' => 'PRD-' . strtoupper(\Illuminate\Support\Str::random(8)),
                    'description' => $product->description,
                    'price' => $product->price,
                    'image' => $product->image,
                    'stock' => $product->stock,
                    'status' => 'active',
                    'views_count' => 0,
                    'sales_count' => 0,
                    'weight' => null,
                    'dimensions' => null,
                    'attributes' => null,
                    'is_featured' => false,
                    'meta_title' => $product->name,
                    'meta_description' => \Illuminate\Support\Str::limit($product->description, 160),
                    'meta_keywords' => null,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ]);
            }

            // Drop old table and rename new table
            Schema::dropIfExists('products');
            Schema::rename('products_new', 'products');
            
            // Add indexes
            Schema::table('products', function (Blueprint $table) {
                $table->index(['seller_id', 'status']);
                $table->index('slug');
                $table->index('sku');
            });
        } else {
            // For MySQL/PostgreSQL, use normal alter table
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('seller_id')->after('id');
                $table->unsignedBigInteger('category_id')->nullable()->after('seller_id');
                $table->string('slug')->unique()->after('name');
                $table->string('sku')->unique()->nullable()->after('slug');
                $table->enum('status', ['active', 'inactive', 'draft'])->default('active')->after('stock');
                $table->integer('views_count')->default(0)->after('status');
                $table->integer('sales_count')->default(0)->after('views_count');
                $table->decimal('weight', 8, 2)->nullable()->after('sales_count');
                $table->json('dimensions')->nullable()->after('weight');
                $table->json('attributes')->nullable()->after('dimensions');
                $table->boolean('is_featured')->default(false)->after('attributes');
                $table->text('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                
                $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
                $table->index(['seller_id', 'status']);
                $table->index('slug');
                $table->index('sku');
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // Create original structure
            Schema::create('products_original', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->string('image')->nullable();
                $table->integer('stock')->default(0);
                $table->timestamps();
            });

            // Copy back essential data
            $products = DB::table('products')->get();
            foreach ($products as $product) {
                DB::table('products_original')->insert([
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'image' => $product->image,
                    'stock' => $product->stock,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ]);
            }

            Schema::dropIfExists('products');
            Schema::rename('products_original', 'products');
        } else {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['seller_id']);
                $table->dropForeign(['category_id']);
                $table->dropColumn([
                    'seller_id', 'category_id', 'slug', 'sku', 'status', 
                    'views_count', 'sales_count', 'weight', 'dimensions', 
                    'attributes', 'is_featured', 'meta_title', 
                    'meta_description', 'meta_keywords'
                ]);
            });
        }
    }
};