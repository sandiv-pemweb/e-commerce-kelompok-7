<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Seller\SellerStoreController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\Seller\SellerBalanceController;
use App\Http\Controllers\Seller\SellerWithdrawalController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerCategoryController;
use App\Http\Controllers\Seller\SellerProductImageController;
use App\Http\Controllers\Admin\AdminStoreController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminWithdrawalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{store:slug}/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/stores/{store}', [StoreController::class, 'show'])->name('stores.show');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    // Wishlist routes
    Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{wishlist}', [App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Payment routes
    Route::get('/payment/{transaction}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{transaction}/upload', [PaymentController::class, 'uploadProof'])->name('payment.upload');

    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    
    // Review routes
    Route::post('/reviews', [App\Http\Controllers\Buyer\ProductReviewController::class, 'store'])->name('reviews.store');
});

// Seller Routes (available to all authenticated users)
Route::prefix('seller')->name('seller.')->middleware(['auth', 'verified'])->group(function () {
    // Store registration/management (available to all authenticated users)
    Route::get('/stores/create', [SellerStoreController::class, 'create'])->name('stores.create');
    Route::post('/stores', [SellerStoreController::class, 'store'])->name('stores.store');
    Route::get('/stores/edit', [SellerStoreController::class, 'edit'])->name('stores.edit');
    Route::patch('/stores', [SellerStoreController::class, 'update'])->name('stores.update');
    Route::delete('/stores', [SellerStoreController::class, 'destroy'])->name('stores.destroy');
    Route::patch('/stores/restore', [SellerStoreController::class, 'restore'])->name('stores.restore');

    // Routes that require verified store
    Route::middleware('seller')->group(function () {
        // Orders
        Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}', [SellerOrderController::class, 'update'])->name('orders.update');

        // Balance & Withdrawals
        Route::get('/balance', [SellerBalanceController::class, 'index'])->name('balance.index');
        
        // Redirect old withdrawals index to balance page
        Route::get('/withdrawals', function() {
            return redirect()->route('seller.balance.index');
        })->name('withdrawals.index');
        
        Route::get('/withdrawals/create', [SellerWithdrawalController::class, 'create'])->name('withdrawals.create');
        Route::post('/withdrawals', [SellerWithdrawalController::class, 'store'])->name('withdrawals.store');
        Route::delete('/withdrawals/{withdrawal}', [SellerWithdrawalController::class, 'destroy'])->name('withdrawals.destroy');

        // Products
        Route::get('/products', [SellerProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [SellerProductController::class, 'create'])->name('products.create');
        Route::post('/products', [SellerProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [SellerProductController::class, 'edit'])->name('products.edit');
        Route::patch('/products/{product}', [SellerProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [SellerProductController::class, 'destroy'])->name('products.destroy');

        // Product Images
        Route::post('/products/{product}/images', [SellerProductImageController::class, 'store'])->name('product-images.store');
        Route::delete('/products/{product}/images/{image}', [SellerProductImageController::class, 'destroy'])->name('product-images.destroy');

        // Categories
        Route::get('/categories', [SellerCategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [SellerCategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [SellerCategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [SellerCategoryController::class, 'edit'])->name('categories.edit');
        Route::patch('/categories/{category}', [SellerCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [SellerCategoryController::class, 'destroy'])->name('categories.destroy');
    });
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    // Stores
    Route::get('/stores', [AdminStoreController::class, 'index'])->name('stores.index');
    Route::get('/stores/{store}', [AdminStoreController::class, 'show'])->name('stores.show')->withTrashed();
    Route::patch('/stores/{store}/verify', [AdminStoreController::class, 'verify'])->name('stores.verify');
    Route::delete('/stores/{store}/reject', [AdminStoreController::class, 'reject'])->name('stores.reject');
    Route::delete('/stores/{store}', [AdminStoreController::class, 'destroy'])->name('stores.destroy');
    Route::patch('/stores/{store}/restore', [AdminStoreController::class, 'restore'])->name('stores.restore')->withTrashed();

    // Orders (New)
    Route::get('/orders', [App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/verify-payment', [App\Http\Controllers\Admin\AdminOrderController::class, 'verifyPayment'])->name('orders.verify-payment');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Withdrawals
    Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/{withdrawal}', [AdminWithdrawalController::class, 'show'])->name('withdrawals.show');
    Route::patch('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::patch('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');
});

require __DIR__.'/auth.php';
