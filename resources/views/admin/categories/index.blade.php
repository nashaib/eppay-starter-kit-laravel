{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Categories Management')
@section('header', 'Categories Management')

@section('content')
<div class="p-6">
    {{-- Header with Add Button --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <p class="text-gray-600">Manage product categories and subcategories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Add New Category
        </a>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" placeholder="Search categories..." 
                       value="{{ request('search') }}"
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Categories Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                         alt="{{ $category->name }}"
                                         class="h-10 w-10 rounded object-cover mr-3">
                                @else
                                    <div class="h-10 w-10 rounded bg-gray-200 mr-3"></div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $category->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($category->parent)
                                <span class="text-sm text-gray-900">{{ $category->parent->name }}</span>
                            @else
                                <span class="text-sm text-gray-500">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $category->products()->count() }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $category->order }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($category->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.categories.edit', $category) }}" 
                               class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            
                            @if($category->products()->count() === 0 && $category->children()->count() === 0)
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            @else
                                <span class="text-gray-400" title="Cannot delete category with products or subcategories">Delete</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No categories found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($categories->hasPages())
            <div class="px-6 py-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection