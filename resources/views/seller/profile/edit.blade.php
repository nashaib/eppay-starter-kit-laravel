{{-- resources/views/seller/profile/edit.blade.php --}}
@extends('layouts.seller')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Profile Settings</h1>

    <div class="space-y-8">
        {{-- Personal Information --}}
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold">Personal Information</h2>
                <p class="text-sm text-gray-600 mt-1">Update your personal details</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('seller.profile.update') }}">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $seller->name) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $seller->email) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $seller->phone) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 border-t pt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-4">Change Password</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                <input type="password" name="current_password" id="current_password"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-500 @enderror">
                                @error('current_password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <input type="password" name="new_password" id="new_password"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('new_password') border-red-500 @enderror">
                                @error('new_password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Leave password fields empty if you don't want to change it</p>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Shop Information --}}
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold">Shop Information</h2>
                <p class="text-sm text-gray-600 mt-1">Manage your shop details and appearance</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('seller.shop.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="shop_name" class="block text-sm font-medium text-gray-700 mb-2">Shop Name</label>
                                <input type="text" name="shop_name" id="shop_name" value="{{ old('shop_name', $seller->shop_name) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('shop_name') border-red-500 @enderror">
                                @error('shop_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="shop_slug" class="block text-sm font-medium text-gray-700 mb-2">Shop URL Slug</label>
                                <div class="flex items-center">
                                    <span class="text-gray-500 text-sm mr-2">{{ url('/sellers') }}/</span>
                                    <input type="text" name="shop_slug" id="shop_slug" value="{{ old('shop_slug', $seller->shop_slug) }}" required
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('shop_slug') border-red-500 @enderror">
                                </div>
                                @error('shop_slug')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Shop Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $seller->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
                            <textarea name="address" id="address" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror">{{ old('address', $seller->address) }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="eppay_wallet_address" class="block text-sm font-medium text-gray-700 mb-2">Eppay Wallet Address</label>
                            <input type="text" name="eppay_wallet_address" id="eppay_wallet_address" value="{{ old('eppay_wallet_address', $seller->eppay_wallet_address) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('eppay_wallet_address') border-red-500 @enderror">
                            @error('eppay_wallet_address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Shop Logo</label>
                                @if($seller->logo)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $seller->logo) }}" alt="Current Logo" class="h-20 w-20 object-cover rounded">
                                        <p class="text-xs text-gray-500 mt-1">Current logo</p>
                                    </div>
                                @endif
                                <input type="file" name="logo" id="logo" accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('logo') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1">Square image recommended. Max 2MB.</p>
                                @error('logo')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="banner" class="block text-sm font-medium text-gray-700 mb-2">Shop Banner</label>
                                @if($seller->banner)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $seller->banner) }}" alt="Current Banner" class="h-20 w-40 object-cover rounded">
                                        <p class="text-xs text-gray-500 mt-1">Current banner</p>
                                    </div>
                                @endif
                                <input type="file" name="banner" id="banner" accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('banner') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1">1920x400 recommended. Max 4MB.</p>
                                @error('banner')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update Shop Information
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Account Information --}}
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold">Account Information</h2>
                <p class="text-sm text-gray-600 mt-1">View your account details</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Account Status</p>
                        <p class="font-medium">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($seller->status === 'active') bg-green-100 text-green-800
                                @elseif($seller->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($seller->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Commission Rate</p>
                        <p class="font-medium">{{ $seller->commission_rate }}%</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Member Since</p>
                        <p class="font-medium">{{ $seller->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Available Balance</p>
                        <p class="font-medium">${{ number_format($seller->balance, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection