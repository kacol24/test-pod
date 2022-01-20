<?php

namespace App\Composers;

use App\Models\Store;
use Illuminate\View\View;

class StoreBalanceComposer
{
    protected $store;

    public function __construct()
    {
        $storeId = session(Store::SESSION_KEY)->id;

        $this->store = Store::find($storeId);
    }

    public function compose(View $view)
    {
        $view->with('storeBalanceComposer', $this->store->balance);
    }
}
