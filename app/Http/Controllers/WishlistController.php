<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wishlistItems = Auth::user()->wishlist()->with('product.seller')->get();
        
        return view('wishlist.index', compact('wishlistItems'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Product removed from wishlist';
            $isWishlisted = false;
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            $message = 'Product added to wishlist';
            $isWishlisted = true;
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'isWishlisted' => $isWishlisted,
            ]);
        }

        return back()->with('success', $message);
    }

    public function remove(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }

        $wishlist->delete();

        return back()->with('success', 'Product removed from wishlist');
    }
}