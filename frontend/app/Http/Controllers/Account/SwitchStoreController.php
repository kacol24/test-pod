<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class SwitchStoreController extends Controller
{
    public function switch(Request $request)
    {
        $switchTo = Store::where('storename', $request->storename)->first();

        if (in_array($switchTo->id, auth()->user()->stores->pluck('id')->toArray())) {
            session([Store::SESSION_KEY => $switchTo]);
        }

        return back();
    }
}
