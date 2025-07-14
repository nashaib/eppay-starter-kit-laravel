{{-- resources/views/orders/show.blade.php --}}
@extends('layouts.marketplace')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Order Details</h1>
                <p class="text-gray-600 mt-1">Order #{{ $order->order_number }}</p>
            </div>
            <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Orders
            </a>
        </div>
    </div>

    {{-- Order Status Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        {{-- Order Status --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Order Status</h3>
            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                @if($order->status === 'completed') bg-green-100 text-green-800
                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800
                @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        {{-- Payment Status --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Payment Status</h3>
            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                @if($order->payment_status === 'completed') bg-green-100 text-green-800
                @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                @else bg-red-100 text-red-800
                @endif">
                @if($order->payment_status === 'completed')
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                @endif
                {{ ucfirst($order->payment_status) }}
            </span>
        </div>

        {{-- Order Date --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Order Date</h3>
            <p class="text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
            <p class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</p>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-6">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center space-x-4 py-4 border-b border-gray-100 last:border-0">
                        <div class="flex-shrink-0">
                            @php
                                $product = \App\Models\Product::find($item['product_id']);
                            @endphp
                            @if($product && $product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $item['name'] }}"
                                     class="w-20 h-20 object-cover rounded-lg">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $item['name'] }}</h4>
                            <p class="text-sm text-gray-500">Quantity: {{ $item['quantity'] }}</p>
                            <p class="text-sm text-gray-500">Price: ${{ number_format($item['price'], 2) }} each</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">${{ number_format($item['total'] ?? ($item['price'] * $item['quantity']), 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Order Summary --}}
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="text-gray-900">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Shipping</span>
                        <span class="text-gray-900">${{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tax</span>
                        <span class="text-gray-900">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-semibold pt-2 border-t">
                        <span>Total</span>
                        <span class="text-blue-600">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Shipping & Billing Information --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Shipping Address --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Shipping Address
            </h3>
            @if($order->shipping_address)
                <div class="text-sm text-gray-600 space-y-1">
                    <p class="font-medium text-gray-900">{{ $order->shipping_address['name'] }}</p>
                    <p>{{ $order->shipping_address['address'] }}</p>
                    <p>{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['postal_code'] }}</p>
                    <p>{{ $order->shipping_address['country'] }}</p>
                    <p class="pt-2">Phone: {{ $order->shipping_address['phone'] }}</p>
                </div>
            @endif
        </div>

        {{-- Billing Address --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Billing Address
            </h3>
            @if($order->billing_address)
                <div class="text-sm text-gray-600 space-y-1">
                    <p class="font-medium text-gray-900">{{ $order->billing_address['name'] }}</p>
                    <p>{{ $order->billing_address['address'] }}</p>
                    <p>{{ $order->billing_address['city'] }}, {{ $order->billing_address['state'] }} {{ $order->billing_address['postal_code'] }}</p>
                    <p>{{ $order->billing_address['country'] }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Additional Information --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            {{-- Seller Information --}}
            @if($order->seller)
                <div>
                    <span class="text-gray-600">Seller:</span>
                    <span class="ml-2 font-medium text-gray-900">{{ $order->seller->shop_name }}</span>
                </div>
            @endif

            {{-- Payment Method --}}
            <div>
                <span class="text-gray-600">Payment Method:</span>
                <span class="ml-2 font-medium text-gray-900">Eppay (USDT)</span>
            </div>

            {{-- Shipping Method --}}
            <div>
                <span class="text-gray-600">Shipping Method:</span>
                <span class="ml-2 font-medium text-gray-900">{{ ucfirst($order->shipping_method) }}</span>
            </div>

            {{-- Tracking Number --}}
            @if($order->tracking_number)
                <div>
                    <span class="text-gray-600">Tracking Number:</span>
                    <span class="ml-2 font-medium text-gray-900">{{ $order->tracking_number }}</span>
                </div>
            @endif
        </div>

        {{-- Order Notes --}}
        @if($order->notes)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <h4 class="text-sm font-medium text-gray-600 mb-2">Order Notes</h4>
                <p class="text-sm text-gray-900">{{ $order->notes }}</p>
            </div>
        @endif
    </div>

    {{-- Action Buttons --}}
    <div class="mt-8 flex flex-col sm:flex-row gap-4">
        @if($order->status === 'processing' || $order->status === 'pending')
            <button class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                Contact Seller
            </button>
        @endif

        @if($order->status === 'completed')
            <a href="{{ route('products.show', ['product' => $order->items[0]['product_id']]) }}" 
               class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-center">
                Leave a Review
            </a>
        @endif

        <button onclick="window.print()" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Print Invoice
        </button>
    </div>
</div>
@endsection