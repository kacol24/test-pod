<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\SwitchStoreController;
use App\Http\Controllers\Account\WalletController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\DesignProductController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\Xendit\XenditController;
use App\Http\Controllers\Xendit\XenditWebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokopediaController;
use App\Jobs\CapacityUpdated;
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

use App\Repositories\Facades\Tokopedia;
use App\Repositories\Facades\Shopee;
use App\Repositories\Facades\Order;
use App\Repositories\Facades\Product;
use App\Services\Facades\Shopee as ShopeeService;
use App\Models\Product\Product as ProductModel;
use App\Models\Order\Order as OrderModel;

Route::get('shopee/unpublish-product', function () {
    $product = ProductModel::where('store_id', session('current_store')->id)->where('id',8)->first();
    Product::unpublish($product);
});

Route::get('shopee/delete-product', function () {
    $product = ProductModel::where('store_id', session('current_store')->id)->where('id',11)->first();
    Product::delete($product);
});

Route::get('shopee/publish-product', function () {
    $product = ProductModel::where('store_id', session('current_store')->id)->where('id',8)->first();
    Product::publish($product);
});

Route::get('shopee/update-product', function () {
    $product = ProductModel::where('store_id', session('current_store')->id)->where('id',8)->first();
    Shopee::update($product);
});

Route::get('shopee/create-product', function () {
    $product = ProductModel::where('store_id', session('current_store')->id)->where('id',11)->first();
    Shopee::create($product);
});

Route::get('shopee/auth', function () {
    if(!session('current_store')) {
        return redirect()->route('login');
    }
    return redirect()->to(ShopeeService::authUrl());
});

Route::get('shopee/callback', function (Request $request) {
    if(!session('current_store')) {
        return redirect()->route('login');
    }
    $resp = ShopeeService::getToken($request->code, $request->shop_id);
    if($resp['message']) {
        echo $resp['message'];
        // return redirect()->to('store')->with('error', $resp['message']); #redirect back ke page connect store & show error
    }else if(isset($resp['access_token'])) {
        $platform = StorePlatform::firstOrcreate(array(
            'store_id' => session('current_store')->id,
            'platform' => 'shopee',
            'platform_store_id' => $request->shop_id,
        ));

        $platform->access_token = $resp['access_token'];
        $platform->refresh_token = $resp['refresh_token'];
        $platform->save();
        // return redirect()->to('store'); #redirect back ke connect store
    }
})->name('shopee.callback');


Route::post('webhook/tokopedia/orders', [TokopediaController::class, 'order']);
Route::post('webhook/tokopedia/status', [TokopediaController::class, 'status']);

Route::get('tokopedia/create-product', function () {
    $product = ProductModel::where('store_id', session('current_store')->id)->where('id',11)->first();
    Tokopedia::create($product);
});

Route::get('tokopedia/update-product', function () {
    $product = ProductModel::where('store_id', session('current_store')->id)->where('id',8)->first();
    Tokopedia::update($product);
});

Route::get('tokopedia/unpublish-product', function () {
    $product = ProductModel::where('store_id', session('current_store')->id)->where('id',8)->first();
    Product::unpublish($product);
});

Route::get('tokopedia/accept-order', function () {
    $order = OrderModel::find(1);
    Order::accept($order);
});

Route::get('tokopedia/reject-order', function () {
    $order = OrderModel::find(2);
    Order::reject($order);
});

Route::get('tokopedia/shipping-label', function () {
    $order = OrderModel::find(1);
    return Order::label($order);
});

Route::get('tokopedia/request-pickup', function () {
    $order = OrderModel::find(1);
    return Order::pickup($order);
});

Route::get('tokopedia/confirm-shipping', function () {
    $order = OrderModel::find(1);
    $order->shipping->awb = "RESIM4NT413";
    $order->save();
    return Order::awb($order);
});

Route::get('tokopedia/publish-product', function () {
    $product = ProductModel::where('store_id', session('current_store')->id)->where('id',8)->first();
    Product::publish($product); 
});

Route::get('tokopedia/delete-product', function () {
    $product = ProductModel::where('store_id', session('current_store')->id)->where('id',11)->first();
    Product::delete($product);
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
    Route::get('referrals', [AccountController::class, 'referral'])->name('myreferral');

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

Route::get('capacity-updated/{id}', function($id){
    CapacityUpdated::dispatch($id);
});

require __DIR__.'/auth.php';
