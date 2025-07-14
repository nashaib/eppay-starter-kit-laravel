<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the product seeder first (if it exists)
        if (class_exists(ProductSeeder::class)) {
            $this->call(ProductSeeder::class);
        }
        
        // Call the demo data seeder
        $this->call(DemoDataSeeder::class);
    }
}