{{-- resources/views/orders/index.blade.php --}}
@extends('layouts.marketplace')

@section('title', 'My Orders')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
        <p class="text-gray-600 mt-2">Track and manage your orders</p>
    </div>

    {{-- Orders List --}}
    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                            {{-- Order Info --}}
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-4 lg:mb-0">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Order #{{ $order->order_number }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Placed on {{ $order->created_at->format('F d, Y \a\t g:i A') }}
                                        </p>
                                    </div>
                                    <div class="lg:hidden">
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                            @if($order->status === 'completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Order Details --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                                    {{-- Seller --}}
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider">Seller</p>
                                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $order->seller->shop_name }}</p>
                                    </div>

                                    {{-- Items --}}
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider">Items</p>
                                        <p class="text-sm font-medium text-gray-900 mt-1">
                                            {{ count($order->items) }} {{ count($order->items) === 1 ? 'item' : 'items' }}
                                        </p>
                                    </div>

                                    {{-- Total --}}
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider">Total</p>
                                        <p class="text-sm font-medium text-gray-900 mt-1">${{ number_format($order->total_amount, 2) }}</p>
                                    </div>

                                    {{-- Payment Status --}}
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider">Payment</p>
                                        <p class="text-sm font-medium mt-1">
                                            <span class="inline-flex items-center">
                                                @if($order->payment_status === 'completed')
                                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-green-600">Paid</span>
                                                @elseif($order->payment_status === 'pending')
                                                    <svg class="w-4 h-4 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-yellow-600">Pending</span>
                                                @else
                                                    <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-red-600">{{ ucfirst($order->payment_status) }}</span>
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                {{-- Order Items Preview --}}
                                <div class="mt-4 flex -space-x-2 overflow-hidden">
                                    @foreach(array_slice($order->items, 0, 4) as $item)
                                        @php
                                            $product = \App\Models\Product::find($item['product_id']);
                                        @endphp
                                        @if($product && $product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 alt="{{ $item['name'] }}"
                                                 class="inline-block h-10 w-10 rounded-full ring-2 ring-white object-cover"
                                                 title="{{ $item['name'] }}">
                                        @else
                                            <div class="inline-block h-10 w-10 rounded-full ring-2 ring-white bg-gray-200 flex items-center justify-center"
                                                 title="{{ $item['name'] }}">
                                                <span class="text-xs text-gray-500">{{ substr($item['name'], 0, 1) }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                    @if(count($order->items) > 4)
                                        <div class="inline-block h-10 w-10 rounded-full ring-2 ring-white bg-gray-100 flex items-center justify-center">
                                            <span class="text-xs text-gray-600">+{{ count($order->items) - 4 }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="mt-6 lg:mt-0 lg:ml-6 flex flex-col sm:flex-row lg:flex-col space-y-2 sm:space-y-0 sm:space-x-3 lg:space-x-0 lg:space-y-2">
                                {{-- Status Badge (Desktop) --}}
                                <div class="hidden lg:block mb-2">
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>

                                {{-- View Details Button --}}
                                <a href="{{ route('orders.show', $order) }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </a>

                                {{-- Track Order Button (if applicable) --}}
                                @if($order->status === 'processing' && $order->tracking_number)
                                    <button class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                        Track Order
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
            <p class="text-gray-500 mb-6">When you make your first purchase, it will appear here.</p>
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection