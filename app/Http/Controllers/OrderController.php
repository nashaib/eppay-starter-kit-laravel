<?php
// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\EppayService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    private $eppayService;

    public function __construct(EppayService $eppayService)
    {
        $this->eppayService = $eppayService;
    }

    public function index(Request $request)
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['seller'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $order->load(['seller']);

        return view('orders.show', compact('order'));
    }
    public function create(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
        ]);

        $product = Product::findOrFail($request->product_id);
        $totalAmount = $product->price * $request->quantity;

        $order = Order::create([
            'order_number' => 'EPP-' . strtoupper(Str::random(8)),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'total_amount' => $totalAmount,
            'items' => [
                [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $request->quantity,
                ]
            ],
        ]);

        $successUrl = "https://eppay.io/payment-success";
        $paymentData = $this->eppayService->generatePayment($totalAmount, $successUrl);

        if ($paymentData && isset($paymentData['paymentId'])) {
            $order->update(['payment_id' => $paymentData['paymentId']]);
            return redirect()->route('order.payment', $order);
        }

        return back()->with('error', 'Failed to generate payment. Please try again.');
    }

    public function payment(Order $order)
    {
        if (!$order->payment_id) {
            return redirect()->route('products.index')->with('error', 'Invalid payment request.');
        }

        return view('orders.payment', compact('order'));
    }

    public function checkStatus(Order $order)
    {
        if (!$order->payment_id) {
            return response()->json(['status' => false, 'message' => 'No payment ID']);
        }

        $status = $this->eppayService->checkPaymentStatus($order->payment_id);
        
        \Log::info('Payment status check', [
            'order_id' => $order->id,
            'payment_id' => $order->payment_id,
            'status' => $status
        ]);

        if ($status) {
            $order->update([
                'payment_status' => 'completed',
                'status' => 'processing',
            ]);
        }

        return response()->json([
            'status' => $status,
            'payment_id' => $order->payment_id,
            'order_status' => $order->payment_status
        ]);
    }

    public function success(Order $order)
    {
        if ($order->payment_status !== 'completed') {
            // Double-check payment status
            $status = $this->eppayService->checkPaymentStatus($order->payment_id);
            if ($status) {
                $order->update([
                    'payment_status' => 'completed',
                    'status' => 'processing',
                ]);
            }
        }

        return view('orders.success', compact('order'));
    }
}