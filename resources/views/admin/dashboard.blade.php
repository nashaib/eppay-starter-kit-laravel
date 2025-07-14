{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

@section('content')
<div class="p-6">
    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Users --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Users</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total_users']) }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Active Sellers --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Active Sellers</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['active_sellers']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['pending_sellers'] }} pending</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Products --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Products</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total_products']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['active_products'] }} active</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Orders</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total_orders']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['completed_orders'] }} completed</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm mb-2">Total Revenue</p>
            <p class="text-2xl font-bold text-green-600">${{ number_format($revenue['total'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm mb-2">Today's Revenue</p>
            <p class="text-2xl font-bold text-blue-600">${{ number_format($revenue['today'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm mb-2">This Month</p>
            <p class="text-2xl font-bold text-purple-600">${{ number_format($revenue['this_month'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm mb-2">Commission Earned</p>
            <p class="text-2xl font-bold text-yellow-600">${{ number_format($revenue['commission_earned'], 2) }}</p>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Orders Chart --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Orders (Last 7 Days)</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>

        {{-- Revenue Chart --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Revenue (Last 7 Days)</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Recent Activities --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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
                                    <p class="text-sm text-gray-500">
                                        {{ $order->user ? $order->user->name : $order->customer_name }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold">${{ number_format($order->total_amount, 2) }}</p>
                                    <span class="inline-block px-2 py-1 text-xs rounded-full
                                        @if($order->payment_status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No recent orders</p>
                @endif
            </div>
        </div>

        {{-- Pending Sellers --}}
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold">Pending Seller Approvals</h3>
            </div>
            <div class="p-6">
                @if($pendingSellers->count() > 0)
                    <div class="space-y-4">
                        @foreach($pendingSellers as $seller)
                            <div class="flex items-center justify-between pb-4 border-b last:border-0 last:pb-0">
                                <div>
                                    <p class="font-medium">{{ $seller->shop_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $seller->name }}</p>
                                    <p class="text-xs text-gray-400">Applied {{ $seller->created_at->diffForHumans() }}</p>
                                </div>
                                <a href="{{ route('admin.sellers.show', $seller) }}" 
                                   class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                    Review
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No pending approvals</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Orders Chart
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    const ordersChart = new Chart(ordersCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Orders',
                data: {!! json_encode($chartData['orders']) !!},
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
                        precision: 0
                    }
                }
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($chartData['revenue']) !!},
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 1
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
                            return '
    });
</script>
@endpush
@endsection