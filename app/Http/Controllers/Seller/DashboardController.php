<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $seller = auth()->guard('seller')->user();
        
        // Today's stats
        $todayOrders = Order::where('seller_id', $seller->id)
            ->whereDate('created_at', Carbon::today())
            ->count();
            
        $todayRevenue = Order::where('seller_id', $seller->id)
            ->whereDate('created_at', Carbon::today())
            ->where('payment_status', 'completed')
            ->sum('total_amount');
        
        // This month stats
        $monthOrders = Order::where('seller_id', $seller->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
            
        $monthRevenue = Order::where('seller_id', $seller->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('payment_status', 'completed')
            ->sum('total_amount');
        
        // Pending orders
        $pendingOrders = Order::where('seller_id', $seller->id)
            ->where('status', 'pending')
            ->count();
        
        // Low stock products
        $lowStockProducts = Product::where('seller_id', $seller->id)
            ->where('stock', '<=', 5)
            ->where('status', 'active')
            ->count();
        
        // Recent orders
        $recentOrders = Order::where('seller_id', $seller->id)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();
        
        // Best selling products
        $bestSellingProducts = Product::where('seller_id', $seller->id)
            ->orderBy('sales_count', 'desc')
            ->take(5)
            ->get();
        
        // Sales chart data (last 7 days)
        $salesChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $salesChartData['labels'][] = $date->format('M d');
            $salesChartData['data'][] = Order::where('seller_id', $seller->id)
                ->whereDate('created_at', $date)
                ->where('payment_status', 'completed')
                ->sum('total_amount');
        }
        
        return view('seller.dashboard', compact(
            'todayOrders',
            'todayRevenue',
            'monthOrders',
            'monthRevenue',
            'pendingOrders',
            'lowStockProducts',
            'recentOrders',
            'bestSellingProducts',
            'salesChartData'
        ));
    }
}