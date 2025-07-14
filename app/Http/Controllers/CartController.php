<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        
        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $sessionId = $this->getSessionId();
        $userId = Auth::id();

        $cart = Cart::where(function ($query) use ($sessionId, $userId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->where('product_id', $product->id)->first();

        if ($cart) {
            $newQuantity = $cart->quantity + $request->quantity;
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Insufficient stock available.');
            }
            $cart->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);
        }

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cart->product->stock < $request->quantity) {
            return response()->json(['error' => 'Insufficient stock available.'], 422);
        }

        $cart->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'subtotal' => number_format($cart->subtotal, 2),
            'total' => number_format($this->getCartTotal(), 2),
        ]);
    }

    public function remove(Cart $cart)
    {
        $cart->delete();
        
        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $sessionId = $this->getSessionId();
        $userId = Auth::id();

        Cart::where(function ($query) use ($sessionId, $userId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->delete();

        return back()->with('success', 'Cart cleared.');
    }

    private function getCartItems()
    {
        $sessionId = $this->getSessionId();
        $userId = Auth::id();

        return Cart::with('product')
            ->where(function ($query) use ($sessionId, $userId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })->get();
    }

    private function getCartTotal()
    {
        return $this->getCartItems()->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    private function getSessionId()
    {
        if (!session()->has('cart_session_id')) {
            session(['cart_session_id' => Str::uuid()->toString()]);
        }
        
        return session('cart_session_id');
    }

    // Merge guest cart with user cart on login
    public function mergeCart()
    {
        if (!Auth::check()) {
            return;
        }

        $sessionId = $this->getSessionId();
        $userId = Auth::id();

        // Get guest cart items
        $guestCartItems = Cart::where('session_id', $sessionId)
            ->where('user_id', null)
            ->get();

        foreach ($guestCartItems as $guestItem) {
            // Check if user already has this product in cart
            $userCartItem = Cart::where('user_id', $userId)
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($userCartItem) {
                // Merge quantities
                $userCartItem->update([
                    'quantity' => $userCartItem->quantity + $guestItem->quantity
                ]);
                $guestItem->delete();
            } else {
                // Transfer to user
                $guestItem->update(['user_id' => $userId]);
            }
        }
    }
}