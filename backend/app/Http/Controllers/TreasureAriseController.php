<?php

namespace App\Http\Controllers;

use App\Exports\BusinessExport;
use App\Http\Controllers\Resources\Business as BusinessResource;
use App\Imports\BusinessImport;
use App\Jobs\SortBusiness;
use App\Jobs\StockReady;
use App\Models\Business\Business;
use App\Models\Business\Category;
use Cache;
use DB;
use Form;
use Illuminate\Http\Request;
use Lang;
use Theme;
use Validator;
use View;

class TreasureAriseController extends Controller
{
    public function index()
    {
        $data = [
            'categories' => Category::all(),
            'page_title' => 'Treasures Arise Lists',
            'active'     => 'treasure_arise',
        ];

        return view('treasure_arise/list', $data);
    }

    public function datatable(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        $sorting = 'updated_at';
        $order = $request->order;

        if ($request->has('sort')) {
            $sorting = $request->sort;
        }

        return BusinessResource::collection(Business::when(! empty($search), function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
        })->when(! empty($status), function ($query) use ($status) {
            return $query->where('treasure_arise_status', $status);
        })->orderBy($sorting, $order)
                                                    ->where('is_treasure_arise', true)
                                                    ->paginate($request->limit));
    }

    public function edit($id)
    {
        $business = Business::where('id', $id)->first();

        $data = [
            'entity'     => $business,
            'page_title' => $business->name,
            'active'     => 'treasure_arise',
        ];

        return view('treasure_arise/edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->flash();

        $input = $request->all();
        \DB::beginTransaction();
        try {
            $business = Business::find($id);
            if (isset($input['is_publish'])) {
                $business->is_publish = $input['is_publish'];
            }
            $business->updated_at = date('Y-m-d H:i:s');

            if ($request->status == Business::STATUS_APPROVED) {
                $business->treasure_arise_status = Business::STATUS_APPROVED;
            }

            if ($request->status == Business::STATUS_REJECTED) {
                $business->treasure_arise_status = Business::STATUS_REJECTED;
            }

            $business->save();

            // send email

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage());
        }

        \Cache::forget('businesss');

        return redirect()->route('treasure_arise.list')->with('status', 'Success edit business');
    }

    public function deleteBusiness($id)
    {
        Business::where('id', $id)->delete();
        BusinessSku::where('business_id', $id)->delete();
        Cache::tags('businesss'.session('store')->id.app_key())->flush();

        return redirect()->route('business.list')->with('status', 'Success delete business');
    }

    public function bulkDelete(Request $request)
    {
        if (is_array($request->input('ids'))) {
            Business::whereIn('id', $request->input('ids'))->delete();
            BusinessSku::whereIn('business_id', $request->input('ids'))->delete();
        } else {
            Business::whereIn('id', json_decode($request->input('ids'), true))->delete();
            BusinessSku::whereIn('business_id', json_decode($request->input('ids'), true))->delete();
        }

        Cache::tags('businesss'.session('store')->id.app_key())->flush();
        if ($request->input('back_url')) {
            return redirect($request->input('back_url'));
        }
    }

    public function publish(Request $request)
    {
        if (is_array($request->input('ids'))) {
            BusinessRepository::bulkPublish($request->input('ids'));
        } else {
            BusinessRepository::bulkPublish(json_decode($request->input('ids'), true));
        }
        Cache::tags('businesss'.session('store')->id.app_key())->flush();
        if ($request->input('back_url')) {
            return redirect($request->input('back_url'));
        }
    }

    public function unpublish(Request $request)
    {

        if (is_array($request->input('ids'))) {
            BusinessRepository::bulkUnpublish($request->input('ids'));
        } else {
            BusinessRepository::bulkUnpublish(json_decode($request->input('ids'), true));
        }
        Cache::tags('businesss'.session('store')->id.app_key())->flush();
        if ($request->input('back_url')) {
            return redirect($request->input('back_url'));
        }
    }
}
