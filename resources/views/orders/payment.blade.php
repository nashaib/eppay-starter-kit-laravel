{{-- resources/views/orders/payment.blade.php --}}
@extends('layouts.marketplace')

@section('title', 'Complete Payment')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Payment</h1>
            <p class="text-gray-600">Scan the QR code below with your Eppay app</p>
        </div>
        
        {{-- QR Code Section --}}
        <div class="bg-gray-50 rounded-lg p-8 mb-6">
            <div class="text-center">
                <div class="inline-block p-4 bg-white rounded-lg shadow-sm" id="qrcode"></div>
                <p class="text-sm text-gray-500 mt-4">Scan with Eppay wallet app</p>
            </div>
        </div>
        
        {{-- Order Details --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="font-semibold text-blue-900 mb-3">Order Details</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-blue-700">Order Number:</span>
                    <span class="font-semibold text-blue-900">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-blue-700">Total Amount:</span>
                    <span class="font-bold text-blue-900 text-lg">${{ number_format($order->total_amount, 2) }} USDT</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-blue-700">Payment ID:</span>
                    <span class="font-mono text-xs text-blue-800">{{ $order->payment_id }}</span>
                </div>
            </div>
        </div>
        
        {{-- Payment Status --}}
        <div id="status-message" class="text-center mb-6">
            <div class="inline-flex items-center text-gray-600">
                <svg class="animate-spin h-5 w-5 mr-3 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Waiting for payment confirmation...</span>
            </div>
        </div>
        
        {{-- Manual Check Button --}}
        <div class="text-center">
            <button onclick="manualCheck()" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                Check payment status
            </button>
        </div>

        {{-- Help Section --}}
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="text-center text-sm text-gray-500">
                <p>Having trouble? Make sure you:</p>
                <ul class="mt-2 space-y-1">
                    <li>• Have the Eppay app installed</li>
                    <li>• Have sufficient USDT balance</li>
                    <li>• Scan the QR code properly</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
// Generate QR Code
new QRCode(document.getElementById("qrcode"), {
    text: "product=uuideppay&id={{ $order->payment_id }}",
    width: 200,
    height: 200,
    colorDark: "#1e40af",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

// Check payment status every 3 seconds
let checkInterval = setInterval(checkPaymentStatus, 3000);
let checkCount = 0;

function checkPaymentStatus() {
    checkCount++;
    
    fetch("{{ route('order.check-status', $order) }}")
        .then(response => response.json())
        .then(data => {
            console.log('Payment status response:', data);
            
            if (data.status === true) {
                clearInterval(checkInterval);
                document.getElementById('status-message').innerHTML = 
                    '<div class="text-green-600 font-semibold">' +
                    '<svg class="w-6 h-6 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">' +
                    '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>' +
                    '</svg>' +
                    'Payment successful! Redirecting...</div>';
                setTimeout(() => {
                    window.location.href = "{{ route('order.success', $order) }}";
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error checking payment status:', error);
        });
    
    // Stop checking after 5 minutes (100 checks * 3 seconds)
    if (checkCount > 100) {
        clearInterval(checkInterval);
        document.getElementById('status-message').innerHTML = 
            '<div class="text-yellow-600">' +
            '<svg class="w-6 h-6 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">' +
            '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>' +
            '</svg>' +
            'Payment timeout. Please check your transaction or try again.</div>';
    }
}

function manualCheck() {
    console.log('Manual check triggered');
    // Reset the message
    document.getElementById('status-message').innerHTML = 
        '<div class="inline-flex items-center text-gray-600">' +
        '<svg class="animate-spin h-5 w-5 mr-3 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">' +
        '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
        '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>' +
        '</svg>' +
        '<span>Checking payment status...</span>' +
        '</div>';
    checkPaymentStatus();
}
</script>
@endsection