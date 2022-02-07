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

use App\Http\Controllers\BusinessController;

use App\Notifications\FirstLogin;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;

Route::get('/notif', function () {
    $user = User::find(1);
    return (new FirstLogin($user))
                ->toMail('phendy@goodcommerce.co');
});

Route::get('/visit', function (Request $request) {
    // $token = $request->bearerToken();
    $bearerToken = $request->bearerToken();
    $parsedJwt = (new \Lcobucci\JWT\Parser())->parse($bearerToken);
    if ($parsedJwt->hasHeader('jti')) {
      $tokenId = $parsedJwt->getHeader('jti');
    } elseif ($parsedJwt->hasClaim('jti')) {
      $tokenId = $parsedJwt->getClaim('jti');
    }

    Visitor::create(array(
      'business_id' => 1,
      'token' => $tokenId,
      'date' => "2021-08-26"
    ));
})->name('visit');

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

#Customer
Route::group(['prefix' => 'user', 'namespace' => 'App\Http\Controllers'], function () {
  Route::get('/', ['as' => 'user.list', 'uses' => 'CustomerController@index']);
  Route::get('/dashboard/{user_id}', ['as' => 'user.dashboard', 'uses' => 'CustomerController@dashboard']);
  Route::get('get-datatable', ['as' => 'user.datatable', 'uses' => 'CustomerController@datatable']);
  Route::get('/export', ['as' => 'user.export', 'uses' => 'CustomerController@export']);
  Route::post('/update-group', ['as' => 'user.updategroup', 'uses' => 'CustomerController@updateCustomerGroup']);
  Route::post('/update-status', ['as' => 'user.updatestatus', 'uses' => 'CustomerController@updateCustomerStatus']);
  Route::get('/topup-point/{user_id}', ['as' => 'user.topuppoint', 'uses' => 'CustomerController@topUpPoint']);
  Route::post('/topup-point/{user_id}', ['as' => 'user.topuppoint.store', 'uses' => 'CustomerController@savePoint']);
  Route::get('get-datatable-order/{id}', ['as' => 'user.order.datatable', 'uses' => 'CustomerController@datatableOrder']);
  Route::get('get-datatable-point/{id}', ['as' => 'user.point.datatable', 'uses' => 'CustomerController@datatablePoint']);

  Route::get('/groups', ['as' => 'group.list', 'uses' => 'CustomerController@groupList']);
  Route::get('/groups/get-datatable', ['as' => 'group.datatable', 'uses' => 'CustomerController@groupDatatable']);
  Route::get('/groups/add', ['as' => 'group.add', 'uses' => 'CustomerController@createGroup']);
  Route::post('/groups/add', ['as' => 'group.store', 'uses' => 'CustomerController@storeGroup']);
  Route::get('/groups/edit/{id}', ['as' => 'group.edit', 'uses' => 'CustomerController@editGroup']);
  Route::post('/groups/edit/{id}', ['as' => 'group.update', 'uses' => 'CustomerController@updateGroup']);
  Route::post('/groups/delete', ['as' => 'group.delete', 'uses' => 'CustomerController@deleteGroup']);
});

#Dashboard
Route::group(['prefix' => 'dashboard', 'namespace' => 'App\Http\Controllers'], function () {
  Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@getDashboard']);
  Route::get('/get-header', ['as' => 'dashboard.getheader', 'uses' => 'DashboardController@getHeader']);
  Route::get('/get-data-product', ['as' => 'dashboard.getdataproduct', 'uses' => 'DashboardController@getDataBusiness']);
  Route::get('/get-best-seller', ['as' => 'dashboard.getbestseller', 'uses' => 'DashboardController@getBestSeller']);
  Route::get('/get-most-viewer', ['as' => 'dashboard.getmostviewer', 'uses' => 'DashboardController@getMostViewer']);
  Route::get('/get-sales-data', ['as' => 'dashboard.getsalesdata', 'uses' => 'DashboardController@getSalesData']);
  Route::get('/get-data-status', ['as' => 'dashboard.getdatastatus', 'uses' => 'DashboardController@getDataStatus']);
  Route::get('/get-customer', ['as' => 'dashboard.getcustomer', 'uses' => 'DashboardController@getCustomer']);
  Route::get('/get-top-buyer', ['as' => 'dashboard.gettopbuyer', 'uses' => 'DashboardController@getTopBuyer']);
  Route::get('/get-top-city', ['as' => 'dashboard.gettopcity', 'uses' => 'DashboardController@getTopCity']);

  Route::get('/get-impact-report', 'Report\ImpactReportController')->name('dashboard.impact');
  Route::get('/get-members-report', 'Report\MemberReportController')->name('dashboard.members');
  Route::get('/get-business-report', 'Report\BusinessReportController')->name('dashboard.business');

  Route::get('/analytics', ['as' => 'analytics', 'uses' => 'DashboardController@analytics']);
});

#Business
Route::group(['prefix' => 'business', 'namespace' => 'App\Http\Controllers'], function () {
  Route::get('list', ['as' => 'business.list', 'uses' => 'BusinessController@index']);
  Route::get('request/list', ['as' => 'business.request.list', 'uses' => 'BusinessController@getRequestList']);
  Route::get('get-datatable', ['as' => 'business.datatable', 'uses' => 'BusinessController@datatable']);

  Route::get('edit/{id}', ['as' => 'business.edit', 'uses' => 'BusinessController@edit']);
  Route::post('edit/{id}', ['as' => 'business.update', 'uses' => 'BusinessController@update']);
  Route::post('delete', ['as' => 'business.bulkdelete', 'uses' => 'BusinessController@bulkDelete']);
  Route::get('delete/{id}', ['as' => 'business.delete', 'uses' => 'BusinessController@deleteBusiness']);
  Route::post('status/{id}', ['as' => 'business.status', 'uses' => 'BusinessController@status']);

  Route::post('publish', ['as' => 'business.publish', 'uses' => 'BusinessController@publish']);
  Route::post('unpublish', ['as' => 'business.unpublish', 'uses' => 'BusinessController@unpublish']);

  Route::get('send-statistics/{id?}', ['as' => 'business.statistics', 'uses' => 'BusinessController@sendStatistics']);


  #Business Category
  Route::get('category/list', ['as' => 'category.list', 'uses' => 'CategoryController@index']);
  Route::get('category/datatable', ['as' => 'category.datatable', 'uses' => 'CategoryController@datatable']);
  Route::get('category/add', ['as' => 'category.add', 'uses' => 'CategoryController@create']);
  Route::post('category/add', ['as' => 'category.store', 'uses' => 'CategoryController@store']);
  Route::get('category/edit/{id}', ['as' => 'category.edit', 'uses' => 'CategoryController@edit']);
  Route::post('category/edit/{id}', ['as' => 'category.update', 'uses' => 'CategoryController@update']);
  Route::post('category/delete', ['as' => 'category.delete', 'uses' => 'CategoryController@delete']);
  Route::post('category/status/{id}', ['as' => 'category.status', 'uses' => 'CategoryController@status']);
  # Export/Import
    Route::post('import', [BusinessController::class, 'import'])->name('business.import');
    Route::get('export', [BusinessController::class, 'export'])->name('business.export');
});

Route::group(['prefix' => 'contact-submissions', 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('list', ['as' => 'contact_submissions.list', 'uses' => 'ContactSubmissionController@index']);
    Route::get('get-datatable', ['as' => 'contact_submissions.datatable', 'uses' => 'ContactSubmissionController@datatable']);
    Route::post('delete', ['as' => 'contact_submissions.delete', 'uses' => 'ContactSubmissionController@delete']);
});

#Business
Route::group(['prefix' => 'treasure-arise', 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('list', ['as' => 'treasure_arise.list', 'uses' => 'TreasureAriseController@index']);
    Route::get('request/list', ['as' => 'treasure_arise.request.list', 'uses' => 'TreasureAriseController@getRequestList']);
    Route::get('get-datatable', ['as' => 'treasure_arise.datatable', 'uses' => 'TreasureAriseController@datatable']);

    Route::get('edit/{id}', ['as' => 'treasure_arise.edit', 'uses' => 'TreasureAriseController@edit']);
    Route::post('edit/{id}', ['as' => 'treasure_arise.update', 'uses' => 'TreasureAriseController@update']);
    Route::post('delete', ['as' => 'treasure_arise.bulkdelete', 'uses' => 'TreasureAriseController@bulkDelete']);
    Route::get('delete/{id}', ['as' => 'treasure_arise.delete', 'uses' => 'TreasureAriseController@deleteBusiness']);
    Route::post('status/{id}', ['as' => 'treasure_arise.status', 'uses' => 'TreasureAriseController@status']);

    Route::post('publish', ['as' => 'treasure_arise.publish', 'uses' => 'TreasureAriseController@publish']);
    Route::post('unpublish', ['as' => 'treasure_arise.unpublish', 'uses' => 'TreasureAriseController@unpublish']);
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

Route::post('upload/content', ['as' => 'upload.content', 'uses' => 'App\Http\Controllers\UploadController@content']);
Route::get('help', ['as' => 'help', 'uses' => 'App\Http\Controllers\LoginController@help']);
Route::get('logout', ['as' => 'logout', 'uses' => 'App\Http\Controllers\LoginController@logout']);
