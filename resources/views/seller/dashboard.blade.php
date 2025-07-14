{{-- resources/views/seller/dashboard.blade.php --}}
@extends('layouts.seller')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Seller Dashboard</h1>
    
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Today's Orders --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Today's Orders</p>
                    <p class="text-2xl font-bold">{{ $todayOrders }}</p>
                </div>
                <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
        </div>

        {{-- Today's Revenue --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Today's Revenue</p>
                    <p class="text-2xl font-bold">${{ number_format($todayRevenue, 2) }}</p>
                </div>
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        {{-- Pending Orders --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending Orders</p>
                    <p class="text-2xl font-bold">{{ $pendingOrders }}</p>
                </div>
                <svg class="w-12 h-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        {{-- Low Stock Alert --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Low Stock Products</p>
                    <p class="text-2xl font-bold">{{ $lowStockProducts }}</p>
                </div>
                <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Sales Chart --}}
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Sales Overview (Last 7 Days)</h2>
        <canvas id="salesChart" height="100"></canvas>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Recent Orders --}}
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Recent Orders</h2>
            </div>
            <div class="p-6">
                @if($recentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                            <div class="flex items-center justify-between p-4 border rounded">
                                <div>
                                    <p class="font-semibold">#{{ $order->order_number }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->customer_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold">${{ number_format($order->total_amount, 2) }}</p>
                                    <span class="inline-block px-2 py-1 text-xs rounded
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('seller.orders.index') }}" class="text-blue-500 hover:text-blue-700">View all orders →</a>
                    </div>
                @else
                    <p class="text-gray-500">No orders yet.</p>
                @endif
            </div>
        </div>

        {{-- Best Selling Products --}}
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Best Selling Products</h2>
            </div>
            <div class="p-6">
                @if($bestSellingProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($bestSellingProducts as $product)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded object-cover mr-4">
                                    @endif
                                    <div>
                                        <p class="font-semibold">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $product->sales_count }} sold</p>
                                    </div>
                                </div>
                                <p class="font-semibold">${{ number_format($product->price, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('seller.products.index') }}" class="text-blue-500 hover:text-blue-700">View all products →</a>
                    </div>
                @else
                    <p class="text-gray-500">No products yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesChartData['labels']) !!},
            datasets: [{
                label: 'Sales',
                data: {!! json_encode($salesChartData['data']) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return ' + value;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection