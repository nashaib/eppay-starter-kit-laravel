{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Eppay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <div x-data="{ sidebarOpen: false }" class="h-screen flex overflow-hidden">
        {{-- Sidebar --}}
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
             class="fixed lg:relative lg:translate-x-0 z-40 inset-y-0 left-0 w-64 bg-gray-900 transition-transform duration-300 ease-in-out flex flex-col">
            <div class="flex items-center justify-center h-16 bg-gray-800 flex-shrink-0">
                <h1 class="text-white text-xl font-bold">Eppay Admin</h1>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white border-l-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white border-l-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Users
                </a>
                
                <a href="{{ route('admin.sellers.index') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.sellers.*') ? 'bg-gray-800 text-white border-l-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Sellers
                </a>
                
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800 text-white border-l-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Categories
                </a>

                <a href="#" 
                   class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Reports
                </a>

                <a href="#" 
                   class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Settings
                </a>
            </nav>
            
            <div class="p-4 border-t border-gray-800">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Main content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Mobile menu button --}}
            <div class="lg:hidden bg-gray-900 shadow-sm">
                <div class="flex items-center justify-between p-4">
                    <h2 class="text-xl font-semibold text-white">Admin Panel</h2>
                    <button @click="sidebarOpen = !sidebarOpen" class="text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            {{-- Top bar --}}
            <div class="bg-white shadow-sm flex-shrink-0">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center">
                        <div class="mr-4">
                            <p class="text-sm text-gray-500">Welcome,</p>
                            <p class="font-semibold">{{ auth()->guard('admin')->user()->name }}</p>
                        </div>
                        <div class="bg-gray-200 rounded-full w-10 h-10 flex items-center justify-center">
                            <span class="text-gray-600 font-semibold">
                                {{ substr(auth()->guard('admin')->user()->name, 0, 1) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto bg-gray-100">
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