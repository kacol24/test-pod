<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Resources\OptionSet as OptionSetResource;
use App\Models\Product\Option;
use App\Models\Product\OptionSet;
use Cache;
use DB;
use Form;
use Illuminate\Http\Request;
use Theme;
use Validator;
use View;

class OptionSetController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'OptionSets',
            'active'     => 'product',
        ];

        return view('product/optionset/list', $data);
    }

    public function datatable(Request $request)
    {
        $search = $request->search;
        $sorting = 'updated_at';
        $order = $request->order;

        if ($request->has('sort')) {
            $sorting = $request->sort;
        }

        return OptionSetResource::collection(OptionSet::when(! empty($search),
            function ($query) use ($search) {
                return $query->where('title', 'like', "%{$search}%");
            })->orderBy($sorting, $order)
                                                      ->paginate($request->limit));
    }

    public function create()
    {
        $data = [
            'page_title' => 'Add OptionSet',
            'options'    => Option::get(),
            'active'     => 'product',
        ];

        return view('product/optionset/add', $data);
    }

    public function store(Request $request)
    {
        $request->flash();

        $validator = Validator::make($request->all(), [
            'title'    => 'required',
            'selected' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('optionset.add')->withErrors($validator)->withInput();
        }

        if ($request->selected) {
            $optionset = OptionSet::create([
                'title' => $request->title,
                'value' => json_encode($request->selected),
            ]);
        }

        return redirect()->route('optionset.list')->with('status', 'Success add optionset');
    }

    public function edit($id)
    {
        $data = [
            'page_title' => 'Edit OptionSet',
            'entity'     => OptionSet::where('id', $id)->first(),
            'options'    => Option::get(),
            'active'     => 'product',
        ];

        return view('product/optionset/edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('optionset.edit', ['id' => $id])->withErrors($validator)->withInput();
        }

        $input = $request->all();
        $optionset = OptionSet::where('id', $id)->first();
        $optionset->title = $input['title'];
        $optionset->value = json_encode($input['selected']);
        $optionset->save();

        return redirect()->route('optionset.list')->with('status', 'Success edit optionset');
    }

    public function delete(Request $request)
    {
        OptionSet::whereIn('id', $request->input('ids'))->delete();
    }
}
