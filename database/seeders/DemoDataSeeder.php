<?php

// database/seeders/DemoDataSeeder.php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin
        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@eppay.store',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Electronic devices and gadgets',
            'is_active' => true,
        ]);

        $smartphones = Category::create([
            'name' => 'Smartphones',
            'slug' => 'smartphones',
            'parent_id' => $electronics->id,
            'description' => 'Mobile phones and accessories',
            'is_active' => true,
        ]);

        $laptops = Category::create([
            'name' => 'Laptops',
            'slug' => 'laptops',
            'parent_id' => $electronics->id,
            'description' => 'Notebooks and laptops',
            'is_active' => true,
        ]);

        $fashion = Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion',
            'description' => 'Clothing and accessories',
            'is_active' => true,
        ]);

        $mensFashion = Category::create([
            'name' => "Men's Fashion",
            'slug' => 'mens-fashion',
            'parent_id' => $fashion->id,
            'description' => "Men's clothing and accessories",
            'is_active' => true,
        ]);

        $womensFashion = Category::create([
            'name' => "Women's Fashion",
            'slug' => 'womens-fashion',
            'parent_id' => $fashion->id,
            'description' => "Women's clothing and accessories",
            'is_active' => true,
        ]);

        // Create sellers
        $techStore = Seller::create([
            'name' => 'John Tech',
            'email' => 'tech@seller.com',
            'password' => bcrypt('password'),
            'shop_name' => 'Tech Paradise',
            'shop_slug' => 'tech-paradise',
            'description' => 'Your one-stop shop for all tech needs',
            'phone' => '+1234567890',
            'address' => '123 Tech Street, Silicon Valley',
            'commission_rate' => 10.00,
            'status' => 'active',
            'eppay_wallet_address' => '0x1234567890abcdef',
        ]);

        $fashionStore = Seller::create([
            'name' => 'Fashion Expert',
            'email' => 'fashion@seller.com',
            'password' => bcrypt('password'),
            'shop_name' => 'Fashion Hub',
            'shop_slug' => 'fashion-hub',
            'description' => 'Latest fashion trends',
            'phone' => '+0987654321',
            'address' => '456 Fashion Avenue, New York',
            'commission_rate' => 12.00,
            'status' => 'active',
            'eppay_wallet_address' => '0xabcdef1234567890',
        ]);

        // Create buyers
        $buyer1 = User::create([
            'name' => 'John Buyer',
            'email' => 'buyer@example.com',
            'password' => bcrypt('password'),
            'phone' => '+1122334455',
            'address' => '789 Buyer Street',
            'city' => 'Los Angeles',
            'state' => 'CA',
            'country' => 'USA',
            'postal_code' => '90001',
        ]);

        $buyer2 = User::create([
            'name' => 'Jane Customer',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'phone' => '+5544332211',
            'address' => '321 Customer Lane',
            'city' => 'Chicago',
            'state' => 'IL',
            'country' => 'USA',
            'postal_code' => '60601',
        ]);

        // Create products for Tech Store
        $products = [
            [
                'seller_id' => $techStore->id,
                'category_id' => $smartphones->id,
                'name' => 'iPhone 15 Pro Max',
                'slug' => 'iphone-15-pro-max',
                'description' => 'Latest iPhone with advanced features',
                'price' => 1199.99,
                'stock' => 50,
                'status' => 'active',
            ],
            [
                'seller_id' => $techStore->id,
                'category_id' => $smartphones->id,
                'name' => 'Samsung Galaxy S24 Ultra',
                'slug' => 'samsung-galaxy-s24-ultra',
                'description' => 'Premium Android smartphone',
                'price' => 1099.99,
                'stock' => 30,
                'status' => 'active',
            ],
            [
                'seller_id' => $techStore->id,
                'category_id' => $laptops->id,
                'name' => 'MacBook Pro 16"',
                'slug' => 'macbook-pro-16',
                'description' => 'Powerful laptop for professionals',
                'price' => 2499.99,
                'stock' => 20,
                'status' => 'active',
            ],
            [
                'seller_id' => $techStore->id,
                'category_id' => $laptops->id,
                'name' => 'Dell XPS 15',
                'slug' => 'dell-xps-15',
                'description' => 'High-performance Windows laptop',
                'price' => 1799.99,
                'stock' => 25,
                'status' => 'active',
            ],
        ];

        // Create products for Fashion Store
        $fashionProducts = [
            [
                'seller_id' => $fashionStore->id,
                'category_id' => $mensFashion->id,
                'name' => 'Classic Leather Jacket',
                'slug' => 'classic-leather-jacket',
                'description' => 'Premium quality leather jacket',
                'price' => 299.99,
                'stock' => 100,
                'status' => 'active',
            ],
            [
                'seller_id' => $fashionStore->id,
                'category_id' => $mensFashion->id,
                'name' => 'Formal Business Suit',
                'slug' => 'formal-business-suit',
                'description' => 'Elegant business suit for professionals',
                'price' => 499.99,
                'stock' => 50,
                'status' => 'active',
            ],
            [
                'seller_id' => $fashionStore->id,
                'category_id' => $womensFashion->id,
                'name' => 'Evening Dress',
                'slug' => 'evening-dress',
                'description' => 'Beautiful evening dress for special occasions',
                'price' => 399.99,
                'stock' => 75,
                'status' => 'active',
            ],
            [
                'seller_id' => $fashionStore->id,
                'category_id' => $womensFashion->id,
                'name' => 'Designer Handbag',
                'slug' => 'designer-handbag',
                'description' => 'Luxury designer handbag',
                'price' => 599.99,
                'stock' => 40,
                'status' => 'active',
            ],
        ];

        // Insert all products
        foreach (array_merge($products, $fashionProducts) as $productData) {
            Product::create(array_merge($productData, [
                'sku' => 'SKU-' . strtoupper(Str::random(8)),
                'meta_title' => $productData['name'],
                'meta_description' => Str::limit($productData['description'], 160),
            ]));
        }

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Admin login: admin@eppay.store / password');
        $this->command->info('Seller login: tech@seller.com / password');
        $this->command->info('Buyer login: buyer@example.com / password');
    }
}