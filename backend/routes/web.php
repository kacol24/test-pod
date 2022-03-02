<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\Product\CapacityController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Product\OptionController;
use App\Http\Controllers\Product\OptionSetController;
use App\Http\Controllers\Product\ProductController;
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
Route::group(['namespace' => 'App\Http\Controllers'], function () {
    #Login
    Route::get('/', ['as' => 'login', 'uses' => 'LoginController@getLogin']);
    Route::get('/login', ['as' => 'login', 'uses' => 'LoginController@getLogin']);
    Route::post('/login', ['as' => 'login.post', 'uses' => 'LoginController@postLogin']);
    Route::get('/forget', ['as' => 'forget', 'uses' => 'LoginController@getForget']);
    Route::post('/forget', ['as' => 'forget.post', 'uses' => 'LoginController@postForget']);
    Route::get('/reset-password/{token}', ['as' => 'resetpassword', 'uses' => 'LoginController@getReset']);
    Route::post('/reset-password/{token}', ['as' => 'resetpassword.post', 'uses' => 'LoginController@postReset']);

    #Banner
    Route::post('banner/delete', ['as' => 'banner.destroy', 'uses' => 'BannerController@delete']);
    Route::post('banner/status/{id}', ['as' => 'banner.status', 'uses' => 'BannerController@status']);
    Route::get('banner/get-datatable', ['as' => 'banner.datatable', 'uses' => 'BannerController@datatable']);
    Route::resource('banner', 'BannerController')->except(['destroy']);;
});

#Settings
Route::group(['prefix' => 'settings', 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('role/list', ['as' => 'role.list', 'uses' => 'SettingController@getRoleList']);
    Route::get('add-role', ['as' => 'role.add', 'uses' => 'SettingController@createRole']);
    Route::post('add-role', ['as' => 'role.store', 'uses' => 'SettingController@storeRole']);
    Route::get('edit-role/{id}', ['as' => 'role.edit', 'uses' => 'SettingController@editRole']);
    Route::post('edit-role/{id}', ['as' => 'role.update', 'uses' => 'SettingController@updateRole']);
    Route::get('role-permission/{id}', ['as' => 'permission.edit', 'uses' => 'SettingController@getPermission']);
    Route::post('role-permission/{id}', ['as' => 'permission.update', 'uses' => 'SettingController@updatePermission']);
    Route::post('delete-role', ['as' => 'role.delete', 'uses' => 'SettingController@deleteRole']);

    Route::get('admin/list', ['as' => 'admin.list', 'uses' => 'SettingController@getAdminList']);
    Route::get('add-admin', ['as' => 'admin.add', 'uses' => 'SettingController@createAdmin']);
    Route::post('add-admin', ['as' => 'admin.store', 'uses' => 'SettingController@storeAdmin']);
    Route::get('edit-admin/{id}', ['as' => 'admin.edit', 'uses' => 'SettingController@editAdmin']);
    Route::post('edit-admin/{id}', ['as' => 'admin.update', 'uses' => 'SettingController@updateAdmin']);
    Route::post('delete-admin', ['as' => 'admin.delete', 'uses' => 'SettingController@deleteAdmin']);

    Route::get('get-role-datatable', ['as' => 'role.datatable', 'uses' => 'SettingController@getRoleDatatable']);
    Route::get('get-admin-datatable', ['as' => 'admin.datatable', 'uses' => 'SettingController@getAdminDatatable']);
});

#Product
Route::group(['prefix' => 'product'], function () {
    Route::name('product.')->group(function () {
        Route::get('list', [ProductController::class, 'index'])->name('list');
        Route::get('create', [ProductController::class, 'create'])->name('add');
        Route::post('create', [ProductController::class, 'store'])->name('store');
        Route::get('edit/{id}', [ProductController::class, 'edit'])->name('edit');
        Route::post('edit/{id}', [ProductController::class, 'update'])->name('update');
        Route::get('get-datatable', [ProductController::class, 'datatable'])->name('datatable');
        Route::post('status/{id}', [ProductController::class, 'status'])->name('status');
        Route::post('upload', [ProductController::class, 'upload'])->name('upload');
        Route::get('get-option', [ProductController::class, 'getOption'])->name('getoption');
        Route::post('delete', [ProductController::class, 'bulkDelete'])->name('bulkdelete');
    });

    #Product Category
    Route::get('category/list', [CategoryController::class, 'index'])->name('category.list');
    Route::get('category/datatable', [CategoryController::class, 'datatable'])->name('category.datatable');
    Route::get('category/add', [CategoryController::class, 'create'])->name('category.add');
    Route::post('category/add', [CategoryController::class, 'store'])->name('category.store');
    Route::get('category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::post('category/edit/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::post('category/delete', [CategoryController::class, 'delete'])->name('category.delete');
    Route::post('category/status/{id}', [CategoryController::class, 'status'])->name('category.status');

    #Product Options
    Route::get('option/list', [OptionController::class, 'index'])->name('option.list');
    Route::get('option/datatable', [OptionController::class, 'datatable'])->name('option.datatable');
    Route::get('option/add', [OptionController::class, 'create'])->name('option.add');
    Route::post('option/add', [OptionController::class, 'store'])->name('option.store');
    Route::get('option/edit/{id}', [OptionController::class, 'edit'])->name('option.edit');
    Route::post('option/edit/{id}', [OptionController::class, 'update'])->name('option.update');
    Route::post('option/delete', [OptionController::class, 'delete'])->name('option.delete');

    #Product Option Sets
    Route::get('option-set/list', [OptionSetController::class, 'index'])->name('optionset.list');
    Route::get('option-set/datatable', [OptionSetController::class, 'datatable'])->name('optionset.datatable');
    Route::get('option-set/add', [OptionSetController::class, 'create'])->name('optionset.add');
    Route::post('option-set/add', [OptionSetController::class, 'store'])->name('optionset.store');
    Route::get('option-set/edit/{id}', [OptionSetController::class, 'edit'])->name('optionset.edit');
    Route::post('option-set/edit/{id}', [OptionSetController::class, 'update'])->name('optionset.update');
    Route::post('option-set/delete', [OptionSetController::class, 'delete'])->name('optionset.delete');

    #Capacity
    Route::get('capacity/list', [CapacityController::class, 'index'])->name('capacity.list');
    Route::get('capacity/datatable', [CapacityController::class, 'datatable'])->name('capacity.datatable');
    Route::get('capacity/add', [CapacityController::class, 'create'])->name('capacity.add');
    Route::post('capacity/add', [CapacityController::class, 'store'])->name('capacity.store');
    Route::get('capacity/edit/{id}', [CapacityController::class, 'edit'])->name('capacity.edit');
    Route::post('capacity/edit/{id}', [CapacityController::class, 'update'])->name('capacity.update');
    Route::post('capacity/delete', [CapacityController::class, 'delete'])->name('capacity.delete');
});

Route::prefix('orders')->name('order.')->group(function () {
    Route::get('list', [OrderController::class, 'index'])->name('list');
    Route::get('edit/{id}', [OrderController::class, 'edit'])->name('edit');
});

Route::post('upload/content', ['as' => 'upload.content', 'uses' => 'App\Http\Controllers\UploadController@content']);
Route::get('logout', ['as' => 'logout', 'uses' => 'App\Http\Controllers\LoginController@logout']);
