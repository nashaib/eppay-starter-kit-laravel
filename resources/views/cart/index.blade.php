{{-- resources/views/cart/index.blade.php --}}
@extends('layouts.marketplace')

@section('title', 'Shopping Cart')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Cart Items --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        @foreach($cartItems as $item)
                            <div class="flex items-center space-x-4 py-4 {{ !$loop->last ? 'border-b' : '' }}">
                                {{-- Product Image --}}
                                <div class="flex-shrink-0">
                                    @if($item->product->primaryImage)
                                        <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" 
                                             alt="{{ $item->product->name }}"
                                             class="w-24 h-24 object-cover rounded">
                                    @elseif($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}"
                                             class="w-24 h-24 object-cover rounded">
                                    @else
                                        <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                                            <span class="text-gray-400">No image</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Product Details --}}
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold">
                                        <a href="{{ route('products.show', $item->product->slug) }}" 
                                           class="hover:text-blue-600">
                                            {{ $item->product->name }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Sold by {{ $item->product->seller->shop_name }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Price: ${{ number_format($item->price, 2) }}
                                    </p>
                                    
                                    @if($item->product->stock < $item->quantity)
                                        <p class="text-sm text-red-600 mt-1">
                                            Only {{ $item->product->stock }} left in stock
                                        </p>
                                    @endif
                                </div>

                                {{-- Quantity and Actions --}}
                                <div class="flex items-center space-x-4">
                                    {{-- Quantity Update --}}
                                    <div class="flex items-center space-x-2">
                                        <label for="quantity-{{ $item->id }}" class="text-sm text-gray-600">Qty:</label>
                                        <select id="quantity-{{ $item->id }}" 
                                                onchange="updateQuantity({{ $item->id }}, this.value)"
                                                class="px-2 py-1 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            @for($i = 1; $i <= min(10, $item->product->stock); $i++)
                                                <option value="{{ $i }}" {{ $item->quantity == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    {{-- Subtotal --}}
                                    <div class="text-right">
                                        <p class="font-semibold">${{ number_format($item->subtotal, 2) }}</p>
                                    </div>

                                    {{-- Remove Button --}}
                                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800"
                                                title="Remove from cart">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Clear Cart --}}
                    <div class="px-6 py-4 bg-gray-50 rounded-b-lg">
                        <form action="{{ route('cart.clear') }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to clear your cart?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                Clear Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Order Summary --}}
            <div>
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span>Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span class="text-gray-600">Calculated at checkout</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax</span>
                            <span class="text-gray-600">Calculated at checkout</span>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4 mb-6">
                        <div class="flex justify-between font-semibold text-lg">
                            <span>Total</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                    </div>

                    @auth
                         <a href="{{ route('checkout.index') }}" 
                            class="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition text-center">
                                Proceed to Checkout
                            </a>
                    @else
                        <div class="space-y-3">
                            <a href="{{ route('login') }}?redirect={{ urlencode(route('cart.index')) }}" 
                               class="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition text-center">
                                Login to Checkout
                            </a>
                            <p class="text-sm text-center text-gray-600">or</p>
                            <a href="{{ route('register') }}" 
                               class="block w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-200 transition text-center">
                                Create Account
                            </a>
                        </div>
                    @endauth

                    <div class="mt-6 text-center">
                        <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            Continue Shopping
                        </a>
                    </div>

                    {{-- Security Badge --}}
                    <div class="mt-6 pt-6 border-t">
                        <div class="flex items-center justify-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Secure Checkout with Eppay
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Empty Cart --}}
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h2 class="text-2xl font-semibold mb-2">Your cart is empty</h2>
            <p class="text-gray-600 mb-6">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('products.index') }}" 
               class="inline-block bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition">
                Start Shopping
            </a>
        </div>
    @endif
</div>

<script>
function updateQuantity(cartId, quantity) {
    fetch(`{{ url('cart/update') }}/${cartId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else if (data.error) {
            alert(data.error);
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the quantity.');
    });
}
</script>
@endsection