<?php

namespace App\Http\Controllers;

use App\Models\Product\Product;
use App\Models\Product\ProductDesign;
use App\Models\Product\ProductSku;
use Illuminate\Http\Request;

class DesignProductController extends Controller
{
    public function edit($designId, $productId)
    {
        $design = ProductDesign::findOrFail($designId);
        $product = Product::findOrFail($productId);

        $data = [
            'design'         => $design,
            'product'        => $product,
            'masterproduct'  => $product->masterproduct,
            'skus'           => $product->skus,
            'existingDesign' => $product->editor->toArray(),
            'templates'      => $product->masterproduct->templates,
        ];

        return view('design.edit-designer', $data);
    }

    public function update(Request $request, $designId, $productId)
    {
        $product = Product::findOrFail($productId);

        \DB::beginTransaction();
        $product->editor()->update([
            'template_id' => $request->template,
            'state_id'    => $request->state_id,
            'print_file'  => $request->print_file,
            'proof_file'  => $request->proof_file,
        ]);
        ProductSku::ofProduct($product->id)->delete();
        ProductSku::ofProduct($product->id)->whereIn('option_detail_key2', $request->variants)->restore();

        foreach ($request->selling_price as $key => $price) {
            ProductSku::ofProduct($product->id)
                      ->where('option_detail_key1', $key)
                      ->restore();
            ProductSku::ofProduct($product->id)
                      ->where('option_detail_key1', $key)
                      ->update([
                          'price' => $price,
                      ]);
        }
        \DB::commit();

        return redirect()->route('design.edit', $designId);
    }
}
