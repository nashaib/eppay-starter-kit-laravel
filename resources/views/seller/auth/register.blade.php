{{-- resources/views/seller/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration - Eppay Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-8">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-2xl">
            <h2 class="text-2xl font-bold mb-6 text-center">Register as Seller</h2>
            
            <form method="POST" action="{{ route('seller.register') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Personal Information --}}
                    <div class="col-span-2">
                        <h3 class="text-lg font-semibold mb-4">Personal Information</h3>
                    </div>

                    <div>
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                    </div>

                    {{-- Shop Information --}}
                    <div class="col-span-2 mt-4">
                        <h3 class="text-lg font-semibold mb-4">Shop Information</h3>
                    </div>

                    <div>
                        <label for="shop_name" class="block text-gray-700 text-sm font-bold mb-2">Shop Name</label>
                        <input type="text" name="shop_name" id="shop_name" value="{{ old('shop_name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('shop_name') border-red-500 @enderror">
                        @error('shop_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Business Address</label>
                        <textarea name="address" id="address" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Payment Information --}}
                    <div class="col-span-2 mt-4">
                        <h3 class="text-lg font-semibold mb-4">Payment Information</h3>
                    </div>

                    <div class="col-span-2">
                        <label for="eppay_wallet_address" class="block text-gray-700 text-sm font-bold mb-2">Eppay Wallet Address</label>
                        <input type="text" name="eppay_wallet_address" id="eppay_wallet_address" value="{{ old('eppay_wallet_address') }}" required
                               placeholder="0x..."
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('eppay_wallet_address') border-red-500 @enderror">
                        <p class="text-xs text-gray-600 mt-1">This is where you'll receive your payments</p>
                        @error('eppay_wallet_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="terms" required class="mr-2">
                        <span class="text-sm text-gray-700">I agree to the Terms of Service and Privacy Policy</span>
                    </label>
                </div>

                <button type="submit" class="w-full mt-6 bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600">
                    Register
                </button>
            </form>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="{{ route('seller.login') }}" class="text-blue-500 hover:text-blue-700">Login</a>
                </p>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-800">← Back to Marketplace</a>
            </div>
        </div>
    </div>
</body>
</html>