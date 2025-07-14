{{-- resources/views/admin/sellers/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Seller')
@section('header', 'Edit Seller')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('admin.sellers.show', $seller) }}" class="text-gray-600 hover:text-gray-900">
            ← Back to Seller Details
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold">Edit Seller Settings</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.sellers.update', $seller) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        {{-- Seller Info (Read-only) --}}
                        <div class="bg-gray-50 p-4 rounded">
                            <h4 class="font-medium mb-3">Seller Information</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Shop Name</p>
                                    <p class="font-medium">{{ $seller->shop_name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Owner</p>
                                    <p class="font-medium">{{ $seller->name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Email</p>
                                    <p class="font-medium">{{ $seller->email }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Member Since</p>
                                    <p class="font-medium">{{ $seller->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Commission Rate --}}
                        <div>
                            <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                Commission Rate (%)
                            </label>
                            <input type="number" name="commission_rate" id="commission_rate" 
                                   value="{{ old('commission_rate', $seller->commission_rate) }}" 
                                   min="0" max="100" step="0.01" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('commission_rate') border-red-500 @enderror">
                            @error('commission_rate')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">
                                The percentage of each sale that goes to the platform as commission.
                            </p>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="pending" 
                                           {{ old('status', $seller->status) === 'pending' ? 'checked' : '' }}
                                           class="mr-2">
                                    <span>Pending</span>
                                    <span class="text-sm text-gray-500 ml-2">- Awaiting approval</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="active" 
                                           {{ old('status', $seller->status) === 'active' ? 'checked' : '' }}
                                           class="mr-2">
                                    <span>Active</span>
                                    <span class="text-sm text-gray-500 ml-2">- Can sell products</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="suspended" 
                                           {{ old('status', $seller->status) === 'suspended' ? 'checked' : '' }}
                                           class="mr-2">
                                    <span>Suspended</span>
                                    <span class="text-sm text-gray-500 ml-2">- Cannot access seller panel</span>
                                </label>
                            </div>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Impact Summary --}}
                        <div class="bg-yellow-50 p-4 rounded">
                            <h4 class="font-medium text-yellow-800 mb-2">Impact of Changes</h4>
                            <ul class="text-sm text-yellow-700 space-y-1">
                                <li>• Commission rate changes will apply to future orders only</li>
                                <li>• Changing status to "Suspended" will immediately block seller access</li>
                                <li>• Active sellers can list products and receive orders</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('admin.sellers.show', $seller) }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update Seller
                        </button>
                    </div>
                </form>

                {{-- Delete Seller (if applicable) --}}
                @if($seller->products()->count() === 0 && $seller->orders()->count() === 0)
                    <div class="mt-8 pt-8 border-t">
                        <h4 class="font-medium text-red-600 mb-2">Danger Zone</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            This seller has no products or orders and can be safely deleted.
                        </p>
                        <form action="{{ route('admin.sellers.destroy', $seller) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this seller? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Delete Seller
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection