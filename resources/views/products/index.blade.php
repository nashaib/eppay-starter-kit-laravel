{{-- resources/views/products/index.blade.php --}}
@extends('layouts.marketplace')

@section('title', 'All Products')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">All Products</h1>
        <p class="text-gray-600 mt-2">Discover amazing products from our trusted sellers</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Sidebar Filters --}}
        <div class="lg:w-1/4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-20">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                            Filters
                        </h3>
                        @if(request()->hasAny(['category', 'min_price', 'max_price', 'seller']))
                            <a href="{{ route('products.index') }}" class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                                Clear all
                            </a>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('products.index') }}">
                        {{-- Categories --}}
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Categories
                            </h4>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                @foreach($categories as $category)
                                    <div>
                                        <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer transition-colors">
                                            <input type="checkbox" name="category[]" value="{{ $category->slug }}"
                                                   {{ in_array($category->slug, (array)request('category')) ? 'checked' : '' }}
                                                   class="text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                            <span class="ml-3 text-sm text-gray-700">{{ $category->name }}</span>
                                            <span class="ml-auto text-xs text-gray-500">({{ $category->products_count ?? 0 }})</span>
                                        </label>
                                        
                                        {{-- Child Categories --}}
                                        @if($category->children->count() > 0)
                                            <div class="ml-6 mt-1 space-y-1">
                                                @foreach($category->children as $child)
                                                    <label class="flex items-center p-1 rounded hover:bg-gray-50 cursor-pointer transition-colors">
                                                        <input type="checkbox" name="category[]" value="{{ $child->slug }}"
                                                               {{ in_array($child->slug, (array)request('category')) ? 'checked' : '' }}
                                                               class="text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                                        <span class="ml-2 text-xs text-gray-600">{{ $child->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Price Range --}}
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Price Range
                            </h4>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-500">$</span>
                                    <input type="number" name="min_price" placeholder="Min" 
                                           value="{{ request('min_price') }}"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-500">$</span>
                                    <input type="number" name="max_price" placeholder="Max" 
                                           value="{{ request('max_price') }}"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        {{-- Sellers --}}
                        @if($sellers->count() > 0)
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Sellers
                                </h4>
                                <select name="seller" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">All Sellers</option>
                                    @foreach($sellers as $seller)
                                        <option value="{{ $seller->id }}" {{ request('seller') == $seller->id ? 'selected' : '' }}>
                                            {{ $seller->shop_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Apply Filters
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Products Grid --}}
        <div class="lg:w-3/4">
            {{-- Sort and View Options --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                    <p class="text-sm text-gray-600">
                        Showing <span class="font-medium text-gray-900">{{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}</span> 
                        of <span class="font-medium text-gray-900">{{ $products->total() }}</span> products
                    </p>
                    <div class="flex items-center space-x-4">
                        {{-- View Toggle --}}
                        <div class="flex items-center space-x-2 border-r pr-4">
                            <button class="p-2 rounded hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </button>
                            <button class="p-2 rounded hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                        
                        {{-- Sort Dropdown --}}
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-600">Sort by:</label>
                            <select name="sort" onchange="window.location.href='{{ route('products.index') }}?sort=' + this.value + '{{ request()->has('search') ? '&search=' . request('search') : '' }}'"
                                    class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Latest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                <option value="bestselling" {{ request('sort') == 'bestselling' ? 'selected' : '' }}>Best Selling</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Products Grid --}}
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                            <a href="{{ route('products.show', $product->slug) }}" class="block">
                                <div class="relative aspect-w-1 aspect-h-1 overflow-hidden bg-gray-100">
                                    @if($product->primaryImage)
                                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                                    @elseif($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                                            <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    {{-- Quick Actions --}}
                                    <div class="absolute top-3 right-3 space-y-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @auth
                                            <button class="bg-white p-2 rounded-full shadow-md hover:shadow-lg transition-shadow" 
                                                    onclick="addToWishlist({{ $product->id }}); event.preventDefault();">
                                                <svg class="w-5 h-5 text-gray-600 hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </button>
                                        @endauth
                                    </div>
                                    
                                    {{-- Badges --}}
                                    @if($product->is_featured)
                                        <div class="absolute top-3 left-3">
                                            <span class="bg-yellow-400 text-yellow-900 text-xs px-2 py-1 rounded-full font-medium">
                                                Featured
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                            
                            <div class="p-5">
                                {{-- Category & Seller --}}
                                <div class="flex items-center justify-between mb-2">
                                    @if($product->category)
                                        <a href="{{ route('products.category', $product->category->slug) }}" 
                                           class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                            {{ $product->category->name }}
                                        </a>
                                    @endif
                                    <a href="{{ route('products.seller', $product->seller->shop_slug) }}" 
                                       class="text-xs text-gray-500 hover:text-gray-700 transition-colors flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        {{ $product->seller->shop_name }}
                                    </a>
                                </div>
                                
                                {{-- Product Name --}}
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                
                                {{-- Rating --}}
                                @if($product->reviews_count > 0)
                                    <div class="flex items-center mb-2">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-xs text-gray-500 ml-2">({{ $product->reviews_count }})</span>
                                    </div>
                                @endif
                                
                                {{-- Price and Actions --}}
                                <div class="flex items-center justify-between mt-4">
                                    <p class="text-2xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                                    
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center text-sm font-medium">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            Add to Cart
                                        </button>
                                    </form>
                                </div>
                                
                                {{-- Stock Status --}}
                                <div class="mt-3">
                                    @if($product->stock > 10)
                                        <span class="text-xs text-green-600 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            In Stock
                                        </span>
                                    @elseif($product->stock > 0)
                                        <span class="text-xs text-yellow-600 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Only {{ $product->stock }} left
                                        </span>
                                    @else
                                        <span class="text-xs text-red-600 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            Out of Stock
                                        </span>
                                    @endif
                                </div>
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
                    <p class="text-gray-500 text-lg mb-2">No products found</p>
                    <p class="text-gray-400 text-sm mb-6">Try adjusting your filters or search terms</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        View All Products
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function addToWishlist(productId) {
        // Add AJAX call to wishlist endpoint
        fetch(`/wishlist/add/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            // Handle success/error
            if (data.success) {
                // Show success notification
            }
        });
    }
</script>
@endpush
@endsection