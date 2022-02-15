<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Resources\ProductResource;
use App\Models\Product\Option;
use App\Models\Product\OptionSet;
use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductOption;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => 'Product Lists',
            'active'     => 'product',
        ];

        return view('product.list', $data);
    }

    public function upload(Request $request)
    {
        $data = [];
        foreach ($request->file('file') as $file) {
            $validator = \Validator::make(['file' => $file], [
                'file' => 'image|mimes:png,jpeg,jpg,gif',
            ]);

            if ($validator->fails()) {
                return ['status' => 'error', 'message' => 'Invalid image format'];
            }

            $sizes = config('image.product');

            $data[] = generate_image($sizes, $file);
        }

        return ['status' => 'success', 'data' => $data];
    }

    public function getOption(Request $request)
    {
        $return = '';
        $option_set = \App\Models\Product\OptionSet::find($request->set_id);
        $options = \App\Models\Product\Option::whereIn('id', json_decode($option_set->value, true))->get();
        if ($options) {
            foreach ($options as $option) {
                $return .= '
          <div class="form-group">
          <label class="text-uppercase">'.$option->title.'</label>
          <input type="checkbox" name="options[]" style="display:none;" id="'.generate_key($option->title).'" title="'.$option->title.'" title-id="'.$option->title.'" title-en="'.$option->title.'"/>
          <ul class="list-unstyled d-flex flex-wrap">';
                foreach ($option->details as $detail) {
                    $return .= '<li><label style="width:100px;margin-left:10px;"><input class="option-detail" type="checkbox" name="'.$detail->title.'" parent="'.generate_key($option->title).'" value="'.$detail->title.'" title="'.$detail->title.'" title-id="'.$detail->title.'" title-en="'.$detail->title.'" key="'.generate_key($option->title.$detail->title).'" image="'.$detail->image.'">&nbsp;&nbsp;'.$detail->title.'</label></li>';
                }

                $return .= '</ul></div>';
            }
        }

        return ['status' => 'success', 'value' => $return];
    }

    public function create()
    {
        $options = Option::get();

        $data = [
            'page_title'  => 'Add Product',
            'active'      => 'product',
            'options'     => $options,
            'option_sets' => OptionSet::get(),
        ];

        return view('product.add', $data);
    }

    public function datatable(Request $request)
    {
        $input = $request->all();

        $offset = $input['offset'];
        $limit = $input['limit'];
        $search = $input['search'];
        $sorting = 'order_weight';
        $status = $input['status'];
        $order = $input['order'];

        if (isset($input['sort'])) {
            $sorting = $input['sort'];
        }

        return ProductResource::collection(
            Product::when(! empty($search), function ($query) use ($search) {
                return $query->where('title', 'like', "%{$search}%");
            })->when(! empty($status), function ($query) use ($status) {
                return $query->where('status', $status);
            })->orderBy($sorting, $order)->paginate($limit)
        );
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        $product = Product::create([
            'is_publish'             => $request->is_publish,
            'order_weight'           => 0,
            'title'                  => $request->title,
            'production_cost'        => $request->production_cost,
            'fulfillment_cost'       => $request->fulfillment_cost,
            'prism_id'               => $request->prism_id,
            'production_time'        => $request->production_time,
            'fulfillment_time'       => $request->fulfillment_time,
            'description'            => $request->description,
            'size_chart'             => $request->size_chart,
            'shape'                  => $request->shape,
            'orientation'            => $request->orientation,
            'unit'                   => $request->unit,
            'enable_resize'          => $request->enable_resize,
            'bleed'                  => $request->bleed,
            'safety_line'            => $request->safety_line,
            'template_width'         => $request->template_width,
            'template_height'        => $request->template_height,
            'ratio'                  => $request->ratio,
            'template_file'          => $request->template_file,
            'template_design_name'   => $request->template_design_name,
            'template_page_name'     => $request->template_page_name,
            'preview_file'           => $request->preview_file,
            'preview_name'           => $request->preview_name,
            'preview_thumbnail_name' => $request->preview_thumbnail_name,
            'preview_file_config'    => $request->preview_file_config,
        ]);

        $product->categories()->sync([
            $request->category_id,
        ]);

        #Product Images
        if (isset($request->images) && is_array($request->images)) {
            foreach ($request->images as $idx => $image) {
                ProductImage::create(['product_id' => $product->id, 'image' => $image, 'order_weight' => $idx]);
            }
        }
        \DB::commit();

        return redirect()->route('product.list');
    }

    public function status(Request $request, $id)
    {
        if ($request->input('status') == 'disable') {
            Product::where('id', $id)->update(['is_publish' => 0]);
        } else {
            Product::where('id', $id)->update(['is_publish' => 1]);
        }
    }

    public function edit($id)
    {
        $product = Product::where('id', $id)->first();
        $selected_category = [];
        foreach ($product->categories as $category) {
            $selected_category[] = $category['category_id'];
        }

        $options = Option::get();
        foreach ($options as $option) {
            $option->title = $option->title;
            foreach ($option->details as $detail) {
                $detail->title = $detail->title;
            }
        }

        $data = [
            'entity'            => $product,
            'product_options'   => $this->getProductOptions($id),
            'selected_category' => $selected_category,
            'option_sets'       => OptionSet::get(),
            'page_title'        => 'Edit Product',
            'active'            => 'product',
            'options'           => $options,
        ];

        return view('product/edit', $data);
    }

    private function getProductOptions($id)
    {
        $options = ProductOption::where('product_id', $id)->orderBy('id', 'asc')->get();
        foreach ($options as $option) {
            foreach ($option->details as $detail) {
                foreach ($detail->contents as $key => $content_detail) {
                    $detail[$content_detail['keyword'].'_'.$content_detail['language']] = $content_detail['value'];
                }
                $detail['title'] = $detail['title_'.session('language')];
                if (json_decode(session('store')->option)->use_option_image && $detail->image) {
                    $detail->image_url = $detail->image_url;
                }
            }
        }

        return $options->toArray();
    }
}
