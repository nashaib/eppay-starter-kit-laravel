{{-- resources/views/admin/sellers/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Seller Details')
@section('header', 'Seller Details')

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('admin.sellers.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back to Sellers
        </a>
        <div>
            <form action="{{ route('admin.sellers.update-status', $seller) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()" 
                        class="px-3 py-1 rounded text-sm font-semibold
                        @if($seller->status === 'active') bg-green-100 text-green-800
                        @elseif($seller->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                    <option value="pending" {{ $seller->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="active" {{ $seller->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ $seller->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </form>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Total Products</p>
            <p class="text-2xl font-bold">{{ $stats['total_products'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Active Products</p>
            <p class="text-2xl font-bold">{{ $stats['active_products'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Total Orders</p>
            <p class="text-2xl font-bold">{{ $stats['total_orders'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Total Revenue</p>
            <p class="text-xl font-bold">${{ number_format($stats['total_revenue'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Commission ({{ $seller->commission_rate }}%)</p>
            <p class="text-xl font-bold">${{ number_format($stats['total_commission'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Seller Earnings</p>
            <p class="text-xl font-bold">${{ number_format($stats['total_revenue'] - $stats['total_commission'], 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Seller Information --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Shop Info --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold">Shop Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Shop Name</p>
                            <p class="font-medium">{{ $seller->shop_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Shop URL</p>
                            <p class="font-medium text-blue-600">
                                <a href="{{ route('products.seller', $seller->shop_slug) }}" target="_blank">
                                    {{ $seller->shop_slug }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Owner Name</p>
                            <p class="font-medium">{{ $seller->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{ $seller->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="font-medium">{{ $seller->phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Joined</p>
                            <p class="font-medium">{{ $seller->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    @if($seller->description)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-2">Description</p>
                            <p class="text-gray-700">{{ $seller->description }}</p>
                        </div>
                    @endif
                    
                    @if($seller->address)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-2">Business Address</p>
                            <p class="text-gray-700">{{ $seller->address }}</p>
                        </div>
                    @endif

                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-500 mb-2">Eppay Wallet Address</p>
                        <p class="font-mono text-sm bg-gray-100 p-2 rounded">{{ $seller->eppay_wallet_address }}</p>
                    </div>
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold">Recent Orders</h3>
                </div>
                <div class="p-6">
                    @if($recentOrders->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentOrders as $order)
                                <div class="flex items-center justify-between pb-4 border-b last:border-0 last:pb-0">
                                    <div>
                                        <p class="font-medium">#{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->user ? $order->user->name : $order->customer_name }}</p>
                                        <p class="text-xs text-gray-400">{{ $order->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium">${{ number_format($order->total_amount, 2) }}</p>
                                        <span class="inline-block px-2 py-1 text-xs rounded-full
                                            @if($order->status === 'completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No orders yet</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Actions --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.sellers.edit', $seller) }}" 
                       class="block w-full bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700">
                        Edit Seller
                    </a>
                    <a href="{{ route('products.seller', $seller->shop_slug) }}" target="_blank"
                       class="block w-full bg-gray-600 text-white text-center py-2 rounded hover:bg-gray-700">
                        View Shop
                    </a>
                    @if($seller->status === 'pending')
                        <form action="{{ route('admin.sellers.update-status', $seller) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="active">
                            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                                Approve Seller
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Top Products --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Top Products</h3>
                @if($topProducts->count() > 0)
                    <div class="space-y-3">
                        @foreach($topProducts as $product)
                            <div class="pb-3 border-b last:border-0 last:pb-0">
                                <p class="text-sm font-medium">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">{{ $product->sales_count }} sold</p>
                                <p class="text-sm font-medium text-green-600">${{ number_format($product->price, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No products yet</p>
                @endif
            </div>

            {{-- Commission Settings --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Commission Settings</h3>
                <p class="text-sm text-gray-500 mb-2">Current Rate</p>
                <p class="text-2xl font-bold mb-4">{{ $seller->commission_rate }}%</p>
                <a href="{{ route('admin.sellers.edit', $seller) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    Change Commission Rate →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection