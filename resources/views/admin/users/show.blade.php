{{-- resources/views/admin/users/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'User Details')
@section('header', 'User Details')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Back to Users
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- User Information --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Info --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold">User Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Name</p>
                            <p class="font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="font-medium">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Joined</p>
                            <p class="font-medium">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email Verified</p>
                            <p class="font-medium">
                                @if($user->hasVerifiedEmail())
                                    <span class="text-green-600">Yes</span>
                                    <span class="text-xs text-gray-500">({{ $user->email_verified_at->format('M d, Y') }})</span>
                                @else
                                    <span class="text-red-600">No</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">User ID</p>
                            <p class="font-medium">#{{ $user->id }}</p>
                        </div>
                    </div>

                    @if($user->address || $user->city || $user->state || $user->country)
                        <div class="pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-2">Address</p>
                            <p class="font-medium">
                                {{ $user->address }}<br>
                                {{ $user->city }}{{ $user->state ? ', ' . $user->state : '' }} {{ $user->postal_code }}<br>
                                {{ $user->country }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold">Recent Orders</h3>
                </div>
                <div class="p-6">
                    @if($user->orders->count() > 0)
                        <div class="space-y-4">
                            @foreach($user->orders()->latest()->take(5)->get() as $order)
                                <div class="flex items-center justify-between pb-4 border-b last:border-0 last:pb-0">
                                    <div>
                                        <p class="font-medium">#{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium">${{ number_format($order->total_amount, 2) }}</p>
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
                        <p class="text-gray-500">No orders yet</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Statistics & Actions --}}
        <div class="space-y-6">
            {{-- Statistics --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Statistics</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Orders</span>
                        <span class="font-medium">{{ $stats['total_orders'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Completed Orders</span>
                        <span class="font-medium">{{ $stats['completed_orders'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Spent</span>
                        <span class="font-medium">${{ number_format($stats['total_spent'], 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Reviews Given</span>
                        <span class="font-medium">{{ $stats['reviews_given'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="block w-full bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700">
                        Edit User
                    </a>
                    @if(!$user->hasVerifiedEmail())
                        <form action="{{ route('admin.users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $user->name }}">
                            <input type="hidden" name="email" value="{{ $user->email }}">
                            <input type="hidden" name="phone" value="{{ $user->phone }}">
                            <input type="hidden" name="verified" value="1">
                            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                                Mark as Verified
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Recent Reviews</h3>
                @if($user->reviews->count() > 0)
                    <div class="space-y-3">
                        @foreach($user->reviews()->with('product')->latest()->take(3)->get() as $review)
                            <div class="pb-3 border-b last:border-0 last:pb-0">
                                <p class="text-sm font-medium">{{ $review->product->name }}</p>
                                <div class="flex items-center mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                @if($review->comment)
                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($review->comment, 50) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No reviews yet</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection