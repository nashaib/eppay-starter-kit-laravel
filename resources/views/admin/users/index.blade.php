{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Users Management')
@section('header', 'Users Management')

@section('content')
<div class="p-6">
    {{-- Search and Filters --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[300px]">
                <input type="text" name="search" placeholder="Search by name or email..." 
                       value="{{ request('search') }}"
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select name="verified" class="px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Users</option>
                    <option value="yes" {{ request('verified') == 'yes' ? 'selected' : '' }}>Verified</option>
                    <option value="no" {{ request('verified') == 'no' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>
            <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Filter
            </button>
            @if(request()->hasAny(['search', 'verified']))
                <a href="{{ route('admin.users.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $user->id }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                            @if($user->phone)
                                <div class="text-sm text-gray-500">{{ $user->phone }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->orders()->count() }} orders</div>
                            <div class="text-sm text-gray-500">${{ number_format($user->total_spent, 2) }} spent</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->hasVerifiedEmail())
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Verified
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Unverified
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="text-blue-600 hover:text-blue-900">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No users found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($users->hasPages())
            <div class="px-6 py-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection