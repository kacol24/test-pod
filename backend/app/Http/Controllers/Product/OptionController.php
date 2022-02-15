<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Resources\Option as OptionResource;
use App\Models\Product\Option;
use App\Models\Product\OptionDetail;
use Cache;
use Core\Models\Product\OptionContent;
use DB;
use Form;
use Illuminate\Http\Request;
use Storage;
use Theme;
use Validator;
use View;

class OptionController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Options',
            'active'     => 'product',
        ];

        return view('product/option/list', $data);
    }

    public function datatable(Request $request)
    {
        $search = $request->search;
        $sorting = 'updated_at';
        $order = $request->order;

        if ($request->has('sort')) {
            $sorting = $request->sort;
        }

        return OptionResource::collection(Option::when(! empty($search),
            function ($query) use ($search) {
                return $query->where('title', 'like',
                    "%{$search}%");
            })
                                                ->orderBy($sorting, $order)
                                                ->paginate($request->limit));
    }

    public function create()
    {
        $data = [
            'page_title' => 'Add Option',
            'active'     => 'product',
        ];

        return view('product/option/add', $data);
    }

    public function store(Request $request)
    {
        $request->flash();

        $validation = [
            'title_en' => 'required',
        ];

        foreach ($request->valueen as $idx => $value) {
            if ($request->hasFile('images'.$idx)) {
                $validation['images'.$idx] = 'image|mimes:png,jpeg,jpg,gif';
            }
        }

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            return redirect()->route('option.add')->withErrors($validator)->withInput();
        }

        $input = $request->all();
        $images = [];
        foreach ($input['valueen'] as $idx => $value) {
            if ($request->hasFile('images'.$idx)) {
                $path = Storage::putFile('images/options', $request->file('images'.$idx), 'public');
                $images[$idx] = str_replace("images/options/", "", $path);
            }
        }
        DB::beginTransaction();
        try {
            $option = Option::create([
                'order_weight' => 0,
                'title'        => $input['title_en'],
            ]);
            foreach ($input['valueen'] as $idx => $value) {
                OptionDetail::create([
                    'option_id' => $option->id,
                    'title'     => $input['valueen'][$idx],
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }

        return redirect()->route('option.list')->with('status', 'Success add option');
    }

    public function edit($id)
    {
        $data = [
            'page_title' => 'Edit Option',
            'entity'     => Option::where('id', $id)->first(),
            'active'     => 'product',
        ];

        return view('product/option/edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->flash();

        $validation = [
            'title_en' => 'required',
        ];

        foreach ($request->valueen as $idx => $value) {
            if ($request->hasFile('images'.$idx)) {
                $validation['images'.$idx] = 'image|mimes:png,jpeg,jpg,gif';
            }
        }

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            return redirect()->route('option.edit', ['id' => $id])->withErrors($validator)->withInput();
        }

        $input = $request->all();
        $images = [];
        foreach ($input['valueen'] as $idx => $value) {
            if ($request->hasFile('images'.$idx)) {
                $path = Storage::putFile('images/options', $request->file('images'.$idx), 'public');
                $images[$idx] = str_replace("images/options/", "", $path);
            }
        }
        DB::beginTransaction();
        try {
            $option = Option::where('id', $id)->first();
            $option->title = $input['title_en'];

            OptionDetail::where('option_id', $id)->delete();

            $details = $input['valueen'];

            foreach ($details as $idx => $value) {
                $option_detail = OptionDetail::where('option_id', $option->id)->where('title', slugify($value))
                                             ->withTrashed()->first();
                if ($option_detail) {
                    if ($option_detail->trashed()) {
                        $option_detail->restore();
                    }
                } else {
                    $option_detail = OptionDetail::create([
                        'option_id' => $option->id,
                        'title'     => slugify($value),
                    ]);
                }

                if (isset($images[$idx])) {
                    $option_detail->image = $images[$idx];
                    $option_detail->save();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }

        return redirect()->route('option.list')->with('status', 'Success edit option');
    }

    public function delete(Request $request)
    {
        Option::whereIn('id', $request->input('ids'))->delete();
    }
}
