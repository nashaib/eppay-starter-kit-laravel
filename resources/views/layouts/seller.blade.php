{{-- resources/views/layouts/seller.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Seller Dashboard') - Eppay Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen">
        {{-- Mobile menu button --}}
        <div class="lg:hidden fixed top-0 left-0 w-full bg-white shadow-sm z-50">
            <div class="flex items-center justify-between p-4">
                <h2 class="text-xl font-semibold">Seller Panel</h2>
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Sidebar --}}
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
             class="fixed lg:translate-x-0 z-40 inset-y-0 left-0 w-64 bg-gray-800 transition-transform duration-300 ease-in-out">
            <div class="flex items-center justify-center h-16 bg-gray-900">
                <h1 class="text-white text-xl font-bold">Eppay Seller</h1>
            </div>
            
            <nav class="mt-8">
                <a href="{{ route('seller.dashboard') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('seller.dashboard') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('seller.products.index') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('seller.products.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Products
                </a>
                
                <a href="{{ route('seller.orders.index') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('seller.orders.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Orders
                </a>
                
                <a href="{{ route('seller.profile.edit') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('seller.profile.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-full p-4">
                <form method="POST" action="{{ route('seller.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Main content --}}
        <div class="lg:ml-64">
            {{-- Top bar --}}
            <div class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="lg:hidden"></div>
                    <div class="flex-1"></div>
                    <div class="flex items-center">
                        <div class="mr-4">
                            <p class="text-sm text-gray-500">Welcome back,</p>
                            <p class="font-semibold">{{ auth()->guard('seller')->user()->name }}</p>
                        </div>
                        <div class="bg-gray-200 rounded-full w-10 h-10 flex items-center justify-center">
                            <span class="text-gray-600 font-semibold">
                                {{ substr(auth()->guard('seller')->user()->name, 0, 1) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Page content --}}
            <main class="pt-16 lg:pt-0">
                @if(session('success'))
                    <div class="mx-6 mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mx-6 mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>