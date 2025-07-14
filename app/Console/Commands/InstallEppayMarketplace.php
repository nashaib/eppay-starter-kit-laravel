<?php

// app/Console/Commands/InstallEppayMarketplace.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallEppayMarketplace extends Command
{
    protected $signature = 'eppay:install {--fresh : Wipe the database before installing}';
    protected $description = 'Install the Eppay Marketplace with demo data';

    public function handle()
    {
        $this->info('Installing Eppay Marketplace...');

        if ($this->option('fresh')) {
            $this->warn('This will delete all existing data!');
            if ($this->confirm('Do you wish to continue?')) {
                $this->call('migrate:fresh');
            } else {
                return;
            }
        }

        // Create storage link
        if (!File::exists(public_path('storage'))) {
            $this->call('storage:link');
        }

        // Run migrations
        $this->info('Running migrations...');
        $this->call('migrate');

        // Seed demo data
        $this->info('Seeding demo data...');
        $this->call('db:seed', ['--class' => 'DemoDataSeeder']);

        $this->info('Installation complete!');
        $this->info('');
        $this->info('You can now login with:');
        $this->info('Admin: admin@eppay.store / password');
        $this->info('Seller: tech@seller.com / password');
        $this->info('Buyer: buyer@example.com / password');
        $this->info('');
        $this->info('Visit /seller/login for seller panel');
        $this->info('Visit /admin/login for admin panel');
    }
}