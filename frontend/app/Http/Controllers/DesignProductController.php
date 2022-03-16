<?php

namespace App\Http\Controllers;

use App\Models\Product\Product;
use App\Models\Product\ProductDesign;
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
        dd($request->all());
    }
}
