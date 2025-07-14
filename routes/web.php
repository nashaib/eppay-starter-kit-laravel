<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;

// Seller Controllers
use App\Http\Controllers\Seller\Auth\SellerAuthController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ProfileController as SellerProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SellerController as AdminSellerController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PayoutController as AdminPayoutController;

// Include Laravel's auth routes
require __DIR__.'/auth.php';

// Public routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{category:slug}', [ProductController::class, 'category'])->name('products.category');
Route::get('/sellers/{seller:shop_slug}', [ProductController::class, 'seller'])->name('products.seller');

// Cart routes (guest and authenticated)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{cart}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{cart}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
});

// Buyer authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/order/create', [OrderController::class, 'create'])->name('order.create');
    Route::get('/order/{order}/payment', [OrderController::class, 'payment'])->name('order.payment');
    Route::get('/order/{order}/check-status', [OrderController::class, 'checkStatus'])->name('order.check-status');
    Route::get('/order/{order}/success', [OrderController::class, 'success'])->name('order.success');
    
    // Wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/toggle', [WishlistController::class, 'toggle'])->name('toggle');
        Route::delete('/remove/{wishlist}', [WishlistController::class, 'remove'])->name('remove');
    });
    
    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// Seller routes
Route::prefix('seller')->name('seller.')->group(function () {
    // Guest routes
    Route::middleware('guest:seller')->group(function () {
        Route::get('/login', [SellerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [SellerAuthController::class, 'login']);
        Route::get('/register', [SellerAuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [SellerAuthController::class, 'register']);
    });
    
    // Authenticated routes
    Route::middleware('seller.auth')->group(function () {
        Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [SellerAuthController::class, 'logout'])->name('logout');
        
        // Products
        Route::resource('products', SellerProductController::class);
        Route::post('/products/{product}/toggle-status', [SellerProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::delete('/products/{product}/images/{image}', [SellerProductController::class, 'deleteImage'])->name('products.images.delete');
        
        // Orders
        Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::patch('/orders/{order}/tracking', [SellerOrderController::class, 'updateTracking'])->name('orders.update-tracking');
        
        // Profile
        Route::get('/profile', [SellerProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [SellerProfileController::class, 'update'])->name('profile.update');
        Route::patch('/shop', [SellerProfileController::class, 'updateShop'])->name('shop.update');
    });
});

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
    
    // Authenticated routes
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        // Sellers management
        Route::resource('sellers', AdminSellerController::class);
        Route::patch('/sellers/{seller}/status', [AdminSellerController::class, 'updateStatus'])->name('sellers.update-status');
        
        // Categories
        Route::resource('categories', AdminCategoryController::class);
        
        // Users
        Route::resource('users', AdminUserController::class)->only(['index', 'show', 'edit', 'update']);
        
        // Payouts
        Route::get('/payouts', [AdminPayoutController::class, 'index'])->name('payouts.index');
        Route::get('/payouts/create', [AdminPayoutController::class, 'create'])->name('payouts.create');
        Route::post('/payouts', [AdminPayoutController::class, 'store'])->name('payouts.store');
        Route::get('/payouts/{payout}', [AdminPayoutController::class, 'show'])->name('payouts.show');
    });
});