{{-- resources/views/checkout/index.blade.php --}}
@extends('layouts.marketplace')

@section('title', 'Checkout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Oops! There were some errors:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Display session errors --}}
    @if (session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.process') }}" id="checkout-form">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - Shipping & Billing Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Shipping Address --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Shipping Address
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Full Name --}}
                        <div class="md:col-span-2">
                            <label for="shipping_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="shipping_address[name]" id="shipping_name" 
                                   value="{{ old('shipping_address.name', auth()->user()->name ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_address.name') border-red-300 @enderror">
                            @error('shipping_address.name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="md:col-span-2">
                            <label for="shipping_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="shipping_address[phone]" id="shipping_phone" 
                                   value="{{ old('shipping_address.phone', auth()->user()->phone ?? '') }}" required
                                   placeholder="+1 (555) 123-4567"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_address.phone') border-red-300 @enderror">
                            @error('shipping_address.phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div class="md:col-span-2">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Street Address <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="shipping_address[address]" id="shipping_address" 
                                   value="{{ old('shipping_address.address', auth()->user()->address ?? '') }}" required
                                   placeholder="123 Main Street, Apt 4B"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_address.address') border-red-300 @enderror">
                            @error('shipping_address.address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- City --}}
                        <div>
                            <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-2">
                                City <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="shipping_address[city]" id="shipping_city" 
                                   value="{{ old('shipping_address.city', auth()->user()->city ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_address.city') border-red-300 @enderror">
                            @error('shipping_address.city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- State --}}
                        <div>
                            <label for="shipping_state" class="block text-sm font-medium text-gray-700 mb-2">
                                State/Province <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="shipping_address[state]" id="shipping_state" 
                                   value="{{ old('shipping_address.state', auth()->user()->state ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_address.state') border-red-300 @enderror">
                            @error('shipping_address.state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Postal Code --}}
                        <div>
                            <label for="shipping_postal" class="block text-sm font-medium text-gray-700 mb-2">
                                Postal Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="shipping_address[postal_code]" id="shipping_postal" 
                                   value="{{ old('shipping_address.postal_code', auth()->user()->postal_code ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_address.postal_code') border-red-300 @enderror">
                            @error('shipping_address.postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Country --}}
                        <div>
                            <label for="shipping_country" class="block text-sm font-medium text-gray-700 mb-2">
                                Country <span class="text-red-500">*</span>
                            </label>
                            <select name="shipping_address[country]" id="shipping_country" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_address.country') border-red-300 @enderror">
                                <option value="">Select Country</option>
                                <option value="US" {{ old('shipping_address.country', auth()->user()->country ?? '') == 'US' ? 'selected' : '' }}>United States</option>
                                <option value="CA" {{ old('shipping_address.country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                <option value="GB" {{ old('shipping_address.country') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="AU" {{ old('shipping_address.country') == 'AU' ? 'selected' : '' }}>Australia</option>
                                <option value="DE" {{ old('shipping_address.country') == 'DE' ? 'selected' : '' }}>Germany</option>
                                <option value="FR" {{ old('shipping_address.country') == 'FR' ? 'selected' : '' }}>France</option>
                                <option value="JP" {{ old('shipping_address.country') == 'JP' ? 'selected' : '' }}>Japan</option>
                                <option value="CN" {{ old('shipping_address.country') == 'CN' ? 'selected' : '' }}>China</option>
                                <option value="IN" {{ old('shipping_address.country') == 'IN' ? 'selected' : '' }}>India</option>
                            </select>
                            @error('shipping_address.country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Billing Address --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Billing Address
                    </h2>

                    <div class="mb-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="hidden" name="billing_same_as_shipping" value="0">
                            <input type="checkbox" name="billing_same_as_shipping" id="billing_same" value="1" checked
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Same as shipping address</span>
                        </label>
                    </div>

                    <div id="billing_fields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Billing Name --}}
                        <div class="md:col-span-2">
                            <label for="billing_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name
                            </label>
                            <input type="text" name="billing_address[name]" id="billing_name" 
                                   value="{{ old('billing_address.name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('billing_address.name') border-red-300 @enderror">
                            @error('billing_address.name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Billing Address --}}
                        <div class="md:col-span-2">
                            <label for="billing_address_field" class="block text-sm font-medium text-gray-700 mb-2">
                                Street Address
                            </label>
                            <input type="text" name="billing_address[address]" id="billing_address_field" 
                                   value="{{ old('billing_address.address') }}"
                                   placeholder="123 Main Street, Apt 4B"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('billing_address.address') border-red-300 @enderror">
                            @error('billing_address.address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Billing City --}}
                        <div>
                            <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-2">
                                City
                            </label>
                            <input type="text" name="billing_address[city]" id="billing_city" 
                                   value="{{ old('billing_address.city') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('billing_address.city') border-red-300 @enderror">
                            @error('billing_address.city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Billing State --}}
                        <div>
                            <label for="billing_state" class="block text-sm font-medium text-gray-700 mb-2">
                                State/Province
                            </label>
                            <input type="text" name="billing_address[state]" id="billing_state" 
                                   value="{{ old('billing_address.state') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('billing_address.state') border-red-300 @enderror">
                            @error('billing_address.state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Billing Postal Code --}}
                        <div>
                            <label for="billing_postal" class="block text-sm font-medium text-gray-700 mb-2">
                                Postal Code
                            </label>
                            <input type="text" name="billing_address[postal_code]" id="billing_postal" 
                                   value="{{ old('billing_address.postal_code') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('billing_address.postal_code') border-red-300 @enderror">
                            @error('billing_address.postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Billing Country --}}
                        <div>
                            <label for="billing_country" class="block text-sm font-medium text-gray-700 mb-2">
                                Country
                            </label>
                            <select name="billing_address[country]" id="billing_country"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('billing_address.country') border-red-300 @enderror">
                                <option value="">Select Country</option>
                                <option value="US" {{ old('billing_address.country') == 'US' ? 'selected' : '' }}>United States</option>
                                <option value="CA" {{ old('billing_address.country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                <option value="GB" {{ old('billing_address.country') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="AU" {{ old('billing_address.country') == 'AU' ? 'selected' : '' }}>Australia</option>
                                <option value="DE" {{ old('billing_address.country') == 'DE' ? 'selected' : '' }}>Germany</option>
                                <option value="FR" {{ old('billing_address.country') == 'FR' ? 'selected' : '' }}>France</option>
                                <option value="JP" {{ old('billing_address.country') == 'JP' ? 'selected' : '' }}>Japan</option>
                                <option value="CN" {{ old('billing_address.country') == 'CN' ? 'selected' : '' }}>China</option>
                                <option value="IN" {{ old('billing_address.country') == 'IN' ? 'selected' : '' }}>India</option>
                            </select>
                            @error('billing_address.country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Order Items Review --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Order Items
                    </h2>

                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            <div class="flex items-center space-x-4 py-3 border-b border-gray-100 last:border-0">
                                <div class="flex-shrink-0">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}"
                                             class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h4>
                                    <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                                    @if($item->product->seller)
                                        <p class="text-xs text-gray-500">Seller: {{ $item->product->seller->shop_name }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right Column - Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium">${{ number_format($shippingCost, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium">${{ number_format($taxAmount, 2) }}</span>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold">Total</span>
                                <span class="text-lg font-bold text-blue-600">${{ number_format($total, 2) }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Payable in USDT</p>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Payment Method
                        </h3>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-blue-900">Eppay Crypto Payment</p>
                                    <p class="text-xs text-blue-700">Secure payment with cryptocurrency</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Place Order Button --}}
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center" id="place-order-btn">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span id="btn-text">Place Order - ${{ number_format($total, 2) }}</span>
                        <div id="btn-spinner" class="hidden ml-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </button>

                    {{-- Security Notice --}}
                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-500 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v3m8 0H6"></path>
                            </svg>
                            Secure checkout powered by Eppay
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Toggle billing address fields
document.getElementById('billing_same').addEventListener('change', function() {
    const billingFields = document.getElementById('billing_fields');
    if (this.checked) {
        billingFields.classList.add('hidden');
    } else {
        billingFields.classList.remove('hidden');
    }
});

// Handle form submission with loading state
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const btn = document.getElementById('place-order-btn');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');
    
    // Disable button and show spinner
    btn.disabled = true;
    btn.classList.add('opacity-75', 'cursor-not-allowed');
    btnText.textContent = 'Processing...';
    btnSpinner.classList.remove('hidden');
});
</script>
@endsection