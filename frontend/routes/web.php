<?php

use App\Http\Controllers\Account\SwitchStoreController;
use App\Http\Controllers\Account\WalletController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\Xendit\XenditController;
use App\Http\Controllers\Xendit\XenditWebhookController;
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

    Route::get('products/designer/{id}', [ProductController::class, 'designer'])->name('products.designer');
    Route::get('products/add-more', [ProductController::class, 'additional'])->name('products.additional');
    Route::get('products/finish', [ProductController::class, 'finish'])->name('products.finish');
    Route::get('products/saving', [ProductController::class, 'saving'])->name('products.saving');
    Route::get('products/success', [ProductController::class, 'success'])->name('products.saved');
    Route::resource('products', ProductController::class);
});

Route::prefix('xendit')->group(function () {
    Route::post('notify-ewallet', [XenditWebhookController::class, 'notifyEwallet'])
         ->name('xendit.notifyewallet');
    Route::post('xendit/notify-va-created', [XenditWebhookController::class, 'notifyVACreated'])
         ->name('xendit.notifyvacreated');
    Route::post('xendit/notify-va-paid', [XenditWebhookController::class, 'notifyVAPaid'])
         ->name('xendit.notifyvapaid');
});

require __DIR__.'/auth.php';
