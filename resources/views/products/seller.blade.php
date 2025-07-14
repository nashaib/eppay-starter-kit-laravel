{{-- resources/views/products/seller.blade.php --}}
@extends('layouts.marketplace')

@section('title', $seller->shop_name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Seller Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <div class="flex items-center space-x-4">
                @if($seller->logo)
                    <img src="{{ asset('storage/' . $seller->logo) }}" alt="{{ $seller->shop_name }}" 
                         class="w-20 h-20 rounded-lg object-cover">
                @else
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <span class="text-white text-2xl font-bold">{{ substr($seller->shop_name, 0, 2) }}</span>
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $seller->shop_name }}</h1>
                    <p class="text-gray-600 mt-1">{{ $seller->description }}</p>
                    <div class="flex items-center space-x-4 mt-2">
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            {{ $seller->products()->count() }} Products
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Member since {{ $seller->created_at->format('M Y') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex items-center">
                    @php
                        $rating = $seller->products()->with('reviews')->get()->pluck('reviews')->flatten()->avg('rating') ?? 0;
                        $reviewCount = $seller->products()->with('reviews')->get()->pluck('reviews')->flatten()->count();
                    @endphp
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="ml-2 text-sm text-gray-600">{{ number_format($rating, 1) }} ({{ $reviewCount }} reviews)</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <div class="flex items-center space-x-4">
                <p class="text-sm text-gray-600">
                    Showing <span class="font-medium text-gray-900">{{ $products->count() }}</span> products
                </p>
                @if($categories->count() > 0)
                    <select onchange="filterByCategory(this.value)" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-600">Sort by:</label>
                <select onchange="sortProducts(this.value)" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5">
                    <option value="">Latest</option>
                    <option value="price_low">Price: Low to High</option>
                    <option value="price_high">Price: High to Low</option>
                    <option value="popular">Most Popular</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Products Grid --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                    <a href="{{ route('products.show', $product->slug) }}" class="block">
                        <div class="relative aspect-w-1 aspect-h-1 overflow-hidden bg-gray-100">
                            @if($product->primaryImage)
                                <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                            @elseif($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-56 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            @if($product->is_featured)
                                <div class="absolute top-3 left-3">
                                    <span class="bg-yellow-400 text-yellow-900 text-xs px-2 py-1 rounded-full font-medium">
                                        Featured
                                    </span>
                                </div>
                            @endif
                        </div>
                    </a>
                    
                    <div class="p-4">
                        @if($product->category)
                            <a href="{{ route('products.category', $product->category->slug) }}" 
                               class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                {{ $product->category->name }}
                            </a>
                        @endif
                        
                        <h3 class="font-semibold text-gray-900 mt-2 mb-1 line-clamp-2 group-hover:text-blue-600 transition-colors">
                            <a href="{{ route('products.show', $product->slug) }}">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        @if($product->reviews_count > 0)
                            <div class="flex items-center mb-2">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-500 ml-1">({{ $product->reviews_count }})</span>
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-between mt-3">
                            <p class="text-xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                            
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        
                        @if($product->stock > 10)
                            <span class="text-xs text-green-600 flex items-center mt-2">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                In Stock
                            </span>
                        @elseif($product->stock > 0)
                            <span class="text-xs text-yellow-600 flex items-center mt-2">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Low Stock
                            </span>
                        @else
                            <span class="text-xs text-red-600 flex items-center mt-2">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Out of Stock
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <p class="text-gray-500 text-lg">No products available from this seller yet</p>
            <p class="text-gray-400 text-sm mt-2">Check back later for new arrivals</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function filterByCategory(category) {
        const currentUrl = new URL(window.location);
        if (category) {
            currentUrl.searchParams.set('category', category);
        } else {
            currentUrl.searchParams.delete('category');
        }
        window.location.href = currentUrl.toString();
    }

    function sortProducts(sort) {
        const currentUrl = new URL(window.location);
        if (sort) {
            currentUrl.searchParams.set('sort', sort);
        } else {
            currentUrl.searchParams.delete('sort');
        }
        window.location.href = currentUrl.toString();
    }
</script>
@endpush
@endsection