{{-- resources/views/seller/orders/show.blade.php --}}
@extends('layouts.seller')

@section('title', 'Order Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('seller.orders.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back to Orders
        </a>
    </div>

    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-3xl font-bold">Order #{{ $order->order_number }}</h1>
            <p class="text-gray-600 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t H:i') }}</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                @if($order->payment_status === 'completed') bg-green-100 text-green-800
                @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                @else bg-red-100 text-red-800
                @endif">
                Payment: {{ ucfirst($order->payment_status) }}
            </span>
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                @if($order->status === 'completed') bg-green-100 text-green-800
                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                @else bg-red-100 text-red-800
                @endif">
                Status: {{ ucfirst($order->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Order Items & Status Update --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Items --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-semibold">Order Items</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center space-x-4 pb-4 border-b last:border-0 last:pb-0">
                                @php
                                    $product = \App\Models\Product::find($item['product_id'] ?? 0);
                                @endphp
                                @if($product && $product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $item['name'] ?? 'Product' }}"
                                         class="w-16 h-16 rounded object-cover">
                                @else
                                    <div class="w-16 h-16 rounded bg-gray-200"></div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-medium">{{ $item['name'] ?? 'Product' }}</h3>
                                    @if($product)
                                        <p class="text-sm text-gray-500">SKU: {{ $product->sku }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="font-medium">${{ number_format($item['price'] ?? 0, 2) }} x {{ $item['quantity'] ?? 1 }}</p>
                                    <p class="text-sm text-gray-500">${{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Order Summary --}}
                    <div class="mt-6 pt-6 border-t">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Subtotal</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if($order->shipping_cost > 0)
                                <div class="flex justify-between text-sm">
                                    <span>Shipping</span>
                                    <span>${{ number_format($order->shipping_cost, 2) }}</span>
                                </div>
                            @endif
                            @if($order->tax_amount > 0)
                                <div class="flex justify-between text-sm">
                                    <span>Tax</span>
                                    <span>${{ number_format($order->tax_amount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between font-semibold text-lg pt-2 border-t">
                                <span>Total</span>
                                <span>${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            @if($order->commission_amount > 0)
                                <div class="flex justify-between text-sm text-gray-500 pt-2">
                                    <span>Platform Fee ({{ $order->seller->commission_rate }}%)</span>
                                    <span>-${{ number_format($order->commission_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between font-medium">
                                    <span>Your Earnings</span>
                                    <span>${{ number_format($order->seller_amount, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Update Order Status --}}
            @if($order->payment_status === 'completed')
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold">Update Order Status</h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('seller.orders.update-status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-end space-x-4">
                                <div class="flex-1">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select name="status" id="status" 
                                            class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Update Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Shipping Information --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-semibold">Shipping Information</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('seller.orders.update-tracking', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <div>
                                <label for="shipping_method" class="block text-sm font-medium text-gray-700 mb-2">Shipping Method</label>
                                <input type="text" name="shipping_method" id="shipping_method" 
                                       value="{{ $order->shipping_method }}"
                                       placeholder="e.g., Standard Shipping, Express Delivery"
                                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                                <input type="text" name="tracking_number" id="tracking_number" 
                                       value="{{ $order->tracking_number }}"
                                       placeholder="Enter tracking number"
                                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                                Update Shipping Info
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Customer Information --}}
        <div class="space-y-6">
            {{-- Customer Details --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-semibold">Customer Information</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Name</p>
                            <p class="font-medium">{{ $order->customer_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{ $order->customer_email }}</p>
                        </div>
                        @if($order->user)
                            <div>
                                <p class="text-sm text-gray-500">Phone</p>
                                <p class="font-medium">{{ $order->user->phone ?? 'Not provided' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            @if($order->shipping_address)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold">Shipping Address</h2>
                    </div>
                    <div class="p-6">
                        <div class="text-sm">
                            @if(is_array($order->shipping_address))
                                <p>{{ $order->shipping_address['address'] ?? '' }}</p>
                                <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                                <p>{{ $order->shipping_address['country'] ?? '' }}</p>
                            @else
                                <p class="text-gray-500">No shipping address provided</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Payment Information --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-semibold">Payment Information</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Payment Method</p>
                            <p class="font-medium">Eppay Crypto Payment</p>
                        </div>
                        @if($order->payment_id)
                            <div>
                                <p class="text-sm text-gray-500">Payment ID</p>
                                <p class="font-mono text-xs">{{ $order->payment_id }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-500">Payment Status</p>
                            <p class="font-medium">{{ ucfirst($order->payment_status) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Notes --}}
            @if($order->notes)
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold">Order Notes</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm">{{ $order->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection