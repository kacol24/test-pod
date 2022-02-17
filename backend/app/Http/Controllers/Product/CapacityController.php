<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Resources\Capacity as CapacityResource;
use App\Models\Product\Capacity;
use Cache;
use DB;
use Form;
use Illuminate\Http\Request;
use Theme;
use Validator;
use View;

class CapacityController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Capacities',
            'active'     => 'product',
        ];

        return view('product/capacity/list', $data);
    }

    public function datatable(Request $request)
    {
        $search = $request->search;
        $sorting = 'updated_at';
        $order = $request->order;

        if ($request->has('sort')) {
            $sorting = $request->sort;
        }

        return CapacityResource::collection(Capacity::when(! empty($search),
            function ($query) use ($search) {
                return $query->where('value', 'like',
                    "%{$search}%");
            })
                                                    ->orderBy($sorting, $order)
                                                    ->paginate($request->limit));
    }

    public function create()
    {
        $data = [
            'page_title' => 'Add Capacity',
            'active'     => 'product',
        ];

        return view('product/capacity/add', $data);
    }

    public function store(Request $request)
    {
        $request->flash();
        $validation = [
            'title' => 'required',
            'capacity' => 'integer',
        ];
        $validation['title'] = 'required';

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            return redirect()->route('capacity.add')->withErrors($validator)->withInput();
        }

        $input = $request->all();

        DB::beginTransaction();
        try {
            $capacity = Capacity::create([
                'title'    => $input['title'],
                'capacity' => $input['capacity']
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }

        return redirect()->route('capacity.list')->with('status', 'Success add capacity');
    }

    public function edit($id)
    {
        $data = [
            'page_title' => 'Edit Capacity',
            'entity'     => Capacity::where('id', $id)->first(),
            'active'     => 'product',
        ];

        return view('product/capacity/edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validation = [
            'title' => 'required',
            'capacity' => 'integer',
        ];

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            return redirect()->route('capacity.edit', ['id' => $id])->withErrors($validator)->withInput();
        }

        $input = $request->all();
        
        DB::beginTransaction();
        try {
            $capacity = Capacity::find($id);
            $capacity->title = $input['title'];
            $capacity->capacity = $input['capacity'];
            $capacity->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }

        return redirect()->route('capacity.list')->with('status', 'Success edit capacity');
    }

    public function delete(Request $request)
    {
        Capacity::whereIn('id', $request->input('ids'))->delete();
        Cache::tags('categories'.session('store')->id.app_key())->flush();
    }

    public function status(Request $request, $id)
    {
        if ($request->input('status') == 'disable') {
            Capacity::where('id', $id)->where('store_id', session('store')->id)->update(['is_active' => 0]);
        } else {
            Capacity::where('id', $id)->where('store_id', session('store')->id)->update(['is_active' => 1]);
        }
        Cache::tags('categories'.session('store')->id.app_key())->flush();
    }
}
