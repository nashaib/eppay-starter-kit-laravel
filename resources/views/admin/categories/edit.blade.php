{{-- resources/views/admin/categories/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Category')
@section('header', 'Edit Category')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back to Categories
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold">Edit Category</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        {{-- Category Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                   placeholder="e.g., Electronics">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- URL Slug --}}
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                URL Slug <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror"
                                   placeholder="e.g., electronics">
                            @error('slug')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">This will be used in the category URL</p>
                        </div>

                        {{-- Parent Category --}}
                        <div>
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Parent Category
                            </label>
                            <select name="parent_id" id="parent_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('parent_id') border-red-500 @enderror">
                                <option value="">None (Top Level Category)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" 
                                            {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}
                                            {{ $cat->id === $category->id ? 'disabled' : '' }}>
                                        {{ $cat->name }}
                                        {{ $cat->id === $category->id ? '(Current Category)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                      placeholder="Brief description of the category">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category Image --}}
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Category Image
                            </label>
                            
                            @if($category->image)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 mb-2">Current image:</p>
                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                         alt="{{ $category->name }}"
                                         class="h-32 w-32 object-cover rounded border">
                                </div>
                            @endif
                            
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image') border-red-500 @enderror">
                            @error('image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Upload new image to replace current one. Recommended size: 300x300px. Max 2MB.</p>
                        </div>

                        {{-- Display Order --}}
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                                Display Order
                            </label>
                            <input type="number" name="order" id="order" value="{{ old('order', $category->order) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('order') border-red-500 @enderror"
                                   placeholder="0">
                            @error('order')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="is_active" value="1" 
                                           {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                           class="mr-2">
                                    <span>Active</span>
                                    <span class="text-sm text-gray-500 ml-2">- Visible on the website</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="is_active" value="0" 
                                           {{ !old('is_active', $category->is_active) ? 'checked' : '' }}
                                           class="mr-2">
                                    <span>Inactive</span>
                                    <span class="text-sm text-gray-500 ml-2">- Hidden from customers</span>
                                </label>
                            </div>
                            @error('is_active')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category Info --}}
                        <div class="bg-gray-50 p-4 rounded">
                            <h4 class="font-medium mb-3">Category Information</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Total Products</p>
                                    <p class="font-medium">{{ $category->products()->count() }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Subcategories</p>
                                    <p class="font-medium">{{ $category->children()->count() }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Created</p>
                                    <p class="font-medium">{{ $category->created_at->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Last Updated</p>
                                    <p class="font-medium">{{ $category->updated_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <div>
                            @if($category->products()->count() === 0 && $category->children()->count() === 0)
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this category?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        Delete Category
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="space-x-3">
                            <a href="{{ route('admin.categories.index') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Update Category
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection