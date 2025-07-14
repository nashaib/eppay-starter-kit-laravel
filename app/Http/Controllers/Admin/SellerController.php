<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $query = Seller::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('shop_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sellers = $query->latest()->paginate(20);

        $stats = [
            'total' => Seller::count(),
            'active' => Seller::where('status', 'active')->count(),
            'pending' => Seller::where('status', 'pending')->count(),
            'suspended' => Seller::where('status', 'suspended')->count(),
        ];

        return view('admin.sellers.index', compact('sellers', 'stats'));
    }

    public function show(Seller $seller)
    {
        $seller->load(['products', 'orders']);
        
        $stats = [
            'total_products' => $seller->products()->count(),
            'active_products' => $seller->products()->where('status', 'active')->count(),
            'total_orders' => $seller->orders()->count(),
            'completed_orders' => $seller->orders()->where('payment_status', 'completed')->count(),
            'total_revenue' => $seller->orders()->where('payment_status', 'completed')->sum('total_amount'),
            'total_commission' => $seller->orders()->where('payment_status', 'completed')->sum('commission_amount'),
        ];

        $recentOrders = $seller->orders()->with('user')->latest()->take(10)->get();
        $topProducts = $seller->products()->orderBy('sales_count', 'desc')->take(5)->get();

        return view('admin.sellers.show', compact('seller', 'stats', 'recentOrders', 'topProducts'));
    }

    public function edit(Seller $seller)
    {
        return view('admin.sellers.edit', compact('seller'));
    }

    public function update(Request $request, Seller $seller)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:pending,active,suspended',
        ]);

        $seller->update([
            'commission_rate' => $request->commission_rate,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.sellers.show', $seller)
            ->with('success', 'Seller updated successfully.');
    }

    public function updateStatus(Request $request, Seller $seller)
    {
        $request->validate([
            'status' => 'required|in:pending,active,suspended',
        ]);

        $seller->update(['status' => $request->status]);

        return back()->with('success', 'Seller status updated successfully.');
    }

    public function destroy(Seller $seller)
    {
        // Check if seller has active products or pending orders
        if ($seller->products()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot delete seller with active products.');
        }

        if ($seller->orders()->whereIn('status', ['pending', 'processing'])->exists()) {
            return back()->with('error', 'Cannot delete seller with pending orders.');
        }

        $seller->delete();

        return redirect()->route('admin.sellers.index')
            ->with('success', 'Seller deleted successfully.');
    }
}