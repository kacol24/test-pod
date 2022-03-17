<?php

use App\Http\Controllers\Account\SwitchStoreController;
use App\Http\Controllers\Account\WalletController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\DesignProductController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\Xendit\XenditController;
use App\Http\Controllers\Xendit\XenditWebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokopediaController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Library\Facades\Tokopedia;
use App\Library\Facades\Shopee;
use App\Models\Product\Product;
use App\Models\Product\ProductPlatform;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\TokopediaLog;
use App\Models\Order\Order;
use App\Models\StorePlatform;


Route::get('shopee/auth', function () {
    if(!session('current_store')) {
        return redirect()->route('login');
    }
    return redirect()->to(Shopee::authUrl());
});

Route::get('shopee/create-product', function () {
    echo Shopee::refreshToken(44235);
});

Route::get('shopee/callback', function (Request $request) {
    if(!session('current_store')) {
        return redirect()->route('login');
    }
    $resp = Shopee::getToken($request->code, $request->shop_id);
    if($resp['message']) {
        echo $resp['message'];
        // return redirect()->to('store')->with('error', $resp['message']);
    }else if(isset($resp['access_token'])) {
        StorePlatform::create(array(
            'store_id' => session('current_store')->id,
            'platform' => 'shopee',
            'platform_store_id' => $request->shop_id,
            'access_token' => $resp['access_token'],
            'refresh_token' => $resp['refresh_token']
        ));
        // return redirect()->to('store');
    }
})->name('shopee.callback');


Route::post('webhook/tokopedia/orders', [TokopediaController::class, 'order']);
Route::post('webhook/tokopedia/status', [TokopediaController::class, 'status']);

Route::get('tokopedia/create-product', function () {
    $shop_id = 13403511;
    $product = Product::find(8);

    $images = array();
    foreach($product->images as $image) {
        // $images[] = array('file_path' => asset(Storage::url('public/products/'.$image->image)));
        $images[] = array('file_path' => "https://ecs7.tokopedia.net/img/cache/700/product-1/2017/9/27/5510391/5510391_9968635e-a6f4-446a-84d0-ff3a98a5d4a2.jpg");
    }
    $sku = $product->firstsku();
    $productdata = array(
        "name" => $product->title,
        "condition" => "NEW",
        "description" => $product->description,
        "sku" => $sku->sku_code,
        "price" => $sku->price,
        "status" => "LIMITED",
        "stock" => $sku->stock($product),
        "min_order" => 1,
        "category_id" => 562, #need update
        "dimension" => array(
            "height" => $sku->height,
            "width" => $sku->width,
            "length" => $sku->length
        ),
        "price_currency" => "IDR",
        "weight" => $sku->weight,
        "weight_unit" => "GR",
        "is_free_return" => false,
        "is_must_insurance" => false,
        "pictures" => $images,
        "preorder" => array(
            "is_active" => true,
            "duration" => $product->masterproduct->production_time + $product->masterproduct->fulfillment_time,
            "time_unit" => "DAY"
        )
    );

    $variant = array();
    if($product->skus->count()>1) {
        $toped_variant = Tokopedia::getVariant($product->masterproduct->tokopedia_category_id);

        $selection = array();
        foreach($product->options as $option) {
            foreach($toped_variant['variant_details'] as $variant_detail) {
                if($variant_detail['name'] == $option->title) {
                    $options = array();
                    foreach($option->details as $detail) {
                        $options[] = array(
                            'hex_code' => "",
                            'unit_value_id' => 0,
                            'value' => $detail->title
                        );
                    }
                    $selection[] = array(
                        'name' => $variant_detail['name'],
                        'id' => $variant_detail['variant_id'],
                        'unit_id' => $variant_detail['units'][0]['variant_unit_id'],
                        'options' => $options
                    );
                }
            }
        }

        $variantproducts = array();
        foreach($product->skus as $i => $sku) {
            $variantproducts[] = array(
                "is_primary" => ($i==0) ? true : false,
                "status" => "LIMITED",
                "price" => $sku->price,
                "stock" => $sku->stock($product),
                "sku" => $sku->sku_code,
                "combination" => $sku->getCombinationVariant($selection, $toped_variant['variant_details'])
            );
        }

        foreach($selection as $i => $select) {
            unset($select['name']);
            $selection[$i] = $select;
        }
        $variant['products'] = $variantproducts;
        $variant['selection'] = $selection;
        // $variant['pictures'] = array(array(
        //     'file_path' => "https://ecs7.tokopedia.net/img/cache/700/product-1/2017/9/27/5510391/5510391_9968635e-a6f4-446a-84d0-ff3a98a5d4a2.jpg"
        // ));
        $productdata['variant'] = $variant;
    }

    $data['products'] = array($productdata);
    $response = Tokopedia::createProduct($data, $shop_id);
    if(isset($response['data']['success_rows_data'][0]['product_id'])) {
        ProductPlatform::create(array(
            'product_id' => $product->id,
            'platform' => 'tokopedia',
            'platform_product_id' => $response['data']['success_rows_data'][0]['product_id']
        ));
    }
});

Route::get('tokopedia/update-product', function () {
    $shop_id = 13403511;
    $product = Product::find(8);

    $images = array();
    foreach($product->images as $image) {
        // $images[] = array('file_path' => asset(Storage::url('public/products/'.$image->image)));
        $images[] = array('file_path' => "https://ecs7.tokopedia.net/img/cache/700/product-1/2017/9/27/5510391/5510391_9968635e-a6f4-446a-84d0-ff3a98a5d4a2.jpg");
    }
    $sku = $product->firstsku();
    $productdata = array(
        "id" => (int) $product->platform('tokopedia')->platform_product_id,
        "name" => $product->title,
        "condition" => "NEW",
        "description" => $product->description,
        "sku" => $sku->sku_code,
        "price" => $sku->price,
        "status" => "LIMITED",
        "stock" => $sku->stock($product),
        "min_order" => 1,
        "category_id" => 562, #need update
        "dimension" => array(
            "height" => $sku->height,
            "width" => $sku->width,
            "length" => $sku->length
        ),
        "price_currency" => "IDR",
        "weight" => $sku->weight,
        "weight_unit" => "GR",
        "is_free_return" => false,
        "is_must_insurance" => false,
        "pictures" => $images,
        "preorder" => array(
            "is_active" => true,
            "duration" => $product->masterproduct->production_time + $product->masterproduct->fulfillment_time,
            "time_unit" => "DAY"
        )
    );

    $variant = array();
    if($product->skus->count()>1) {
        $toped_variant = Tokopedia::getVariant($product->masterproduct->tokopedia_category_id);

        $selection = array();
        foreach($product->options as $option) {
            foreach($toped_variant['variant_details'] as $variant_detail) {
                if($variant_detail['name'] == $option->title) {
                    $options = array();
                    foreach($option->details as $detail) {
                        $options[] = array(
                            'hex_code' => "",
                            'unit_value_id' => 0,
                            'value' => $detail->title
                        );
                    }
                    $selection[] = array(
                        'name' => $variant_detail['name'],
                        'id' => $variant_detail['variant_id'],
                        'unit_id' => $variant_detail['units'][0]['variant_unit_id'],
                        'options' => $options
                    );
                }
            }
        }

        $variantproducts = array();
        foreach($product->skus as $i => $sku) {
            $variantproducts[] = array(
                "is_primary" => ($i==0) ? true : false,
                "status" => "LIMITED",
                "price" => $sku->price,
                "stock" => $sku->stock($product),
                "sku" => $sku->sku_code,
                "combination" => $sku->getCombinationVariant($selection, $toped_variant['variant_details'])
            );
        }

        foreach($selection as $i => $select) {
            unset($select['name']);
            $selection[$i] = $select;
        }
        $variant['products'] = $variantproducts;
        $variant['selection'] = $selection;
        // $variant['pictures'] = array(array(
        //     'file_path' => "https://ecs7.tokopedia.net/img/cache/700/product-1/2017/9/27/5510391/5510391_9968635e-a6f4-446a-84d0-ff3a98a5d4a2.jpg"
        // ));
        $productdata['variant'] = $variant;
    }

    $data['products'] = array($productdata);
    $response = Tokopedia::updateProduct($data, $shop_id);
});

Route::get('tokopedia/inactive', function () {
    $shop_id = 13403511;
    $data = array(
        'product_id' => array(3152520682)
    );
    Tokopedia::setInactiveProduct($data, $shop_id);
});

Route::get('tokopedia/accept-order', function () {
    $order = Order::find(15);
    Tokopedia::acceptOrder($order->platform('tokopedia')->platform_order_id);
});

Route::get('tokopedia/reject-order', function () {
    $order = Order::find(2);
    $data = array(
        'reason_code' => 1,
        'reason' => 'out of stock'
    );
    Tokopedia::rejectOrder($data, $order->platform('tokopedia')->platform_order_id);
});

// Route::get('tokopedia/process-stock', function () {
//     $order = Order::find(1);


//     $checkStock = true;
//     foreach($order->details as $detail) {
//         if($detail->product->template_id) {

//         }else {
//             if($)
//         }
//     }
//     return $checkStock;
// });


Route::get('tokopedia/shipping-label', function () {
    $order = Order::find(1);
    return Tokopedia::shippingLabel($order->platform('tokopedia')->platform_order_id);
});

Route::get('tokopedia/request-pickup', function () {
    $order = Order::find(15);
    $data = array(
        'order_id' => (int)$order->platform('tokopedia')->platform_order_id,
        'shop_id' => (int)$order->store->platform('tokopedia')->platform_store_id
    );
    Tokopedia::requestPickup($data);
});

Route::get('tokopedia/confirm-shipping', function () {
    $order = Order::find(14);
    $data = array(
        'order_status' => 500,
        'shipping_ref_num' => "RESIM4NT413"
    );
    Tokopedia::confirmShipping($data, (int)$order->platform('tokopedia')->platform_order_id);
});

Route::get('tokopedia/active', function () {
    $shop_id = 13403511;
    $product = Product::find(8);

    if($product->skus->count()>1) {
        $platform_product = Tokopedia::getProduct((int)$product->platform('tokopedia')->platform_product_id);
        $product_id = array();
        foreach($platform_product['data'][0]['variant']['childrenID'] as $variant) {
            $product_id[] = $variant;
        }
        $data = array(
            'product_id' => $product_id
        );
    }else {
        $data = array(
            'product_id' => array((int)$product->platform('tokopedia')->platform_product_id)
        );
    }

    echo json_encode(Tokopedia::setActiveProduct($data, $shop_id));
});

Route::get('tokopedia/delete-product', function () {
    $shop_id = 13403511;
    $data = array(
        'product_id' => array(3152419729)
    );
    echo json_encode(Tokopedia::deleteProduct($data, $shop_id));
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('switch-store/{storename}', [SwitchStoreController::class, 'switch'])->name('switchstore');

    Route::view('my-account', 'account.myaccount')->name('myaccount');
    Route::view('my-purchases', 'account.mypurchases')->name('myorders');
    Route::view('my-purchases/{id?}', 'account.orderdetail')->name('orderdetail');
    Route::view('my-purchases/{id?}/print', 'account.print-invoice')->name('orderdetail.print');
    Route::view('my-shipments', 'account.myshipments')->name('myshipments');
    Route::view('my-shipments/{id?}', 'account.shipmentdetail')->name('shipmentdetail');
    Route::view('my-address', 'account.myaddress')->name('myaddress');
    Route::view('my-team', 'account.myteam')->name('myteam');
    Route::get('my-wallet', [WalletController::class, 'index'])->name('mywallet');

    Route::get('top-up', [TopupController::class, 'index'])->name('topup');
    Route::post('top-up', [TopupController::class, 'store']);
    Route::get('top-up/success', [TopupController::class, 'success'])->name('topup.success');

    Route::post('xendit/cc', [XenditController::class, 'creditCard'])->name('xendit.cc');
    Route::post('xendit/e-wallet', [XenditController::class, 'ewallet'])->name('xendit.ewallet');

    Route::get('design/add-more', [DesignController::class, 'additional'])->name('design.additional');
    Route::get('design/finish', [DesignController::class, 'finish'])->name('design.finish');
    Route::post('design/finish', [DesignController::class, 'store'])->name('design.finish.store');
    Route::get('design/saving', [DesignController::class, 'saving'])->name('design.saving');
    Route::get('design/success', [DesignController::class, 'success'])->name('design.saved');
    Route::get('design/datatable', [DesignController::class, 'datatable'])->name('design.datatable');
    Route::resource('design', DesignController::class);
    Route::get('design/product/{id}', [DesignController::class, 'designer'])->name('design');
    Route::post('design/product/{id}', [DesignController::class, 'saveDesigner'])->name('design.post');
    Route::get('design/product/{id}/remove', [DesignController::class, 'removeProduct'])->name('design.remove-product');
    Route::get('design/{design}/product/{product}/edit', [DesignProductController::class, 'edit'])->name('design.product.edit');
    Route::post('design/{design}/product/{product}/edit', [DesignProductController::class, 'update']);
});

Route::prefix('xendit')->group(function () {
    Route::post('notify-ewallet', [XenditWebhookController::class, 'notifyEwallet'])
         ->name('xendit.notifyewallet');
    Route::post('notify-va-created', [XenditWebhookController::class, 'notifyVACreated'])
         ->name('xendit.notifyvacreated');
    Route::post('notify-va-paid', [XenditWebhookController::class, 'notifyVAPaid'])
         ->name('xendit.notifyvapaid');
});

require __DIR__.'/auth.php';
