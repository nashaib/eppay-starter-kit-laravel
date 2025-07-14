<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Platform statistics
        $stats = [
            'total_users' => User::count(),
            'total_sellers' => Seller::count(),
            'active_sellers' => Seller::where('status', 'active')->count(),
            'pending_sellers' => Seller::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'active')->count(),
            'total_orders' => Order::count(),
            'completed_orders' => Order::where('payment_status', 'completed')->count(),
        ];

        // Revenue statistics
        $revenue = [
            'total' => Order::where('payment_status', 'completed')->sum('total_amount'),
            'today' => Order::where('payment_status', 'completed')
                ->whereDate('created_at', Carbon::today())
                ->sum('total_amount'),
            'this_month' => Order::where('payment_status', 'completed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_amount'),
            'commission_earned' => Order::where('payment_status', 'completed')->sum('commission_amount'),
        ];

        // Recent activities
        $recentOrders = Order::with(['user', 'seller'])
            ->latest()
            ->take(5)
            ->get();

        $pendingSellers = Seller::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Chart data for last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartData['labels'][] = $date->format('M d');
            $chartData['orders'][] = Order::whereDate('created_at', $date)->count();
            $chartData['revenue'][] = Order::where('payment_status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total_amount');
        }

        return view('admin.dashboard', compact('stats', 'revenue', 'recentOrders', 'pendingSellers', 'chartData'));
    }
}