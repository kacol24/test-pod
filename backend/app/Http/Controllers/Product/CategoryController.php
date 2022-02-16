<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Resources\Category as CategoryResource;
use App\Models\Product\Category;
use App\Models\Product\CategoryContent;
use App\Models\Product\CategorySlug;
use App\Repositories\Facades\CategoryRepository;
use Cache;
use DB;
use Form;
use Illuminate\Http\Request;
use Theme;
use Validator;
use View;

class CategoryController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Categories',
            'active'     => 'product',
        ];

        return view('product/category/list', $data);
    }

    public function datatable(Request $request)
    {
        $search = $request->search;
        $sorting = 'updated_at';
        $order = $request->order;

        if ($request->has('sort')) {
            $sorting = $request->sort;
        }

        return CategoryResource::collection(Category::when(! empty($search),
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
            'page_title' => 'Add Category',
            'active'     => 'product',
        ];

        return view('product/category/add', $data);
    }

    public function store(Request $request)
    {
        $request->flash();
        $validation = [
            'order_weight' => 'integer',
            'file'         => 'image|mimes:png,jpeg,jpg,gif',
        ];
        $validation['title'] = 'required';

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            return redirect()->route('category.add')->withErrors($validator)->withInput();
        }

        $input = $request->all();
        $input['image'] = '';
        if ($request->hasFile('file')) {
            $image_size = config('image.category');
            $result = generate_image($image_size, $request->file('file'));
            $input['image'] = $result['filename'];
        }

        DB::beginTransaction();
        try {
            if (! $input['order_weight']) {
                $input['order_weight'] = 0;
            }
            $category = Category::create([
                'parent_id'    => 0,
                'order_weight' => $input['order_weight'],
                'is_active'    => (isset($input['is_active'])) ? $input['is_active'] : 1,
                'image'        => $input['image'],
                'is_featured'  => (isset($input['is_featured'])) ? $input['is_featured'] : 0,
                'name'         => $input['title'],
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }

        return redirect()->route('category.list')->with('status', 'Success add category');
    }

    public function edit($id)
    {
        $data = [
            'page_title' => 'Edit Category',
            'entity'     => Category::where('id', $id)->first(),
            'active'     => 'product',
        ];

        return view('product/category/edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validation = [
            'order_weight' => 'integer',
            'file'         => 'image|mimes:png,jpeg,jpg,gif',
        ];
        $validation['title'] = 'required';

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            return redirect()->route('category.edit', ['id' => $id])->withErrors($validator)->withInput();
        }

        $input = $request->all();
        if ($request->hasFile('file')) {
            $image_size = config('image.category');
            if (theme_config('image.category')) {
                $image_size = array_merge($image_size, theme_config('image.category'));
            }
            $result = generate_image($image_size, $request->file('file'));
            $input['image'] = $result['filename'];
        }

        DB::beginTransaction();
        try {
            if (! $input['order_weight']) {
                $input['order_weight'] = 0;
            }
            $category = Category::where('id', $id)->first();
            $category->parent_id = 0;
            $category->order_weight = $input['order_weight'];
            $category->name = $input['title'];
            $category->is_active = (isset($input['is_active'])) ? $input['is_active'] : 1;
            $category->is_featured = (isset($input['is_featured'])) ? $input['is_featured'] : 0;

            if (isset($input['image'])) {
                $category->image = $input['image'];
            }
            $category->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }

        return redirect()->route('category.list')->with('status', 'Success edit category');
    }

    public function delete(Request $request)
    {
        Category::whereIn('id', $request->input('ids'))->delete();
        Cache::tags('categories'.session('store')->id.app_key())->flush();
    }

    public function status(Request $request, $id)
    {
        if ($request->input('status') == 'disable') {
            Category::where('id', $id)->where('store_id', session('store')->id)->update(['is_active' => 0]);
        } else {
            Category::where('id', $id)->where('store_id', session('store')->id)->update(['is_active' => 1]);
        }
        Cache::tags('categories'.session('store')->id.app_key())->flush();
    }
}
