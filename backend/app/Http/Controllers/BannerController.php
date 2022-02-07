<?php

namespace App\Http\Controllers;

use Validator, Image, Cache, DB;
use Theme, View, Form, Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Banner;
use App\Http\Controllers\Resources\Banner as BannerResource;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller {

  public function index() {
    $data = array(
      'page_title' => 'Banner Lists',
      'active' => 'Banner'
    );

    return view('banner/list', $data);
  }

  public function datatable(Request $request) {
    $search = $request->search;
    $sorting = 'updated_at';
    $order = $request->order;

    if($request->has('sort')) {
      $sorting = $request->sort;
    }

    return BannerResource::collection(Banner::when(! empty($search), function ($query) use ($search) {
      return $query->where('title', 'like', "%{$search}%");
    })->orderBy($sorting, $order)->paginate($request->limit));
  }

  public function create() {
    $data = array(
      'page_title' => 'Create Banner',
      'active' => 'banner',
      'types' => config('image.banner')
    );

    return view('banner/add', $data);
  }

  public function store(Request $request) {
    $request->flash();

    $validation = [
      'title' => 'required',
      'start_date' => 'required',
      'order_weight' => 'integer',
      'type' => 'required',
      'desktop_image' => 'required|image|mimes:png,jpeg,jpg,gif',
      'mobile_image' => 'required|image|mimes:png,jpeg,jpg,gif'
    ];

    $validator = Validator::make($request->all(), $validation);

    if ($validator->fails()) {
      return redirect()->route('banner.create')->withErrors($validator)->withInput();
    }

    $input = $request->all();
    $input['desktop_image'] = '';
    $input['mobile_image'] = '';

    $image_size = config('image.banner.'.$input['type']);
    if(!$image_size)
      $image_size = [];

    if ($request->hasFile('desktop_image')) {
      $input['desktop_image'] = $this->generate_banner('desktop',$image_size['desktop'],$request->file('desktop_image'));
    }

    if ($request->hasFile('mobile_image')) {
      $input['mobile_image'] = $this->generate_banner('mobile',$image_size['mobile'],$request->file('mobile_image'));
    }

    $banner = Banner::create($input);

    return redirect()->route('banner.index')->with('status', 'Success add banner');
  }

  public function edit($id) {
    $data = array(
      'page_title' => 'Edit Banner',
      'active' => 'banner',
      'types' => config('image.banner'),
      'entity' => Banner::find($id)
    );

    return view('banner/edit', $data);
  }

  public function update(Request $request, $id) {
    $request->flash();

    $validation = [
      'title' => 'required',
      'desktop_image' => 'image|mimes:png,jpeg,jpg,gif',
      'mobile_image' => 'image|mimes:png,jpeg,jpg,gif',
      'order_weight' => 'integer',
      'start_date' => 'required',
      'type' => 'required'
    ];

    $validator = Validator::make($request->all(), $validation);

    $input = $request->all();

    $image_size = config('image.banner.'.$input['type']);
    if(!$image_size)
      $image_size = [];

    if($request->hasFile('desktop_image')) {
      $input['desktop_image'] = $this->generate_banner('desktop',$image_size['desktop'],$request->file('desktop_image'));
    }

    if ($request->hasFile('mobile_image')) {
      $input['mobile_image'] = $this->generate_banner('mobile',$image_size['mobile'],$request->file('mobile_image'));
    }

    $banner = Banner::find($id);
    $banner->title = $input['title'];
    $banner->url = $input['url'];
    $banner->is_active = $input['is_active'];
    $banner->start_date = $input['start_date'];
    $banner->end_date = $input['end_date'] ?? null;
    $banner->type = $input['type'];
    if(isset($input['desktop_image'])) {
      $banner->desktop_image = $input['desktop_image'];
    }
    if(isset($input['mobile_image'])) {
      $banner->mobile_image = $input['mobile_image'];
    }
    $banner->order_weight = intval($input['order_weight']);
    $banner->save();

    return redirect()->route('banner.index')->with('status', 'Success edit banner');
  }

  public function delete(Request $request) {
    if(is_array($request->input('ids')))
      Banner::whereIn('id',$request->input('ids'))->delete();
    else
      Banner::whereIn('id',json_decode($request->input('ids'), true))->delete();

    if($request->input('back_url'))
      return redirect($request->input('back_url'));
  }

  function generate_banner($key,$size,$file)
  {
    $extension = $file->getClientOriginalExtension();
    $filename =  sha1(Str::random(32)).".".$extension;
    $images = array();

    if($size['w']==0 && $size['h']==0) {
      $filetarget = $file->storeAs('/public/'.$key, $filename);
    }else {
      $path = storage_path('app/public/').$key;
      if(!file_exists($path)){
        mkdir($path, 0755, true);
      }
      $filetarget = $path.'/'.$filename;
      $img = Image::make($file)->fit($size['w'],$size['h'])->save($filetarget);
      if(env('FILESYSTEM_DRIVER') == 's3') {
        Storage::putFileAs('/public/'.$key, $filetarget, $filename);
        unlink($filetarget);
      }
    }

    return $filename;
  }

  public function status(Request $request, $id) {
    if($request->input('status') == 'disable') {
      Banner::where('id', $id)->update(array('is_active' => 0));
    }else {
      Banner::where('id', $id)->update(array('is_active' => 1));
    }
  }
}
