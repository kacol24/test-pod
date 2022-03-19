<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Store;

class AccountController extends Controller
{
    public function referral()
    {
        $store = Store::find(session(Store::SESSION_KEY)->id);

        $data = [
            'upline'    => $store,
            'downlines' => $store->downlines,
        ];

        return view('account.myreferrals', $data);
    }
}
