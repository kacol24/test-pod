<?php

namespace App\Providers;

use App\Composers\StoreBalanceComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer([
            'partials.menu-items',
            'account.mywallet'
        ], StoreBalanceComposer::class);
    }
}
