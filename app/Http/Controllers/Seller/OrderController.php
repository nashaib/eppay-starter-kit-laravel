<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $seller = auth()->guard('seller')->user();
        
        $query = Order::where('seller_id', $seller->id)
            ->with(['user']);

        // Search by order number or customer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);

        // Calculate stats
        $stats = [
            'total_orders' => Order::where('seller_id', $seller->id)->count(),
            'pending_orders' => Order::where('seller_id', $seller->id)->where('status', 'pending')->count(),
            'completed_orders' => Order::where('seller_id', $seller->id)->where('status', 'completed')->count(),
            'total_revenue' => Order::where('seller_id', $seller->id)
                ->where('payment_status', 'completed')
                ->sum('total_amount'),
        ];

        return view('seller.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $seller = auth()->guard('seller')->user();
        
        // Ensure the order belongs to this seller
        if ($order->seller_id !== $seller->id) {
            abort(403, 'Unauthorized');
        }

        return view('seller.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $seller = auth()->guard('seller')->user();
        
        // Ensure the order belongs to this seller
        if ($order->seller_id !== $seller->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        // Don't allow status change if payment is not completed
        if ($order->payment_status !== 'completed' && in_array($request->status, ['processing', 'completed'])) {
            return back()->with('error', 'Cannot update status until payment is completed.');
        }

        $order->update(['status' => $request->status]);

        // If marking as completed, update product sales count
        if ($request->status === 'completed' && $order->status !== 'completed') {
            foreach ($order->items as $item) {
                if (isset($item['product_id'])) {
                    $product = \App\Models\Product::find($item['product_id']);
                    if ($product) {
                        $product->increment('sales_count', $item['quantity'] ?? 1);
                    }
                }
            }
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    public function updateTracking(Request $request, Order $order)
    {
        $seller = auth()->guard('seller')->user();
        
        // Ensure the order belongs to this seller
        if ($order->seller_id !== $seller->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'tracking_number' => 'nullable|string|max:255',
            'shipping_method' => 'nullable|string|max:255',
        ]);

        $order->update([
            'tracking_number' => $request->tracking_number,
            'shipping_method' => $request->shipping_method,
        ]);

        return back()->with('success', 'Tracking information updated successfully.');
    }
}