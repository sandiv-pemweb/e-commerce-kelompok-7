<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Wishlist;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }


    public function boot(): void
    {
        View::composer(['components.store-layout', 'layouts.navigation'], function ($view) {
            $cartCount = Auth::check() ? Cart::where('user_id', Auth::id())->sum('quantity') : 0;
            $wishlistCount = Auth::check() ? Wishlist::where('user_id', Auth::id())->count() : 0;
            $view->with('cartCount', $cartCount);
            $view->with('wishlistCount', $wishlistCount);
        });
    }
}
