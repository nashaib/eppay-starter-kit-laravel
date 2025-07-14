{{-- resources/views/orders/success.blade.php --}}
@extends('layouts.marketplace')

@section('title', 'Order Successful')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
        {{-- Success Icon --}}
        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Successful!</h1>
        <p class="text-gray-600 mb-8">Thank you for your purchase. Your order has been confirmed.</p>

        {{-- Order Details --}}
        <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left max-w-md mx-auto">
            <h3 class="font-semibold text-gray-900 mb-4">Order Summary</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Order Number:</span>
                    <span class="font-medium text-gray-900">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Amount:</span>
                    <span class="font-medium text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Payment Status:</span>
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium text-green-600">Paid</span>
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Order Status:</span>
                    <span class="font-medium text-gray-900">{{ ucfirst($order->status) }}</span>
                </div>
            </div>
        </div>

        {{-- What's Next --}}
        <div class="bg-blue-50 rounded-lg p-6 mb-8 max-w-md mx-auto">
            <h3 class="font-semibold text-blue-900 mb-3">What happens next?</h3>
            <ul class="text-sm text-blue-800 space-y-2 text-left">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>You'll receive an order confirmation email shortly</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>The seller will process your order</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>You'll get tracking information once shipped</span>
                </li>
            </ul>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('orders.show', $order) }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                View Order Details
            </a>
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection