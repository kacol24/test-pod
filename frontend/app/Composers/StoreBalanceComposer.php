<?php

namespace App\Composers;

use App\Models\Store;
use Illuminate\View\View;

class StoreBalanceComposer
{
    public function compose(View $view)
    {
        $storeId = session(Store::SESSION_KEY)->id;

        $view->with('storeBalanceComposer', Store::find($storeId)->balance);
    }
}
