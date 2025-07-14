<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['seller', 'category', 'primaryImage'])
            ->active();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $categoryIds = $category->getAllChildrenIds();
                $categoryIds[] = $category->id;
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Seller filter
        if ($request->filled('seller')) {
            $query->where('seller_id', $request->seller);
        }

        // Sort
        switch ($request->sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'bestselling':
                $query->orderBy('sales_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(24)->withQueryString();
        $categories = Category::active()->parents()->with('children')->get();
        $sellers = Seller::active()->get();

        // Get price range
        $minPrice = Product::active()->min('price');
        $maxPrice = Product::active()->max('price');

        return view('products.index', compact(
            'products', 
            'categories', 
            'sellers',
            'minPrice',
            'maxPrice'
        ));
    }

    public function show($slug)
    {
        $product = Product::with(['seller', 'category', 'images', 'reviews.user'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count
        $product->incrementViewCount();

        // Get related products
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['primaryImage'])
            ->limit(4)
            ->get();

        // Check if user has wishlisted this product
        $isWishlisted = false;
        if (auth()->check()) {
            $isWishlisted = auth()->user()->hasWishlisted($product->id);
        }

        return view('products.show', compact('product', 'relatedProducts', 'isWishlisted'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->active()->firstOrFail();
        
        $products = $category->allProducts()
            ->with(['seller', 'primaryImage'])
            ->active()
            ->paginate(24);

        $categories = Category::active()->parents()->with('children')->get();

        return view('products.category', compact('category', 'products', 'categories'));
    }

    public function seller($shopSlug)
    {
        $seller = Seller::where('shop_slug', $shopSlug)
            ->where('status', 'active')
            ->firstOrFail();

        $products = $seller->products()
            ->with(['category', 'primaryImage'])
            ->active()
            ->paginate(24);

        $categories = Category::active()
            ->whereHas('products', function ($query) use ($seller) {
                $query->where('seller_id', $seller->id);
            })
            ->get();

        return view('products.seller', compact('seller', 'products', 'categories'));
    }
}