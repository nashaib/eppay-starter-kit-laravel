<?php

// database/seeders/ProductSeeder.php
namespace Database\Seeders;

use App\Models\Product;
use App\Models\Seller;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // First ensure we have a default seller
        $defaultSeller = Seller::firstOrCreate(
            ['email' => 'default@seller.com'],
            [
                'name' => 'Default Seller',
                'password' => bcrypt('password'),
                'shop_name' => 'Default Shop',
                'shop_slug' => 'default-shop',
                'phone' => '+1234567890',
                'address' => '123 Default Street',
                'status' => 'active',
                'eppay_wallet_address' => '0x' . str_repeat('0', 40),
            ]
        );

        // Create a default category if none exists
        $defaultCategory = Category::firstOrCreate(
            ['slug' => 'general'],
            [
                'name' => 'General',
                'description' => 'General products',
                'is_active' => true,
            ]
        );

        $products = [
            [
                'seller_id' => $defaultSeller->id,
                'category_id' => $defaultCategory->id,
                'name' => 'Wireless Headphones',
                'description' => 'High-quality wireless headphones with noise cancellation',
                'price' => 99.99,
                'stock' => 50,
                'status' => 'active',
            ],
            [
                'seller_id' => $defaultSeller->id,
                'category_id' => $defaultCategory->id,
                'name' => 'Smart Watch',
                'description' => 'Feature-rich smartwatch with health tracking',
                'price' => 199.99,
                'stock' => 30,
                'status' => 'active',
            ],
            [
                'seller_id' => $defaultSeller->id,
                'category_id' => $defaultCategory->id,
                'name' => 'Laptop Stand',
                'description' => 'Ergonomic aluminum laptop stand',
                'price' => 49.99,
                'stock' => 100,
                'status' => 'active',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}