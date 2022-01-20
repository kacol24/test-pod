<?php

namespace App\Providers;

use App\Models\Store;
use App\Services\WalletService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        $this->app->bind(WalletService::class, function ($app) {
            $storeId = session(Store::SESSION_KEY)->id;

            return new WalletService($storeId);
        });
    }
}
