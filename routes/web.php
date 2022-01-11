<?php

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

Route::view('login', 'auth.login')->name('login');
Route::view('login/verify', 'auth.otp')->name('otp');
Route::view('register', 'auth.register')->name('register');
Route::view('register/success', 'auth.register-success')->name('register_success');

Route::view('dashboard', 'dashboard')->name('dashboard');

Route::view('my-account', 'account.myaccount')->name('myaccount');
Route::view('my-purchases', 'account.mypurchases')->name('myorders');
Route::view('my-purchases/{id?}', 'account.orderdetail')->name('orderdetail');
Route::view('my-purchases/{id?}/print', 'account.print-invoice')->name('orderdetail.print');
Route::view('my-shipments', 'account.myshipments')->name('myshipments');
Route::view('my-shipments/{id?}', 'account.shipmentdetail')->name('shipmentdetail');
Route::view('my-address', 'account.myaddress')->name('myaddress');
Route::view('my-team', 'account.myteam')->name('myteam');
Route::view('my-wallet', 'account.mywallet')->name('mywallet');
