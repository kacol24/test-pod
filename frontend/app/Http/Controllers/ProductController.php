<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterProduct\Category;
use App\Models\MasterProduct\MasterProduct;

class ProductController extends Controller
{
    public function index()
    {
        return view('product.index');
    }

    public function create(Request $request)
    {
        $products = MasterProduct::where('is_publish',1);
        if($request->category_id) {
            $products = $products->join('master_category_master_product','master_category_master_product.product_id','=','master_products.id')->where('category_id', $request->category_id);
        }
        $products = $products->when(! empty($request->s), function ($query) use ($request) {
          return $query->where('title', 'like', "%{$request->s}%");
        })->get();
        return view('product.create', array(
            'categories' => Category::active()->get(),
            'products' => $products
        ));
    }

    public function designer($id)
    {
        $masterproduct = MasterProduct::find($id);
        $templates = $masterproduct->templates;
        return view('product.designer', array(
            'masterproduct' => $masterproduct,
            'templates' => $templates
        ));
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
        //
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
