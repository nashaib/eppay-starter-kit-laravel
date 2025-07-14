{{-- resources/views/home.blade.php --}}
@extends('layouts.marketplace')

@section('title', 'Home')

@section('content')
{{-- Hero Section --}}
<div class="bg-blue-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4">Welcome to Eppay Marketplace</h1>
            <p class="text-xl mb-8">Buy and sell with crypto payments powered by Eppay</p>
            <div class="space-x-4">
                <a href="{{ route('products.index') }}" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block">
                    Shop Now
                </a>
                <a href="{{ route('seller.register') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-400 inline-block">
                    Start Selling
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Features Section --}}
<div class="py-16 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">Why Choose Eppay Marketplace?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-blue-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Crypto Payments</h3>
                <p class="text-gray-600">Secure and fast payments with various stable tokens on multiple blockchains</p>
            </div>
            <div class="text-center">
                <div class="bg-green-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Secure Transactions</h3>
                <p class="text-gray-600">Built-in escrow system ensures safe transactions for buyers and sellers</p>
            </div>
            <div class="text-center">
                <div class="bg-purple-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Low Fees</h3>
                <p class="text-gray-600">Competitive commission rates and no hidden charges</p>
            </div>
        </div>
    </div>
</div>

{{-- Featured Products --}}
<div class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold mb-8">Featured Products</h2>
        
        @php
            // Get featured products or latest products
            $featuredProducts = \App\Models\Product::with(['seller', 'primaryImage'])
                ->active()
                ->latest()
                ->take(8)
                ->get();
        @endphp

        @if($featuredProducts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                        <a href="{{ route('products.show', $product->slug) }}">
                            @if($product->primaryImage)
                                <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-48 object-cover rounded-t-lg">
                            @elseif($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-48 object-cover rounded-t-lg">
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                    <span class="text-gray-400">No image</span>
                                </div>
                            @endif
                        </a>
                        <div class="p-4">
                            <a href="{{ route('products.show', $product->slug) }}" 
                               class="text-lg font-semibold hover:text-blue-600 line-clamp-2">
                                {{ $product->name }}
                            </a>
                            <p class="text-sm text-gray-500 mt-1">
                                by <a href="{{ route('products.seller', $product->seller->shop_slug) }}" 
                                      class="hover:text-blue-600">{{ $product->seller->shop_name }}</a>
                            </p>
                            <div class="flex items-center justify-between mt-4">
                                <span class="text-2xl font-bold text-gray-800">${{ number_format($product->price, 2) }}</span>
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                            Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <span class="text-red-500 text-sm">Out of Stock</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('products.index') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 inline-block">
                    View All Products
                </a>
            </div>
        @else
            <p class="text-center text-gray-500">No products available yet.</p>
        @endif
    </div>
</div>

{{-- Categories Section --}}
<div class="py-16 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold mb-8">Shop by Category</h2>
        
        @php
            $categories = \App\Models\Category::active()->parents()->with('children')->get();
        @endphp

        @if($categories->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('products.category', $category->slug) }}" 
                       class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow text-center">
                        <h3 class="font-semibold text-lg">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $category->products()->count() }} products</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection