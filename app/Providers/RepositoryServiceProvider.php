<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\ProductRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentProductRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\CartRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentCartRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\TransactionRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentTransactionRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\StoreRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentStoreRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentUserRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\WishlistRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentWishlistRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\WithdrawalRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentWithdrawalRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\CategoryRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentCategoryRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
