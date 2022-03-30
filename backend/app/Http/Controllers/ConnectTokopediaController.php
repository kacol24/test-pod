<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConnectTokopediaDatatableResource;
use App\Models\ConnectTokopedia;
use App\Models\StorePlatform;
use Illuminate\Http\Request;

class ConnectTokopediaController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Connect Tokopedia',
            'active'     => 'connect_tokopedia',
        ];

        return view('connect_tokopedia.index', $data);
    }

    public function datatable(Request $request)
    {
        $search = $request->search;
        $sorting = 'platform_id';
        $order = $request->order;

        if ($request->has('sort')) {
            $sorting = $request->sort;
        }

        return ConnectTokopediaDatatableResource::collection(
            ConnectTokopedia::when(! empty($search),
                function ($query) use ($search) {
                    return $query->where('value', 'like', "%{$search}%");
                })
                            ->orderBy($sorting, $order)
                            ->paginate($request->limit)
        );
    }

    public function update($id, Request $request)
    {
        \DB::beginTransaction();

        $tokopedia = ConnectTokopedia::find($id);
        $tokopedia->update([
            'platform_id' => $request->platform_id,
        ]);
        StorePlatform::create([
            'store_id'          => $tokopedia->store_id,
            'platform'          => 'tokopedia',
            'platform_store_id' => $tokopedia->platform_id,
        ]);

        \DB::commit();
    }
}
