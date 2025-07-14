<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SellerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('seller.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('seller')->attempt($request->only('email', 'password'), $request->remember)) {
            if (Auth::guard('seller')->user()->status !== 'active') {
                Auth::guard('seller')->logout();
                return back()->with('error', 'Your account is not active yet.');
            }
            
            return redirect()->intended(route('seller.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('seller.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:sellers',
            'password' => 'required|confirmed|min:8',
            'shop_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'eppay_wallet_address' => 'required|string',
        ]);

        $seller = Seller::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'shop_name' => $request->shop_name,
            'shop_slug' => Str::slug($request->shop_name),
            'phone' => $request->phone,
            'address' => $request->address,
            'eppay_wallet_address' => $request->eppay_wallet_address,
            'status' => 'pending',
        ]);

        return redirect()->route('seller.login')
            ->with('success', 'Registration successful! Please wait for admin approval.');
    }

    public function logout(Request $request)
    {
        Auth::guard('seller')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('seller.login');
    }
}