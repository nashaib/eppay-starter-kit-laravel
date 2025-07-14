{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit User')
@section('header', 'Edit User')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('admin.users.show', $user) }}" class="text-gray-600 hover:text-gray-900">
            ← Back to User Details
        </a>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold">Edit User Information</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Verification Status</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="verified" value="1" {{ old('verified', $user->hasVerifiedEmail()) ? 'checked' : '' }}
                                           class="mr-2">
                                    <span>Verified</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="verified" value="0" {{ !old('verified', $user->hasVerifiedEmail()) ? 'checked' : '' }}
                                           class="mr-2">
                                    <span>Unverified</span>
                                </label>
                            </div>
                            @error('verified')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-4 border-t">
                            <p class="text-sm text-gray-600 mb-4">
                                <strong>Note:</strong> Changing email verification status will immediately affect the user's access to certain features.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('admin.users.show', $user) }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection