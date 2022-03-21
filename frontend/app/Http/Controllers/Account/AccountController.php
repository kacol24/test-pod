<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

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

    public function update(Request $request)
    {
        auth()->user()->update([
            'name'        => $request->name,
            'phone'       => $request->phone,
            'description' => $request->description,
            'what_to_do'  => $request->what_to_do,
        ]);

        return back()->withStatus('Profile successfully updated!');
    }
}
