<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Resources\ProductResource;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product\Capacity;
use App\Models\Product\Category;
use App\Models\Product\Color;
use App\Models\Product\Design;
use App\Models\Product\MockupColor;
use App\Models\Product\Option;
use App\Models\Product\OptionSet;
use App\Models\Product\Preview;
use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductOption;
use App\Models\Product\ProductOptionDetail;
use App\Models\Product\ProductSku;
use App\Models\Product\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

            // $data[] = generate_image($sizes, $file);

            $extension = $file->getClientOriginalExtension();
            $filename = sha1(Str::random(32)).".".$extension;

            $path = storage_path('app/public/masterproduct');
            if (! file_exists($path)) {
                mkdir($path, 0755, true);
            }
            $file->storeAs('/public/masterproduct', $filename);
            $data[] = [
                'filename' => $filename, 'images' => [
                    'masterproduct' => image_url('masterproduct', $filename),
                    '175x175'       => image_url('masterproduct', $filename),
                ],
            ];
        }

        return ['status' => 'success', 'data' => $data];
    }

    public function getOption(Request $request)
    {
        $return = '';
        if ($request->set_id) {
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
            'capacities'  => Capacity::all(),
            'categories'  => category_tree(Category::active()->orderBy('order_weight', 'asc')->get()),
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

    public function store(ProductRequest $request)
    {
        $input = $request->all();

        \DB::beginTransaction();
        $product = Product::create([
            'is_publish'       => $request->is_publish,
            'order_weight'     => 0,
            'title'            => $request->title,
            'prism_id'         => $request->prism_id,
            'production_time'  => $request->production_time,
            'fulfillment_time' => $request->fulfillment_time,
            'threshold'        => $request->threshold,
            'description'      => $request->description,
            'size_chart'       => $request->size_chart,
            'capacity_id'      => $request->capacity_id,
        ]);

        $product->categories()->sync($request->category_id);

        if ($request->colors) {
            $product->colors()->createMany($request->colors);
        }

        foreach ($request->templates as $index => $template) {
            $productTemplate = $product->templates()->create([
                'design_name'     => $template['design_name'],
                'price'           => $template['price'],
                'shape'           => $template['shape'],
                'orientation'     => $template['orientation'],
                'unit'            => $template['unit'],
                'enable_resize'   => $template['enable_resize'],
                'bleed'           => $template['bleed'],
                'safety_line'     => $template['safety_line'],
                'template_width'  => $template['width'],
                'template_height' => $template['height'],
                'ratio'           => $template['ratio'],
            ]);

            foreach ($template['design'] as $designIndex => $design) {
                $createDesign = [
                    'page_name'              => $design['page_name'],
                    //'file'                   => $filename,
                    //'customer_canvas'        => $canvas,
                    'mockup_width'           => $design['mockup_width'],
                    'mockup_height'          => $design['mockup_height'],
                    'design_location' => json_encode([
                        'X' => $design['location_x'],
                        'Y' => $design['location_y'],
                    ])
                    //'mockup'                 => $mockupFilename,
                    //'mockup_customer_canvas' => $mockupCanvas,
                ];
                $fileKey = 'templates.'.$index.'.design.'.$designIndex.'.file';
                if ($request->hasFile($fileKey) && $request->file($fileKey)->isValid()) {
                    $file = $request->file($fileKey);
                    $extension = $file->getClientOriginalExtension();
                    $filename = sha1(Str::random(32)).".".$extension;
                    $path = storage_path('app/templates');
                    if (! file_exists($path)) {
                        mkdir($path, 0755, true);
                    }
                    $file->storeAs('/templates', $filename);
                    $canvas = $this->uploadCanvas(Storage::path('templates/'.$filename), 'designs');
                    $createDesign['file'] = $filename;
                    $createDesign['customer_canvas'] = $canvas;
                }

                $mockupFileKey = 'templates.'.$index.'.design.'.$designIndex.'.mockup_file';
                if ($request->hasFile($mockupFileKey) && $request->file($mockupFileKey)->isValid()) {
                    $file = $request->file($mockupFileKey);
                    $extension = $file->getClientOriginalExtension();
                    $mockupFilename = sha1(Str::random(32)).".".$extension;
                    $path = storage_path('app/templates');
                    if (! file_exists($path)) {
                        mkdir($path, 0755, true);
                    }
                    $file->storeAs('/templates', $mockupFilename);
                    $mockupCanvas = $this->uploadCanvas(Storage::path('templates/'.$mockupFilename), 'mockups');
                    $createDesign['mockup'] = $mockupFilename;
                    $createDesign['mockup_customer_canvas'] = $mockupCanvas;
                }
                $productTemplate->designs()->create($createDesign);
            }

            foreach ($template['preview'] as $previewIndex => $preview) {
                $createPreview = [
                    //'file'            => $filename,
                    'preview_name'    => $preview['preview_name'],
                    'thumbnail_name'  => $preview['thumbnail_name'],
                    'file_config'     => $preview['file_config'],
                    //'customer_canvas' => $canvas,
                ];
                $fileKey = 'templates.'.$index.'.preview.'.$previewIndex.'.file';
                if ($request->hasFile($fileKey) && $request->file($fileKey)->isValid()) {
                    $file = $request->file($fileKey);
                    $extension = $file->getClientOriginalExtension();
                    $filename = sha1(Str::random(32)).".".$extension;
                    $path = storage_path('app/previews');
                    if (! file_exists($path)) {
                        mkdir($path, 0755, true);
                    }
                    $file->storeAs('/previews', $filename);
                    $canvas = $this->uploadCanvas(Storage::path('previews/'.$filename), 'mockups');
                    $createPreview['file'] = $filename;
                    $createPreview['customer_canvas'] = $canvas;
                }
                $productTemplate->previews()->create($createPreview);
            }
        }

        foreach ($product->colors as $color) {
            foreach ($product->designs as $design) {
                MockupColor::create([
                    'color_id'        => $color->id,
                    'design_id'       => $design->id,
                    'customer_canvas' => $this->uploadMockupColor($design, $color),
                ]);
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
                    'fulfillment_cost'   => $input['fulfillment_cost'.$idx],
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

        $data = [
            'entity'            => $product,
            'product_options'   => $this->getProductOptions($id),
            'selected_category' => $product->categories->pluck('id')->toArray(),
            'option_sets'       => OptionSet::get(),
            'page_title'        => 'Edit Product',
            'active'            => 'product',
            'options'           => Option::all(),
            'capacities'        => Capacity::all(),
            'categories'        => category_tree(Category::active()->orderBy('order_weight', 'asc')->get()),
        ];

        return view('product/edit', $data);
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        $request->flash();

        $input = $request->all();
        if (! isset($input['is_featured'])) {
            $input['is_featured'] = 0;
        }
        DB::beginTransaction();
        $product = Product::where('id', $id)->first();
        if (isset($input['is_publish'])) {
            $product->is_publish = $input['is_publish'];
        }
        $product->title = $request->title;
        $product->prism_id = $request->prism_id;
        $product->production_time = $request->production_time;
        $product->fulfillment_time = $request->fulfillment_time;
        $product->threshold = $request->threshold;
        $product->description = $request->description;
        $product->size_chart = $request->size_chart;
        $product->capacity_id = $request->capacity_id;
        $product->save();

        $product->colors()->delete();
        foreach ($request->colors ?? [] as $color) {
            if ($color['id']) {
                $theColor = Color::withTrashed()->find($color['id']);
                $theColor->restore();
                $theColor->update($color);
            } else {
                $product->colors()->create($color);
            }
        }

        $templatesData = collect();
        foreach ($request->templates as $index => $template) {
            $rootTemplate = [
                'id'              => $template['id'],
                'design_name'     => $template['design_name'],
                'price'           => $template['price'],
                'shape'           => $template['shape'],
                'orientation'     => $template['orientation'],
                'unit'            => $template['unit'],
                'enable_resize'   => $template['enable_resize'],
                'bleed'           => $template['bleed'],
                'safety_line'     => $template['safety_line'],
                'template_width'  => $template['width'],
                'template_height' => $template['height'],
                'ratio'           => $template['ratio'],
            ];

            foreach ($template['design'] ?? [] as $designIndex => $design) {
                $theDesign = [];
                if ($design['id']) {
                    $theDesign = Design::find($design['id'])->toArray();
                }

                $theDesign['page_name'] = $design['page_name'];
                $theDesign['mockup_width'] = $design['mockup_width'];
                $theDesign['mockup_height'] = $design['mockup_height'];
                $theDesign['design_location'] = json_encode([
                    'X' => $design['location_x'],
                    'Y' => $design['location_y'],
                ]);

                $fileKey = 'templates.'.$index.'.design.'.$designIndex.'.file';
                if ($request->hasFile($fileKey) && $request->file($fileKey)->isValid()) {
                    $file = $request->file($fileKey);
                    $extension = $file->getClientOriginalExtension();
                    $filename = sha1(Str::random(32)).".".$extension;
                    $path = storage_path('app/templates');
                    if (! file_exists($path)) {
                        mkdir($path, 0755, true);
                    }
                    $file->storeAs('/templates', $filename);
                    $canvas = $this->uploadCanvas(Storage::path('templates/'.$filename), 'designs');
                    $theDesign['file'] = $filename;
                    $theDesign['customer_canvas'] = $canvas;
                }

                $mockupFileKey = 'templates.'.$index.'.design.'.$designIndex.'.mockup_file';
                if ($request->hasFile($mockupFileKey) && $request->file($mockupFileKey)->isValid()) {
                    $file = $request->file($mockupFileKey);
                    $extension = $file->getClientOriginalExtension();
                    $filename = sha1(Str::random(32)).".".$extension;
                    $path = storage_path('app/templates');
                    if (! file_exists($path)) {
                        mkdir($path, 0755, true);
                    }
                    $file->storeAs('/templates', $filename);
                    $canvas = $this->uploadCanvas(Storage::path('templates/'.$filename), 'mockups');
                    $theDesign['mockup'] = $filename;
                    $theDesign['mockup_customer_canvas'] = $canvas;
                }
                $rootTemplate['design'][] = $theDesign;
            }

            foreach ($template['preview'] ?? [] as $previewIndex => $preview) {
                $thePreview = [];
                if ($preview['id']) {
                    $thePreview = Preview::find($preview['id'])->toArray();
                }
                $thePreview['preview_name'] = $preview['preview_name'];
                $thePreview['thumbnail_name'] = $preview['thumbnail_name'];
                $thePreview['file_config'] = $preview['file_config'];
                $fileKey = 'templates.'.$index.'.preview.'.$previewIndex.'.file';
                if ($request->hasFile($fileKey) && $request->file($fileKey)->isValid()) {
                    $file = $request->file($fileKey);
                    $extension = $file->getClientOriginalExtension();
                    $filename = sha1(Str::random(32)).".".$extension;
                    $path = storage_path('app/previews');
                    if (! file_exists($path)) {
                        mkdir($path, 0755, true);
                    }
                    $file->storeAs('/previews', $filename);
                    $canvas = $this->uploadCanvas(Storage::path('previews/'.$filename), 'mockups');
                    $thePreview['file'] = $filename;
                    $thePreview['customer_canvas'] = $canvas;
                }
                $rootTemplate['preview'][] = $thePreview;
            }
            $templatesData->push($rootTemplate);
        }

        Design::whereIn('id', $product->designs->pluck('id')->toArray())->delete();
        Preview::whereIn('id', $product->previews->pluck('id')->toArray())->delete();
        Template::whereIn('id', $product->templates->pluck('id')->toArray())->delete();
        foreach ($templatesData as $templateData) {
            $updateTemplate = [
                'design_name'     => $templateData['design_name'],
                'price'           => $templateData['price'],
                'shape'           => $templateData['shape'],
                'orientation'     => $templateData['orientation'],
                'unit'            => $templateData['unit'],
                'enable_resize'   => $templateData['enable_resize'],
                'bleed'           => $templateData['bleed'],
                'safety_line'     => $templateData['safety_line'],
                'template_width'  => $templateData['template_width'],
                'template_height' => $templateData['template_height'],
                'ratio'           => $templateData['ratio'],
            ];
            if (isset($templateData['id'])) {
                $template = Template::withTrashed()->find($templateData['id']);
                $template->restore();
                $template->update($updateTemplate);
            } else {
                $template = $product->templates()->create($updateTemplate);
            }

            foreach ($templateData['design'] as $designData) {
                $updateDesign = [
                    'file'                   => $designData['file'],
                    'mockup'                 => $designData['mockup'],
                    'mockup_customer_canvas' => $designData['mockup_customer_canvas'],
                    'mockup_width'           => $designData['mockup_width'],
                    'mockup_height'          => $designData['mockup_height'],
                    'page_name'              => $designData['page_name'],
                    'customer_canvas'        => $designData['customer_canvas'],
                ];
                if (isset($designData['id'])) {
                    $design = Design::withTrashed()->find($designData['id']);
                    $design->restore();
                    $design->update($updateDesign);
                } else {
                    $template->designs()->create($updateDesign);
                }
            }

            foreach ($templateData['preview'] as $previewData) {
                $updatePreview = [
                    'file'            => $previewData['file'],
                    'preview_name'    => $previewData['preview_name'],
                    'thumbnail_name'  => $previewData['thumbnail_name'],
                    'file_config'     => $previewData['file_config'],
                    'customer_canvas' => $previewData['customer_canvas'],
                ];
                if (isset($previewData['id'])) {
                    $preview = Preview::withTrashed()->find($previewData['id']);
                    $preview->restore();
                    $preview->update($updatePreview);
                } else {
                    $template->previews()->create($updatePreview);
                }
            }
        }

        foreach ($product->colors as $color) {
            foreach ($product->designs as $design) {
                MockupColor::where([
                    'color_id'  => $color->id,
                    'design_id' => $design->id,
                ])->update([
                    'customer_canvas' => $this->uploadMockupColor($design, $color),
                ]);
            }
        }

        $product->categories()->sync($request->category_id);

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
        ProductSku::where('product_id', $product->id)
                  ->whereNull('option_detail_key1')
                  ->whereNull('option_detail_key2')
                  ->restore();
        $sku = ProductSku::where('product_id', $product->id)
                         ->whereNull('option_detail_key1')
                         ->whereNull('option_detail_key2')
                         ->first();

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

                $productsku = ProductSku::withTrashed()
                                        ->where('product_id', $product->id)
                                        ->where('option_detail_key1', $sku->key1)
                                        ->where('option_detail_key2', $sku->key2)
                                        ->first();

                if ($productsku) {
                    $productsku->restore();
                    $productsku->sku_code = $input['sku'.$idx];
                    $productsku->weight = $input['weight'.$idx];
                    $productsku->production_cost = $input['production_cost'.$idx];
                    $productsku->fulfillment_cost = $input['fulfillment_cost'.$idx];
                    $productsku->selling_price = $input['selling_price'.$idx];
                    $productsku->stock = $input['stock'.$idx];
                    $productsku->width = $input['width'.$idx];
                    $productsku->length = $input['length'.$idx];
                    $productsku->height = $input['height'.$idx];
                    $productsku->save();
                } else {
                    ProductSku::create([
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
                    ]);
                }
            }
        }
        DB::commit();

        return redirect()->route('product.list')->with('status', 'Success edit product');
    }

    public function bulkDelete(Request $request)
    {
        if (is_array($request->input('ids'))) {
            Product::whereIn('id', $request->input('ids'))->delete();
            ProductSku::whereIn('product_id', $request->input('ids'))->delete();
        } else {
            Product::whereIn('id', json_decode($request->input('ids'), true))->delete();
            ProductSku::whereIn('product_id', json_decode($request->input('ids'), true))->delete();
        }

        if ($request->input('back_url')) {
            return redirect($request->input('back_url'));
        }
    }

    private function getProductOptions($id)
    {
        $options = ProductOption::where('product_id', $id)->orderBy('id', 'asc')->with('details')->get();

        return $options->toArray();
    }

    private function uploadCanvas($file, $type)
    {
        $key = 'PrinterousCustomerCanvasDemo123!@#';
        $url = "https://canvas.printerous.com/production/Canvas/Edge/api/ProductTemplates/".$type."/pod";

        $name = ($type == 'designs') ? 'design' : 'preview_mockups';
        $post = [$name => curl_file_create($file)];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "X-CustomersCanvasAPIKey: ".$key,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return Str::of($result)->trim('"');
    }

    private function uploadMockupColor(Design $design, Color $color)
    {
        $key = 'PrinterousCustomerCanvasDemo123!@#';
        $url = 'https://canvas.printerous.com/production/DI/api/rendering/preview';

        $post = [
            'template' => curl_file_create(Storage::path('templates/'.$design->mockup)),
            'format'   => 'png',
            'size'     => json_encode([
                'width'  => $design->mockup_width,
                'height' => $design->mockup_height,
            ]),
            'data'     => json_encode([
                'Shape' => [
                    'type'  => 'shape',
                    'color' => $color->color,
                ],
            ]),
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "X-CustomersCanvasAPIKey: ".$key,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return Str::of($result)->trim('"');
    }
}
