<?php

namespace App\Http\Controllers;

use Validator;
use Theme, View, Form, Cache, DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Business\Category;
use App\Http\Controllers\Resources\Category as CategoryResource;

class CategoryController extends Controller {
  public function index() {
    $data = array(
      'page_title' => 'Categories',
      'active' => 'category'
    );
    return view('category/list', $data);
  }

  public function datatable(Request $request) {
    $search = $request->search;
    $sorting = 'updated_at';
    $order = $request->order;

    if($request->has('sort')) {
      $sorting = $request->sort;
    }
    
    return CategoryResource::collection(Category::when(! empty($search), function ($query) use ($search) {
      return $query->where('title', 'like', "%{$search}%");
    })->orderBy($sorting, $order)->paginate($request->limit));
  }

  public function create() {
    $data = array(
      'page_title' => 'Add Category',
      'parents' => category_tree(Category::orderBy('title','asc')->get()),
      'active' => 'category'
    );

    return view('category/add',$data);
  }

  public function store(Request $request)
  {
    $request->flash();
    $validation = array(
      'order_weight' => 'integer',
      'title' => 'required'
    );
    $validator = Validator::make($request->all(), $validation);

    if ($validator->fails()) {
      return redirect()->route('category.add')->withErrors($validator)->withInput();
    }

    $input = $request->all();
    $input['image'] = '';
    if ($request->hasFile('file')) {
      $image_size = config('image.category');
      if(theme_config('image.category')) {
        $image_size = array_merge($image_size, theme_config('image.category'));
      }
      $result = generate_image($image_size,$request->file('file'));
      $input['image'] = $result['filename'];
    }

    DB::beginTransaction();
    try {
      if(!$input['order_weight']) {
        $input['order_weight'] = 0;
      }

      $slug_candidate = slugify($input['title']);
      $possible_conflicts = Category::whereRaw("(`title` = '".$slug_candidate."' or `title` like '%".$slug_candidate."-%')")->get(array('title'))->pluck('title')->toArray();

      $category = Category::create(array(
        'parent_id' => $input['parent'],
        'slug' => slugUniqify($slug_candidate, $possible_conflicts),
        'title' => $input['title'],
        'order_weight' => $input['order_weight'],
        'is_active' => (isset($input['is_active'])) ? $input['is_active'] : 1,
        'image' => $input['image'],
        'is_featured' => (isset($input['is_featured'])) ? $input['is_featured'] : 0,
        'seo' => json_encode(array('meta_title' => $input['meta_title'], 'meta_keyword' => $input['meta_keyword'], 'meta_description' => $input['meta_description']))
      ));

      DB::commit();
    }catch(\Exception $e){
      DB::rollback();
      throw new \Exception($e->getMessage());
    }

    return redirect()->route('category.list')->with('status', 'Success add category');
  }

  public function edit($id) {
    $data = array(
      'page_title' => 'Edit Category',
      'parents' => category_tree(Category::orderBy('title','asc')->get()),
      'entity' => Category::find($id),
      'active' => 'category'
    );

    return view('category/edit',$data);
  }

  public function update(Request $request, $id) {
    $validation = array(
      'order_weight' => 'integer',
      'file' => 'image|mimes:png,jpeg,jpg,gif',
      'title' => 'required'
    );
    
    $validator = Validator::make($request->all(), $validation);

    if ($validator->fails()) {
      return redirect()->route('category.edit',['id' => $id])->withErrors($validator)->withInput();
    }

    $input = $request->all();
    if ($request->hasFile('file')) {
      $image_size = config('image.category');
      if(theme_config('image.category')) {
        $image_size = array_merge($image_size, theme_config('image.category'));
      }
      $result = generate_image($image_size,$request->file('file'));
      $input['image'] = $result['filename'];
    }

    DB::beginTransaction();
    try {
      if(!$input['order_weight']) {
        $input['order_weight'] = 0;
      }
      $slug_candidate = slugify($input['title']);
      $possible_conflicts = Category::whereRaw("(`title` = '".$slug_candidate."' or `title` like '%".$slug_candidate."-%')")->where('id','!=', $id)->get(array('title'))->pluck('title')->toArray();

      $category = Category::find($id);
      $category->slug = slugUniqify($slug_candidate, $possible_conflicts);
      $category->parent_id = $input['parent'];
      $category->order_weight = $input['order_weight'];
      $category->title = $input['title'];
      $category->is_active = (isset($input['is_active'])) ? $input['is_active'] : 1;
      $category->is_featured = (isset($input['is_featured'])) ? $input['is_featured'] : 0;
      $category->seo = json_encode(array('meta_title' => $input['meta_title'], 'meta_keyword' => $input['meta_keyword'], 'meta_description' => $input['meta_description']));
      if(isset($input['image']))
        $category->image = $input['image'];

      $category->save();

      DB::commit();
    }catch(\Exception $e){
      DB::rollback();
      throw new \Exception($e->getMessage());
    }
    return redirect()->route('category.list')->with('status', 'Success edit category');
  }

  public function delete(Request $request) {
    Category::whereIn('id', $request->input('ids'))->delete();
  }

  public function status(Request $request, $id) {
    if($request->input('status') == 'disable') {
      Category::where('id', $id)->update(array('is_active' => 0));
    }else {
      Category::where('id', $id)->update(array('is_active' => 1));
    }
  }
}
