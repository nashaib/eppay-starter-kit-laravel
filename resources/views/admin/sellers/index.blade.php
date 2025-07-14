{{-- resources/views/admin/sellers/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Sellers Management')
@section('header', 'Sellers Management')

@section('content')
<div class="p-6">
    {{-- Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Total Sellers</p>
            <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Suspended</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['suspended'] }}</p>
        </div>
    </div>

    {{-- Search and Filters --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('admin.sellers.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[300px]">
                <input type="text" name="search" placeholder="Search by name, email or shop name..." 
                       value="{{ request('search') }}"
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select name="status" class="px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.sellers.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Sellers Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shop</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stats</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sellers as $seller)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $seller->shop_name }}</div>
                                <div class="text-sm text-gray-500">{{ $seller->shop_slug }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $seller->name }}</div>
                            <div class="text-sm text-gray-500">{{ $seller->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $seller->total_products }} products</div>
                            <div class="text-sm text-gray-500">${{ number_format($seller->total_sales, 2) }} sales</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium">{{ $seller->commission_rate }}%</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.sellers.update-status', $seller) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" 
                                        class="text-xs rounded-full px-2 py-1 font-semibold
                                        @if($seller->status === 'active') bg-green-100 text-green-800
                                        @elseif($seller->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                    <option value="pending" {{ $seller->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="active" {{ $seller->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="suspended" {{ $seller->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.sellers.show', $seller) }}" 
                               class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            <a href="{{ route('admin.sellers.edit', $seller) }}" 
                               class="text-blue-600 hover:text-blue-900">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No sellers found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($sellers->hasPages())
            <div class="px-6 py-4">
                {{ $sellers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection