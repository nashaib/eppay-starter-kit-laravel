<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Services\EppayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private $eppayService;

    public function __construct(EppayService $eppayService)
    {
        $this->eppayService = $eppayService;
    }

    public function index()
    {
        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $shippingCost = $this->calculateShipping($cartItems);
        $taxAmount = $this->calculateTax($subtotal);
        $total = $subtotal + $shippingCost + $taxAmount;

        return view('checkout.index', compact('cartItems', 'subtotal', 'shippingCost', 'taxAmount', 'total'));
    }

    public function process(Request $request)
    {
        Log::info('Checkout process started', ['user_id' => Auth::id()]);

        try {
            $validated = $request->validate([
                'shipping_address.name' => 'required|string|max:255',
                'shipping_address.address' => 'required|string|max:255',
                'shipping_address.city' => 'required|string|max:255',
                'shipping_address.state' => 'required|string|max:255',
                'shipping_address.postal_code' => 'required|string|max:20',
                'shipping_address.country' => 'required|string|max:255',
                'shipping_address.phone' => 'required|string|max:20',
                'billing_same_as_shipping' => 'required|in:0,1',
                'billing_address.name' => 'required_if:billing_same_as_shipping,0|nullable|string|max:255',
                'billing_address.address' => 'required_if:billing_same_as_shipping,0|nullable|string|max:255',
                'billing_address.city' => 'required_if:billing_same_as_shipping,0|nullable|string|max:255',
                'billing_address.state' => 'required_if:billing_same_as_shipping,0|nullable|string|max:255',
                'billing_address.postal_code' => 'required_if:billing_same_as_shipping,0|nullable|string|max:20',
                'billing_address.country' => 'required_if:billing_same_as_shipping,0|nullable|string|max:255',
            ]);

            Log::info('Validation passed', ['data' => $validated]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            throw $e;
        }

        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            Log::warning('Cart is empty during checkout');
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        Log::info('Cart items found', ['count' => $cartItems->count()]);

        try {
            DB::beginTransaction();

            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            $shippingCost = $this->calculateShipping($cartItems);
            $taxAmount = $this->calculateTax($subtotal);
            $totalAmount = $subtotal + $shippingCost + $taxAmount;

            Log::info('Order totals calculated', [
                'subtotal' => $subtotal,
                'shipping' => $shippingCost,
                'tax' => $taxAmount,
                'total' => $totalAmount
            ]);

            // Group items by seller
            $itemsBySeller = $cartItems->groupBy(function ($item) {
                return $item->product->seller_id ?? 0;
            });

            $orders = [];

            foreach ($itemsBySeller as $sellerId => $sellerItems) {
                $orderNumber = 'EPP-' . strtoupper(Str::random(8));
                
                // Get seller
                $seller = $sellerId ? \App\Models\Seller::find($sellerId) : null;
                
                // Calculate seller-specific totals
                $sellerSubtotal = $sellerItems->sum(function ($item) {
                    return $item->quantity * $item->price;
                });
                
                // Calculate commission
                $commissionRate = $seller ? $seller->commission_rate : 10; // Default 10% if no seller
                $commissionAmount = ($sellerSubtotal * $commissionRate) / 100;
                
                Log::info('Creating order', [
                    'order_number' => $orderNumber,
                    'seller_id' => $sellerId,
                    'commission_rate' => $commissionRate,
                    'commission_amount' => $commissionAmount
                ]);

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => Auth::id(),
                    'seller_id' => $sellerId ?: null,
                    'customer_name' => $request->shipping_address['name'],
                    'customer_email' => Auth::user()->email,
                    'subtotal' => $sellerSubtotal,
                    'shipping_cost' => $shippingCost,
                    'tax_amount' => $taxAmount,
                    'commission_amount' => $commissionAmount,
                    'total_amount' => $totalAmount,
                    'shipping_address' => $request->shipping_address,
                    'billing_address' => $request->billing_same_as_shipping == '1'
                        ? $request->shipping_address 
                        : $request->billing_address,
                    'shipping_method' => 'standard',
                    'items' => $sellerItems->map(function ($item) {
                        return [
                            'product_id' => $item->product->id,
                            'name' => $item->product->name,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'total' => $item->price * $item->quantity,
                        ];
                    })->toArray(),
                ]);

                Log::info('Order created', ['order_id' => $order->id]);

                // Update product stock
                foreach ($sellerItems as $item) {
                    $product = $item->product;
                    $product->decrement('stock', $item->quantity);
                    Log::info('Stock updated', [
                        'product_id' => $product->id,
                        'quantity' => $item->quantity
                    ]);
                }

                $orders[] = $order;
            }

            // Clear cart after successful order creation
            $this->clearCart();
            Log::info('Cart cleared');

            DB::commit();
            Log::info('Transaction committed');

            // If single order, proceed to payment
            if (count($orders) === 1) {
                Log::info('Initiating payment for single order');
                return $this->initiatePayment($orders[0]);
            }

            // If multiple orders, show order summary
            Log::info('Multiple orders created', ['count' => count($orders)]);
            return redirect()->route('orders.index')
                ->with('success', 'Orders created successfully. Please complete payment for each order.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Checkout error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred during checkout: ' . $e->getMessage());
        }
    }

    private function initiatePayment(Order $order)
    {
        Log::info('Initiating payment', ['order_id' => $order->id]);

        $seller = $order->seller;
        
        // IMPORTANT: All payments go to platform wallet first
        // The platform will handle payouts to sellers after deducting commission
        $walletAddress = config('services.eppay.wallet_address');
        
        Log::info('Using platform wallet for payment collection', [
            'order_id' => $order->id,
            'seller_id' => $seller->id ?? 'none',
            'commission_rate' => $seller->commission_rate ?? 10,
            'commission_amount' => $order->commission_amount,
            'platform_wallet' => $walletAddress
        ]);

        if (!$walletAddress) {
            Log::error('No platform wallet address configured');
            return back()->with('error', 'Payment configuration error. Please contact support.');
        }

        $successUrl = "https://eppay.io/payment-success";
        
        Log::info('Calling Eppay service', [
            'amount' => $order->total_amount,
            'success_url' => $successUrl,
            'wallet' => $walletAddress
        ]);

        $paymentData = $this->eppayService->generatePayment(
            $order->total_amount,
            $successUrl,
            $walletAddress // Always use platform wallet
        );

        if ($paymentData && isset($paymentData['paymentId'])) {
            Log::info('Payment generated successfully', ['payment_id' => $paymentData['paymentId']]);
            $order->update(['payment_id' => $paymentData['paymentId']]);
            return redirect()->route('order.payment', $order);
        }

        Log::error('Failed to generate payment', ['payment_data' => $paymentData]);
        return back()->with('error', 'Failed to generate payment. Please check your Eppay configuration and try again.');
    }

    private function getCartItems()
    {
        $sessionId = session('cart_session_id');
        $userId = Auth::id();

        return Cart::with(['product.seller'])
            ->where(function ($query) use ($sessionId, $userId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })->get();
    }

    private function clearCart()
    {
        $sessionId = session('cart_session_id');
        $userId = Auth::id();

        Cart::where(function ($query) use ($sessionId, $userId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->delete();
    }

    private function calculateShipping($items)
    {
        // Basic shipping calculation
        $baseShipping = 5.00;
        $perItemShipping = 1.00;
        
        return $baseShipping + ($items->sum('quantity') * $perItemShipping);
    }

    private function calculateTax($subtotal)
    {
        // Basic tax calculation (e.g., 10%)
        $taxRate = 0.10;
        return round($subtotal * $taxRate, 2);
    }

    private function isValidWalletAddress($address)
    {
        // Basic validation for Ethereum-style addresses
        return preg_match('/^0x[a-fA-F0-9]{40}$/', $address);
    }
}