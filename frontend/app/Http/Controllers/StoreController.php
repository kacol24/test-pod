<?php

namespace App\Http\Controllers;

use App\Models\ConnectTokopedia;
use App\Models\Store;
use App\Models\StorePlatform;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $connected = StorePlatform::where('store_id', session(Store::SESSION_KEY)->id)->get();
        $pendingTokopedia = ConnectTokopedia::where('store_id', session(Store::SESSION_KEY)->id)->first();

        $data = [
            'connected'        => $connected,
            'pendingTokopedia' => $pendingTokopedia,
        ];

        return view('stores.index', $data);
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();

        ConnectTokopedia::create([
            'store_id'   => session(Store::SESSION_KEY)->id,
            'store_name' => $request->storename,
        ]);

        \DB::commit();

        return back();
    }

    public function cancelTokopedia($id)
    {
        ConnectTokopedia::find($id)->delete();

        return back();
    }

    public function destroy($id)
    {
        \DB::beginTransaction();

        $storePlatform = StorePlatform::find($id);
        ConnectTokopedia::where('store_id', $storePlatform->store_id)->delete();
        $storePlatform->delete();

        \DB::commit();

        return back();
    }
}
