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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
    Route::get('/stores/{store}', [AdminStoreController::class, 'show'])->name('stores.show');
    Route::patch('/stores/{store}/verify', [AdminStoreController::class, 'verify'])->name('stores.verify');
    Route::delete('/stores/{store}/reject', [AdminStoreController::class, 'reject'])->name('stores.reject');
    Route::delete('/stores/{store}', [AdminStoreController::class, 'destroy'])->name('stores.destroy');
    Route::patch('/stores/{store}/restore', [AdminStoreController::class, 'restore'])->name('stores.restore');

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
