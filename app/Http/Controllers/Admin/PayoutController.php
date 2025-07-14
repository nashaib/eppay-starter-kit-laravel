<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('payment_status', 'completed')
            ->where('payout_status', 'pending')
            ->with(['seller', 'user']);

        if ($request->filled('seller_id')) {
            $query->where('seller_id', $request->seller_id);
        }

        $pendingPayouts = $query->get()
            ->groupBy('seller_id')
            ->map(function ($orders, $sellerId) {
                $seller = $sellerId ? Seller::find($sellerId) : null;
                $totalAmount = $orders->sum('subtotal');
                $totalCommission = $orders->sum('commission_amount');
                $payoutAmount = $totalAmount - $totalCommission;

                return [
                    'seller' => $seller,
                    'orders' => $orders,
                    'order_count' => $orders->count(),
                    'total_amount' => $totalAmount,
                    'commission_amount' => $totalCommission,
                    'payout_amount' => $payoutAmount,
                ];
            });

        $completedPayouts = Payout::with('seller')
            ->latest()
            ->paginate(20);

        return view('admin.payouts.index', compact('pendingPayouts', 'completedPayouts'));
    }

    public function create(Request $request)
    {
        $sellerId = $request->seller_id;
        
        $orders = Order::where('payment_status', 'completed')
            ->where('payout_status', 'pending')
            ->where('seller_id', $sellerId)
            ->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'No pending payouts for this seller.');
        }

        $seller = Seller::findOrFail($sellerId);
        $totalAmount = $orders->sum('subtotal');
        $totalCommission = $orders->sum('commission_amount');
        $payoutAmount = $totalAmount - $totalCommission;

        return view('admin.payouts.create', compact('seller', 'orders', 'totalAmount', 'totalCommission', 'payoutAmount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:sellers,id',
            'transaction_id' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $seller = Seller::findOrFail($request->seller_id);
            
            // Get all pending orders for this seller
            $orders = Order::where('payment_status', 'completed')
                ->where('payout_status', 'pending')
                ->where('seller_id', $seller->id)
                ->get();

            $totalAmount = $orders->sum('subtotal');
            $totalCommission = $orders->sum('commission_amount');
            $payoutAmount = $totalAmount - $totalCommission;

            // Create payout record
            $payout = Payout::create([
                'seller_id' => $seller->id,
                'amount' => $payoutAmount,
                'commission_amount' => $totalCommission,
                'transaction_id' => $request->transaction_id,
                'notes' => $request->notes,
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            // Update order payout status
            $orders->each(function ($order) use ($payout) {
                $order->update([
                    'payout_status' => 'completed',
                    'payout_id' => $payout->id,
                ]);
            });

            // Update seller balance
            $seller->increment('balance', $payoutAmount);
        });

        return redirect()->route('admin.payouts.index')
            ->with('success', 'Payout processed successfully.');
    }

    public function show(Payout $payout)
    {
        $payout->load(['seller', 'orders']);
        
        return view('admin.payouts.show', compact('payout'));
    }
}