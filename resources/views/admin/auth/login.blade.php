{{-- resources/views/admin/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Eppay Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-gray-800 p-8 rounded-lg shadow-2xl w-full max-w-md">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white">Admin Portal</h2>
                <p class="text-gray-400 mt-2">Eppay Store Administration</p>
            </div>
            
            @if(session('success'))
                <div class="bg-green-900 border border-green-700 text-green-300 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-900 border border-red-700 text-red-300 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="mb-6">
                    <label for="email" class="block text-gray-300 text-sm font-bold mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror"
                           placeholder="admin@example.com">
                    @error('email')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-300 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 @error('password') border-red-500 @enderror"
                           placeholder="••••••••">
                    @error('password')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center text-gray-300">
                        <input type="checkbox" name="remember" class="mr-2 bg-gray-700 border-gray-600">
                        <span class="text-sm">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded hover:bg-blue-700 transition duration-200">
                    Sign In
                </button>
            </form>

            <div class="mt-8 text-center">
                <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-gray-300">← Back to Store</a>
            </div>

            <div class="mt-6 p-4 bg-gray-700 rounded text-xs text-gray-400">
                <p class="font-semibold text-gray-300 mb-2">Security Notice:</p>
                <p>This is a restricted area. Unauthorized access is prohibited and may be subject to legal action.</p>
            </div>
        </div>
    </div>
</body>
</html>