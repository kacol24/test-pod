<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterProduct\Category;
use App\Models\MasterProduct\MasterProduct;
use App\Models\MasterProduct\MasterProductOption;
use Validator, DB;
use App\Models\Product\ProductDesign;
use App\Models\Product\ProductEditor;
use App\Models\Product\Product;
use App\Models\Product\ProductOption;
use App\Models\Product\ProductOptionDetail;
use App\Models\Product\ProductSku;

class DesignController extends Controller
{
    public function index()
    {
        return view('product.index');
    }

    public function create(Request $request)
    {
        session(['state_id' => null]);
        $products = MasterProduct::where('is_publish',1);
        if($request->category_id) {
            $products = $products->join('master_category_master_product','master_category_master_product.product_id','=','master_products.id')->where('category_id', $request->category_id);
        }
        $products = $products->when(! empty($request->s), function ($query) use ($request) {
          return $query->where('title', 'like', "%{$request->s}%");
        })->get();
        return view('product.create', array(
            'categories' => Category::active()->get(),
            'products' => $products,
        ));
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

        $validator = Validator::make($request->all(), [
          'title' => 'required',
          'description' => 'required',
          'sku_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $design = ProductDesign::create(array(
                'store_id' => session('current_store')->id,
                'title' => $request->title,
                'description' => $request->description,
                'sku_code' => $request->sku_code
            ));

            $design_data = json_decode(session('design'));
            $masterproduct = MasterProduct::find($design_data->master_product_id);

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
                    'price' => 50000, #perlu di adjust dari harga designer
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

            $this->generateImage($product, $masterproduct, $editor);

            DB::commit();
        }catch(\Exception $e){
          DB::rollback();
          throw new \Exception($e->getMessage());
        }
    }

    public function generateImage($product, $masterproduct, $editor) {
        foreach($masterproduct->previews as $preview) {
            
        }
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
