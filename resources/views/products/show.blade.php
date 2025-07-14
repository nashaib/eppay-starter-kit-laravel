{{-- resources/views/products/show.blade.php --}}
@extends('layouts.marketplace')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <nav class="text-sm mb-6">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800">Home</a>
                <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                    <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                </svg>
            </li>
            @if($product->category)
                <li class="flex items-center">
                    <a href="{{ route('products.category', $product->category->slug) }}" class="text-gray-600 hover:text-gray-800">
                        {{ $product->category->name }}
                    </a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
            @endif
            <li>
                <span class="text-gray-800">{{ $product->name }}</span>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Product Images --}}
        <div>
            <div class="bg-white rounded-lg shadow p-4">
                @if($product->images->count() > 0)
                    <div x-data="{ activeImage: 0 }">
                        {{-- Main Image Display --}}
                        <div class="mb-4">
                            @foreach($product->images as $index => $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="{{ $product->name }}"
                                     x-show="activeImage === {{ $index }}"
                                     class="w-full h-96 object-contain rounded">
                            @endforeach
                        </div>
                        
                        {{-- Thumbnail Gallery --}}
                        @if($product->images->count() > 1)
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($product->images as $index => $image)
                                    <button @click="activeImage = {{ $index }}"
                                            :class="{ 'ring-2 ring-blue-500': activeImage === {{ $index }} }"
                                            class="border rounded overflow-hidden">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-20 object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @elseif($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-96 object-contain rounded">
                @else
                    <div class="w-full h-96 bg-gray-200 rounded flex items-center justify-center">
                        <span class="text-gray-400">No image available</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Product Information --}}
        <div>
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
                
                {{-- Seller Info --}}
                <div class="mb-4">
                    <p class="text-gray-600">
                        Sold by 
                        <a href="{{ route('products.seller', $product->seller->shop_slug) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            {{ $product->seller->shop_name }}
                        </a>
                    </p>
                </div>

                {{-- Rating --}}
                <div class="flex items-center mb-4">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                        <span class="ml-2 text-gray-600">({{ $product->reviews_count }} reviews)</span>
                    </div>
                </div>

                {{-- Price --}}
                <div class="mb-6">
                    <p class="text-4xl font-bold text-gray-800">${{ number_format($product->price, 2) }}</p>
                </div>

                {{-- Stock Status --}}
                <div class="mb-6">
                    @if($product->stock > 0)
                        <p class="text-green-600 font-medium">✓ In Stock ({{ $product->stock }} available)</p>
                    @else
                        <p class="text-red-600 font-medium">✗ Out of Stock</p>
                    @endif
                </div>

                {{-- Add to Cart Form --}}
                @if($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="mb-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="flex items-center space-x-4 mb-4">
                            <label for="quantity" class="text-gray-700">Quantity:</label>
                            <select name="quantity" id="quantity" 
                                    class="px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @for($i = 1; $i <= min(10, $product->stock); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" 
                                    class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition">
                                Add to Cart
                            </button>
                            
                            @auth
                                <button type="button" 
                                        onclick="toggleWishlist({{ $product->id }})"
                                        class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                    <svg class="w-6 h-6 {{ $isWishlisted ? 'text-red-500 fill-current' : 'text-gray-400' }}" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            @endauth
                        </div>
                    </form>
                @endif

                {{-- Product Details --}}
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold mb-3">Product Details</h3>
                    <div class="space-y-2 text-gray-600">
                        @if($product->sku)
                            <p><span class="font-medium">SKU:</span> {{ $product->sku }}</p>
                        @endif
                        @if($product->weight)
                            <p><span class="font-medium">Weight:</span> {{ $product->weight }} kg</p>
                        @endif
                        <p><span class="font-medium">Category:</span> 
                            <a href="{{ route('products.category', $product->category->slug) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                {{ $product->category->name }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Product Description --}}
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4">Description</h3>
        <div class="prose max-w-none text-gray-600">
            {!! nl2br(e($product->description)) !!}
        </div>
    </div>

    {{-- Reviews Section --}}
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4">Customer Reviews</h3>
        
        @if($product->reviews->count() > 0)
            <div class="space-y-4">
                @foreach($product->reviews as $review)
                    <div class="border-b pb-4 last:border-0">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <span class="font-medium">{{ $review->user->name }}</span>
                                <span class="text-gray-500 text-sm ml-2">{{ $review->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                            <p class="text-gray-600">{{ $review->comment }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
        @endif
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4">Related Products</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                        <a href="{{ route('products.show', $relatedProduct->slug) }}">
                            @if($relatedProduct->primaryImage)
                                <img src="{{ asset('storage/' . $relatedProduct->primaryImage->image_path) }}" 
                                     alt="{{ $relatedProduct->name }}"
                                     class="w-full h-48 object-cover rounded-t-lg">
                            @elseif($relatedProduct->image)
                                <img src="{{ asset('storage/' . $relatedProduct->image) }}" 
                                     alt="{{ $relatedProduct->name }}"
                                     class="w-full h-48 object-cover rounded-t-lg">
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                    <span class="text-gray-400">No image</span>
                                </div>
                            @endif
                        </a>
                        <div class="p-4">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" 
                               class="text-lg font-semibold hover:text-blue-600 line-clamp-2">
                                {{ $relatedProduct->name }}
                            </a>
                            <p class="text-sm text-gray-500 mt-1">{{ $relatedProduct->seller->shop_name }}</p>
                            <div class="flex items-center justify-between mt-4">
                                <span class="text-xl font-bold text-gray-800">${{ number_format($relatedProduct->price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@auth
<script>
function toggleWishlist(productId) {
    fetch('{{ route("wishlist.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endauth
@endsection