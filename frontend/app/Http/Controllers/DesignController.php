<?php

namespace App\Http\Controllers;

use App\Http\Resources\DesignDatatableResource;
use App\Models\MasterProduct\Category;
use App\Models\MasterProduct\MasterProduct;
use App\Models\MasterProduct\Template;
use App\Models\Product\Product;
use App\Models\Product\ProductDesign;
use App\Models\Product\ProductEditor;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductOption;
use App\Models\Product\ProductOptionDetail;
use App\Models\Product\ProductSku;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Validator;

class DesignController extends Controller
{
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

        return DesignDatatableResource::collection(
            ProductDesign::when(! empty($search), function ($query) use ($search) {
                return $query->where('title', 'like', "%{$search}%");
            })->when(! empty($status), function ($query) use ($status) {
                return $query->where('status', $status);
            })->orderBy($sorting, $order)->paginate($limit)
        );
    }

    public function index()
    {
        return view('product.index');
    }

    public function create(Request $request)
    {
        session(['state_id' => null]);
        $products = MasterProduct::where('is_publish', 1);
        if ($request->category_id) {
            $products = $products->join('master_category_master_product', 'master_category_master_product.product_id',
                '=', 'master_products.id')->where('category_id', $request->category_id);
        }
        $products = $products->when(! empty($request->s), function ($query) use ($request) {
            return $query->where('title', 'like', "%{$request->s}%");
        })->get();

        return view('product.create', [
            'categories' => Category::active()->get(),
            'products'   => $products,
        ]);
    }

    public function designer($id)
    {
        $masterproduct = MasterProduct::find($id);
        $templates = $masterproduct->templates;
        return view('product.designer', array(
            'masterproduct' => $masterproduct,
            'templates' => $templates,
        ));
    }

    public function saveDesigner(Request $request, $id) {
        $request->flash();

        $validator = Validator::make($request->all(), [
          'state_id' => 'required',
          'print_file' => 'required',
          'proof_file' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        session(['design' => json_encode($request->all())]);
        return redirect()->route('design.finish');
    }

    public function additional()
    {
        return view('product.additional');
    }

    public function finish()
    {
        if(!session('design')) {
            return redirect()->route('design.create');
        }
        return view('product.finish');
    }

    public function saving()
    {
        return view('product.saving');
    }

    public function success()
    {
        return view('product.saved');
    }

    public function store(Request $request)
    {
        $request->flash();
        $store_id = session('current_store')->id;
        $validator = Validator::make($request->all(), [
          'title' => ['required', Rule::unique('product_designs')->where(function ($query) use ($store_id) {
            $query->where('store_id', $store_id);
          })],
          'description' => 'required',
          'sku_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $design = ProductDesign::create(array(
                'store_id' => $store_id,
                'title' => $request->title,
                'description' => $request->description,
                'sku_code' => $request->sku_code,
                'is_publish' => $request->is_publish
            ));

            $design_data = json_decode(session('design'));
            $masterproduct = MasterProduct::find($design_data->master_product_id);
            $mastertemplate = Template::find($design_data->template);

            $product = Product::create(array(
                'store_id' => session('current_store')->id,
                'master_product_id' => $design_data->master_product_id,
                'design_id' => $design->id,
                'title' => $masterproduct->title." ".$design->title,
                'description' => $design->description."\n".$masterproduct->description."\n".$masterproduct->size_chart,
                'is_publish' => $request->is_publish
            ));

            foreach($masterproduct->options as $i => $option) {
                $productoption = ProductOption::create(array(
                    'product_id' => $product->id,
                    'title' => $option->title
                ));

                if($masterproduct->options->count() == 2) {
                    foreach($option->details as $detail) {
                        if($i==0 || ($i==1 && in_array($detail->key, $design_data->variants))) {
                            ProductOptionDetail::create(array(
                                'option_id' => $productoption->id,
                                'title' => $detail->title,
                                'key' => $detail->key
                            ));
                        }
                    }
                }
            }

            foreach($masterproduct->skuvariants($design_data->variants) as $sku) {
                ProductSku::create(array(
                    'product_id' => $product->id,
                    'option_detail_key1' => $sku->option_detail_key1,
                    'option_detail_key2' => $sku->option_detail_key2,
                    'sku_code' => $sku->sku_code."-".$design->sku_code,
                    'stock' => 0,
                    'price' => 50000 + $mastertemplate->price, #perlu di adjust dari harga designer
                    'weight' => $sku->weight,
                    'width' => $sku->width,
                    'length' => $sku->length,
                    'height' => $sku->height
                ));
            }

            $editor = ProductEditor::create(array(
                'product_id' => $product->id,
                'template_id' => $design_data->template,
                'state_id' => $design_data->state_id,
                'print_file' => $design_data->print_file,
                'proof_file' => $design_data->proof_file
            ));

            $response = $this->generateImage($product, $masterproduct, $editor);
            if($response['status']=='error') {
                return redirect()->back()->with('error', $response['message']);
            }

            $this->applyAllProduct($request->apply_products, $design, $editor, $request);
            DB::commit();

            return redirect()->route('design.saved');
        }catch(\Exception $e){
          DB::rollback();
          throw new \Exception($e->getMessage());
        }
    }

    public function applyAllProduct($masterproduct_ids, $design, $editor, $request) {
        foreach($masterproduct_ids as $master_id) {
            $masterproduct = MasterProduct::find($master_id);
            $mastertemplate = Template::find($design_data->template); #ambil pertama template master productnya

            $product = Product::create(array(
                'store_id' => session('current_store')->id,
                'master_product_id' => $master_id,
                'design_id' => $design->id,
                'title' => $masterproduct->title." ".$design->title,
                'description' => $design->description."\n".$masterproduct->description."\n".$masterproduct->size_chart,
                'is_publish' => $request->is_publish
            ));

            foreach($masterproduct->options as $i => $option) {
                $productoption = ProductOption::create(array(
                    'product_id' => $product->id,
                    'title' => $option->title
                ));

                if($masterproduct->options->count() == 2) {
                    foreach($option->details as $detail) {
                        if($i==0 || ($i==1 && in_array($detail->key, $design_data->variants))) {
                            ProductOptionDetail::create(array(
                                'option_id' => $productoption->id,
                                'title' => $detail->title,
                                'key' => $detail->key
                            ));
                        }
                    }
                }
            }

            foreach($masterproduct->skuvariants($design_data->variants) as $sku) {
                ProductSku::create(array(
                    'product_id' => $product->id,
                    'option_detail_key1' => $sku->option_detail_key1,
                    'option_detail_key2' => $sku->option_detail_key2,
                    'sku_code' => $sku->sku_code."-".$design->sku_code,
                    'stock' => 0,
                    'price' => $sku->selling_price, #perlu di adjust dari master
                    'weight' => $sku->weight,
                    'width' => $sku->width,
                    'length' => $sku->length,
                    'height' => $sku->height
                ));
            }

            #Replicate editor lama jadi
            $editor = ProductEditor::create(array( 
                'product_id' => $product->id,
                'template_id' => $design_data->template, #ganti template id
                'state_id' => $design_data->state_id, 
                'print_file' => $design_data->print_file,
                'proof_file' => $design_data->proof_file
            ));

            $response = $this->generateImage($product, $masterproduct, $editor);
            if($response['status']=='error') {
                return redirect()->back()->with('error', $response['message']);
            }
        }
            
    }

    public function generateImage($product, $masterproduct, $editor) {
        $key = 'PrinterousCustomerCanvasDemo123!@#';
        $url = "https://canvas.printerous.com/production/DI/api/rendering/multipage/preview";
        $pages = array();

        $data = array(
            "Front" => array(
                "type" => "image",
                "image" => $editor->proof_file,
                "resizeMode" => "fill"
            )
        );

        foreach($editor->previews as $preview) {
            if($preview->config && is_array(json_decode($preview->config, true))) {
                $data = array_merge($data, json_decode($preview->config, true));
            }
            $pages[] = array(
                'template' => $preview->customer_canvas,
                'format' => 'png',
                'data' => $data,
            );
        }

        $post = array(
            'pages' => $pages,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type:application/json',
            "X-CustomersCanvasAPIKey: ".$key,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = json_decode(curl_exec($ch),true);
        curl_close($ch);

        if(isset($result['message']) && $result['message']=="Error") {
            return array('status' => 'error', 'message' => $result['details']);
        }
        foreach($result['results'] as $result) {
            $file = file_get_contents($result['url']);
            $extension = pathinfo($result['url'], PATHINFO_EXTENSION);
            $filename = sha1(Str::random(32)).".".$extension;
            Storage::put('/public/products/'.$filename, $file);
            ProductImage::create(array(
                'product_id' => $product->id,
                'image' => $filename
            ));
        }

        foreach($masterproduct->images as $i => $image) {
            if($i>=1) {
                $url = env('BACKEND_URL').'/storage/masterproduct/'.$image->image;
                try {
                    $file = file_get_contents($url);
                    $extension = pathinfo($url, PATHINFO_EXTENSION);
                    $filename = sha1(Str::random(32)).".".$extension;
                    Storage::put('/public/products/'.$filename, $file);
                    ProductImage::create(array(
                        'product_id' => $product->id,
                        'image' => $filename
                    ));
                }catch(\Exception $e){
                  return array('status' => 'error', 'message' => 'Error get file '.$url);
                }
            }
        }
        return array('status' => 'success');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('product.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
