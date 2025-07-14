<?php

// app/Http/Middleware/SellerAuth.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('seller')->check()) {
            return redirect()->route('seller.login');
        }

        if (Auth::guard('seller')->user()->status !== 'active') {
            Auth::guard('seller')->logout();
            return redirect()->route('seller.login')
                ->with('error', 'Your account is not active.');
        }

        return $next($request);
    }
}



