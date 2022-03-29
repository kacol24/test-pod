<?php

use App\Enums\Permissions;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\AddressController;
use App\Http\Controllers\Account\SwitchStoreController;
use App\Http\Controllers\Account\TeamController;
use App\Http\Controllers\Account\TeamInvitationController;
use App\Http\Controllers\Account\WalletController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\DesignProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopeeController;
use App\Http\Controllers\TokopediaController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\Xendit\XenditController;
use App\Http\Controllers\Xendit\XenditWebhookController;
use App\Jobs\CapacityUpdated;
use App\Models\Order\Order as OrderModel;
use App\Models\Product\Product as ProductModel;
use App\Repositories\Facades\Order;
use App\Repositories\Facades\Product;
use App\Repositories\Facades\Shopee;
use App\Services\Facades\Shopee as ShopeeService;
use Illuminate\Support\Facades\Route;

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

use App\Services\Facades\Prism;
use App\Models\User;

Route::get('prism/user', function () {
    $user = User::find(1);
    echo Prism::createUser(array(
        'uid' => $user->id,
        'email' => $user->email,
        'name' => $user->name,
        'phone' => $user->phone
    ));
});

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('switch-store/{storename}', [SwitchStoreController::class, 'switch'])->name('switchstore');

    Route::view('my-account', 'account.myaccount')->name('myaccount');
    Route::put('my-account', [AccountController::class, 'update']);
    Route::put('my-account/change-password', [AccountController::class, 'changePassword'])
         ->name('myaccount.changepassword');
    Route::view('my-purchases', 'account.mypurchases')->name('myorders');
    Route::view('my-purchases/{id?}', 'account.orderdetail')->name('orderdetail');
    Route::view('my-purchases/{id?}/print', 'account.print-invoice')->name('orderdetail.print');
    Route::view('my-shipments', 'account.myshipments')->name('myshipments');
    Route::view('my-shipments/{id?}', 'account.shipmentdetail')->name('shipmentdetail');

    Route::get('my-address', [AddressController::class, 'index'])->name('myaddress');
    Route::post('my-address', [AddressController::class, 'store'])->name('myaddress.store');
    Route::put('my-address/{address}', [AccountController::class, 'update'])->name('myaddress.update');
    Route::delete('my-address/{address}', [AccountController::class, 'update'])->name('myaddress.destroy');

    Route::get('referrals', [AccountController::class, 'referral'])->name('myreferral');

    Route::middleware('can:'.Permissions::TEAM)->group(function (){
        Route::get('my-team', [TeamController::class, 'index'])->name('myteam');
        Route::delete('my-team/{member}', [TeamController::class, 'destroy'])->name('myteam.destroy');
        Route::put('my-team/{member}', [TeamController::class, 'update'])->name('myteam.update');
        Route::post('my-team/invite', [TeamInvitationController::class, 'store'])->name('myteam.invite');
        Route::delete('my-team/invite/{invite?}', [TeamInvitationController::class, 'destroy'])->name('myteam.destroy_invite');
    });

    Route::middleware('can:'.Permissions::WALLET)->group(function (){
        Route::get('my-wallet', [WalletController::class, 'index'])->name('mywallet');
        Route::get('top-up', [TopupController::class, 'index'])->name('topup');
        Route::post('top-up', [TopupController::class, 'store']);
        Route::get('top-up/success', [TopupController::class, 'success'])->name('topup.success');
    });

    Route::post('xendit/cc', [XenditController::class, 'creditCard'])->name('xendit.cc');
    Route::post('xendit/e-wallet', [XenditController::class, 'ewallet'])->name('xendit.ewallet');

    Route::middleware('can:'.Permissions::DESIGN)->group(function (){
        Route::get('design/add-more', [DesignController::class, 'additional'])->name('design.additional');
        Route::get('design/finish', [DesignController::class, 'finish'])->name('design.finish');
        Route::post('design/finish', [DesignController::class, 'store'])->name('design.finish.store');
        Route::get('design/saving', [DesignController::class, 'saving'])->name('design.saving');
        Route::get('design/success', [DesignController::class, 'success'])->name('design.saved');
        Route::get('design/datatable', [DesignController::class, 'datatable'])->name('design.datatable');
        Route::resource('design', DesignController::class);
        Route::get('design/product/{id?}', [DesignController::class, 'designer'])->name('design');
        Route::post('design/product/{id}', [DesignController::class, 'saveDesigner'])->name('design.post');
        Route::get('design/product/{id}/remove', [DesignController::class, 'removeProduct'])
             ->name('design.remove-product');
        Route::get('design/{design}/product/{product}/edit', [DesignProductController::class, 'edit'])
             ->name('design.product.edit');
        Route::post('design/{design}/product/{product}/edit', [DesignProductController::class, 'update']);
    });

    Route::middleware('can:' . Permissions::ORDERS)->group(function (){
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    });
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

Route::post('webhook/tokopedia/orders', [TokopediaController::class, 'order']);
Route::post('webhook/tokopedia/status', [TokopediaController::class, 'status']);
Route::post('webhook/shopee', [ShopeeController::class, 'index']);


require __DIR__.'/auth.php';

Route::get('/register/invited/{invitation}', [RegisteredUserController::class, 'invited'])
     ->name('register_invited')
     ->middleware('guest');

Route::post('/register/invited/{invitation}', [RegisteredUserController::class, 'storeInvited'])
     ->middleware('guest');

Route::get('my-team/invite/{invitation}/accept', [TeamInvitationController::class, 'accept'])
     ->middleware(['signed'])
     ->name('myteam.accept');
