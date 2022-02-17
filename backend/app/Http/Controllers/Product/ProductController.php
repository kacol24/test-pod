<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Resources\ProductResource;
use App\Models\Product\Option;
use App\Models\Product\OptionSet;
use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductOption;
use App\Models\Product\ProductOptionDetail;
use App\Models\Product\ProductSku;
use App\Models\Product\Capacity;
use App\Models\Product\Template;
use App\Models\Product\Preview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            'capacities' => Capacity::all()
        ];

        return view('product.add', $data);
    }

    public function datatable(Request $request)
    {
        $input = $request->all();

        $offset = $input['offset'];
        $limit = $input['limit'];
        $search = $input['search'];
        $sorting = 'updated_at';
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
        $input = $request->all();

        \DB::beginTransaction();
        $product = Product::create([
            'is_publish'             => $request->is_publish,
            'order_weight'           => 0,
            'title'                  => $request->title,
            'production_cost'        => $request->production_cost,
            'fulfillment_cost'       => $request->fulfillment_cost,
            'selling_price'          => $request->selling_price,
            'prism_id'               => $request->prism_id,
            'production_time'        => $request->production_time,
            'fulfillment_time'       => $request->fulfillment_time,
            'threshold'              => $request->threshold,
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
            'capacity_id'            => $request->capacity_id,
        ]);

        $product->categories()->sync([
            $request->category_id,
        ]);

        foreach($request->template_design_name as $i => $design_name) {
            $filename = "";
            if ($request->hasFile('template_file') && isset($request->file('template_file')[$i])) {
                $file = $request->file('template_file')[$i];
                $extension = $file->getClientOriginalExtension();
                $filename = sha1(Str::random(32)).".".$extension;
                $path = storage_path('app/templates');
                if (! file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $file->storeAs('/templates', $filename);
                $canvas = $this->uploadCanvas(Storage::path('templates/'.$filename), 'designs');
                $template = Template::create(array(
                    'product_id' => $product->id,
                    'file' => $filename,
                    'design_name' => $request->template_design_name[$i],
                    'page_name' => $request->template_page_name[$i],
                    'customer_canvas' => $canvas,
                ));
            }
        }

        foreach($request->preview_name as $i => $preview) {
            $filename = "";
            if ($request->hasFile('preview_file') && isset($request->file('preview_file')[$i])) {
                $file = $request->file('preview_file')[$i];
                $extension = $file->getClientOriginalExtension();
                $filename = sha1(Str::random(32)).".".$extension;
                $path = storage_path('app/previews');
                if (! file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $file->storeAs('/previews', $filename);
                $canvas = $this->uploadCanvas(Storage::path('previews/'.$filename), 'preview_mockups');
                Preview::create(array(
                    'product_id' => $product->id,
                    'file' => $filename,
                    'preview_name' => $request->preview_name[$i],
                    'thumbnail_name' => $request->preview_thumbnail_name[$i],
                    'file_config' => $request->preview_file_config[$i],
                    'customer_canvas' => $canvas,
                ));
            }
        }

        #Product Options
        if (isset($input['product_options']) && is_array(json_decode($input['product_options']))) {
            foreach (json_decode($input['product_options']) as $option) {
                $product_option = ProductOption::create([
                    'product_id' => $product->id,
                    'title'      => $option->title_en,
                ]);

                foreach ($option->details as $opt_detail) {
                    $product_option_detail = ProductOptionDetail::create([
                        'option_id' => $product_option->id,
                        'key'       => $opt_detail->key,
                        'title'     => $opt_detail->title_en,
                    ]);

                    if (isset($opt_detail->image)) {
                        $product_option_detail->image = $opt_detail->image;
                        $product_option_detail->save();
                    }
                }
            }
        }

        if (! $input['default_weight']) {
            $input['default_weight'] = 0;
        }
        if (! $input['default_production_cost']) {
            $input['default_production_cost'] = 0;
        }
        if (! $input['default_fulfillment_cost']) {
            $input['default_fulfillment_cost'] = 0;
        }
        if (! $input['default_selling_price']) {
            $input['default_selling_price'] = 0;
        }
        if (! $input['default_stock']) {
            $input['default_stock'] = 0;
        }
        if (! $input['default_width']) {
            $input['default_width'] = 0;
        }
        if (! $input['default_length']) {
            $input['default_length'] = 0;
        }
        if (! $input['default_height']) {
            $input['default_height'] = 0;
        }
        #Default Product Option
        $sku = ProductSku::create([
            'product_id'         => $product->id,
            'option_detail_key1' => null,
            'option_detail_key2' => null,
            'sku_code'           => $input['default_sku'],
            'production_cost'    => $input['default_production_cost'],
            'fulfillment_cost'   => $input['default_fulfillment_cost'],
            'selling_price'      => $input['default_selling_price'],
            'stock'              => $input['default_stock'],
            'weight'             => ($input['default_weight']) ? intval($input['default_weight']) : 0,
            'width'              => ($input['default_width']) ? intval($input['default_width']) : 0,
            'length'             => ($input['default_length']) ? intval($input['default_length']) : 0,
            'height'             => ($input['default_height']) ? intval($input['default_height']) : 0,
        ]);

        #Product Images
        if (isset($request->images) && is_array($request->images)) {
            foreach ($request->images as $idx => $image) {
                ProductImage::create(['product_id' => $product->id, 'image' => $image, 'order_weight' => $idx]);
            }
        }

        if (isset($input['product_skus']) && is_array(json_decode($input['product_skus']))) {
            foreach (json_decode($input['product_skus']) as $idx => $sku) {
                if (! isset($sku->key2)) {
                    $sku->key2 = null;
                }

                $sku = ProductSku::create([
                    'product_id'         => $product->id,
                    'option_detail_key1' => $sku->key1,
                    'option_detail_key2' => $sku->key2,
                    'sku_code'           => $input['sku'.$idx],
                    'stock'              => $input['stock'.$idx],
                    'production_cost'    => $input['production_cost'.$idx],
                    'fulfillment_cost'   => $input['default_fulfillment_cost'],
                    'selling_price'      => $input['selling_price'.$idx],
                    'weight'             => ($input['weight'.$idx]) ? intval($input['weight'.$idx]) : 0,
                    'width'              => ($input['width'.$idx]) ? intval($input['width'.$idx]) : 0,
                    'length'             => ($input['length'.$idx]) ? intval($input['length'.$idx]) : 0,
                    'height'             => ($input['height'.$idx]) ? intval($input['height'.$idx]) : 0,
                    'image_no'           => (isset($input['image_no'.$idx])) ? intval($input['image_no'.$idx]) : 0,
                ]);
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

        $data = [
            'entity'            => $product,
            'product_options'   => $this->getProductOptions($id),
            'selected_category' => $selected_category,
            'option_sets'       => OptionSet::get(),
            'page_title'        => 'Edit Product',
            'active'            => 'product',
            'options'           => Option::all(),
        ];

        return view('product/edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->flash();

        $validation = [
            'title' => 'required',
        ];

        if ($request->input('is_publish')) {
            $validation['images'] = 'required';
            $validation['category_id'] = 'required';
        }

        $validator = \Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            return redirect()->route('product.edit', ['id' => $id])->withErrors($validator)->withInput();
        }

        $input = $request->all();
        if (! isset($input['is_featured'])) {
            $input['is_featured'] = 0;
        }
        DB::beginTransaction();
        try {
            $product = Product::where('id', $id)->first();
            if (isset($input['is_publish'])) {
                $product->is_publish = $input['is_publish'];
            }
            $product->updated_at = date('Y-m-d H:i:s');
            $product->title = $input['title'];
            $product->description = $input['description'];
            $product->save();

            $product->categories()->sync([
                $request->category_id,
            ]);

            #Product Images
            ProductImage::where('product_id', $product->id)->delete();
            if (isset($input['images']) && is_array($input['images'])) {
                foreach ($input['images'] as $idx => $image) {
                    ProductImage::create(['product_id' => $product->id, 'image' => $image, 'order_weight' => $idx]);
                }
            }
            #Product Options
            foreach ($product->options as $option) {
                foreach ($option->details as $option_detail) {
                    $option_detail->delete();
                }
                $option->delete();
            }
            ProductOption::where('product_id', $product->id)->delete();
            if (isset($input['product_options']) && is_array(json_decode($input['product_options']))) {
                foreach (json_decode($input['product_options']) as $option) {
                    $product_option = ProductOption::create([
                        'product_id' => $product->id,
                        'title'      => $option->title,
                    ]);

                    foreach ($option->details as $opt_detail) {
                        $product_option_detail = ProductOptionDetail::create([
                            'option_id' => $product_option->id,
                            'title'     => $opt_detail->title,
                            'key'       => $opt_detail->key,
                        ]);

                        if (isset($opt_detail->image)) {
                            $product_option_detail->image = $opt_detail->image;
                            $product_option_detail->save();
                        }
                    }
                }
            }
            #Default Product Option
            ProductSku::where('product_id', $product->id)->delete();
            ProductSku::where('product_id', $product->id)->whereNull('option_detail_key1')
                      ->whereNull('option_detail_key2')->restore();
            $sku = ProductSku::where('product_id', $product->id)->whereNull('option_detail_key1')
                             ->whereNull('option_detail_key2')->first();

            $sku->sku_code = $input['default_sku'];
            $sku->weight = $input['default_weight'];
            $sku->production_cost = $input['default_production_cost'];
            $sku->fulfillment_cost = $input['default_fulfillment_cost'];
            $sku->selling_price = $input['default_selling_price'];
            $sku->stock = $input['default_stock'];
            $sku->width = $input['default_width'];
            $sku->length = $input['default_length'];
            $sku->height = $input['default_height'];
            $sku->save();

            if (isset($input['product_skus']) && is_array(json_decode($input['product_skus']))) {
                foreach (json_decode($input['product_skus']) as $idx => $sku) {
                    if (! isset($sku->key2)) {
                        $sku->key2 = null;
                    }

                    $productsku = ProductSku::withTrashed()->where('product_id', $product->id)
                                            ->where('option_detail_key1', $sku->key1)
                                            ->where('option_detail_key2', $sku->key2)->first();
                    if ($productsku) {
                        $productsku->sku_code = $input['sku'.$idx];
                        $productsku->weight = $input['weight'.$idx];
                        $productsku->production_cost = $input['production_cost'.$idx];
                        $productsku->fulfillment_cost = $input['fulfillment_cost'.$idx];
                        $productsku->selling_price = $input['selling_price'.$idx];
                        $productsku->stock = $input['stock'.$idx];
                        $productsku->width = $input['width'.$idx];
                        $productsku->length = $input['length'.$idx];
                        $productsku->height = $input['height'.$idx];
                        $productsku->image_no = (isset($input['image_no'.$idx])) ? $input['image_no'.$idx] : 0;
                        $productsku->save();
                        $productsku->restore();
                    } else {
                        $sku = ProductSku::create([
                            'product_id'         => $product->id,
                            'option_detail_key1' => $sku->key1,
                            'option_detail_key2' => $sku->key2,
                            'sku_code'           => $input['sku'.$idx],
                            'weight'             => $input['weight'.$idx],
                            'production_cost'    => $input['production_cost'.$idx],
                            'fulfillment_cost'   => $input['fulfillment_cost'.$idx],
                            'selling_price'      => $input['selling_price'.$idx],
                            'stock'              => $input['stock'.$idx],
                            'width'              => $input['width'.$idx],
                            'length'             => $input['length'.$idx],
                            'height'             => $input['height'.$idx],
                            'image_no'           => (isset($input['image_no'.$idx])) ? $input['image_no'.$idx] : 0,
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }

        return redirect()->route('product.list')->with('status', 'Success edit product');
    }

    private function getProductOptions($id)
    {
        $options = ProductOption::where('product_id', $id)->orderBy('id', 'asc')->with('details')->get();

        return $options->toArray();
    }

    public function bulkDelete(Request $request) {
        if(is_array($request->input('ids'))) {
          Product::whereIn('id', $request->input('ids'))->delete();
          ProductSku::whereIn('product_id', $request->input('ids'))->delete();
        }
        else {
          Product::whereIn('id', json_decode($request->input('ids'),true))->delete();
          ProductSku::whereIn('product_id', json_decode($request->input('ids'),true))->delete();
        }

        if($request->input('back_url'))
          return redirect($request->input('back_url'));
    }

    private function uploadCanvas($file, $type) {
        $key = 'PrinterousCustomerCanvasDemo123!@#';        
        $url = "https://canvas.printerous.com/production/Canvas/Edge/api/ProductTemplates/".$type."/pod";

        $name = ($type == 'designs') ? 'design' : 'preview_mockups';
        $post = array($name => curl_file_create($file));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "X-CustomersCanvasAPIKey: ".$key,
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result=curl_exec ($ch);
        curl_close ($ch);
        return $result;
    }
}
