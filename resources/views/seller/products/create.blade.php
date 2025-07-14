{{-- resources/views/seller/products/create.blade.php --}}
@extends('layouts.seller')

@section('title', 'Add New Product')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Add New Product</h1>
        <p class="text-gray-600 mt-1">Fill in the details below to add a new product to your shop.</p>
    </div>

    <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Basic Information</h2>
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="Enter product name">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category_id" id="category_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @if($category->children->count() > 0)
                                @foreach($category->children as $child)
                                    <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;&nbsp;&nbsp;{{ $child->name }}
                                    </option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" id="description" rows="5" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Describe your product">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Pricing & Inventory</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (USD) *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" required
                               step="0.01" min="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror"
                               placeholder="0.00">
                    </div>
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" required
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stock') border-red-500 @enderror"
                           placeholder="0">
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                    <input type="number" name="weight" id="weight" value="{{ old('weight') }}"
                           step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('weight') border-red-500 @enderror"
                           placeholder="0.00">
                    @error('weight')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Product Images</h2>
            
            <div class="space-y-4">
                <div>
                    <label for="primary_image" class="block text-sm font-medium text-gray-700 mb-2">Primary Image *</label>
                    <input type="file" name="primary_image" id="primary_image" required
                           accept="image/jpeg,image/png,image/jpg,image/webp"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('primary_image') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">This will be the main image shown in listings. Max 2MB.</p>
                    @error('primary_image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>
                    <input type="file" name="images[]" id="images" multiple
                           accept="image/jpeg,image/png,image/jpg,image/webp"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('images.*') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">You can upload multiple images. Max 2MB each.</p>
                    @error('images.*')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">SEO Settings</h2>
            
            <div class="space-y-4">
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Leave empty to use product name">
                    <p class="text-xs text-gray-500 mt-1">Page title for search engines (60 characters max)</p>
                </div>

                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Leave empty to use product description">{{ old('meta_description') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Page description for search engines (160 characters max)</p>
                </div>

                <div>
                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="keyword1, keyword2, keyword3">
                    <p class="text-xs text-gray-500 mt-1">Comma-separated keywords</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="space-y-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active (Visible to customers)</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Not visible)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('seller.products.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Create Product
            </button>
        </div>
    </form>
</div>
@endsection