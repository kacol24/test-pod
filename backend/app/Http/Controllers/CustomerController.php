<?php 

namespace Core\Http\Backend;

use Validator, Cache;
use Theme, View, Form, Excel;
use Core\Http\Controller;
use Illuminate\Http\Request;
use Core\Export;
use Core\Models\User\User;
use Core\Models\User\UserGroup;
use Core\Repositories\Facades\VisitorRepository;
use Core\Http\Backend\Resources\User as UserResource;
use Core\Http\Backend\Resources\UserGroup as UserGroupResource;
use Core\Models\User\UserPointLog;
use Core\Models\User\UserPointContent;
use Core\Models\Order\Order;
use Core\Http\Backend\Resources\UserOrder as UserOrderResource;
use Core\Http\Backend\Resources\UserPoint as UserPointResource;

class CustomerController extends Controller {

  public function index() {
    $data = array(
      'page_title' => 'Customer Lists',
      'active' => 'customer',
      'groups' => UserGroup::where('store_id', session('store')->id)->get()
    );

    return $this->view('backend/customer/list', $data);
  }

  public function groupList() {
    $data = array(
      'page_title' => 'Customer Group Lists',
      'active' => 'customer'
    );

    return $this->view('backend/customer/grouplist', $data);
  }

  public function datatable(Request $request) {
    $search = $request->search;
    $sorting = 'updated_at';
    $order = $request->order;
    $group = $request->group;

    if($request->has('sort')) {
      $sorting = $request->sort;
    }

    return UserResource::collection(User::when(! empty($search), function ($query) use ($search) {
      return $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
    })->when($group!=0, function ($query) use ($group) {
      return $query->where('group_id', $group);
    })->where('store_id', session('store')->id)->orderBy($sorting, $order)->paginate($request->limit));
  }

  public function groupDatatable(Request $request) {
    $search = $request->search;
    $sorting = 'updated_at';
    $order = $request->order;

    if($request->has('sort')) {
      $sorting = $request->sort;
    }
    
    return UserGroupResource::collection(UserGroup::when(! empty($search), function ($query) use ($search) {
      return $query->where('name', 'like', "%{$search}%");
    })->where('store_id', session('store')->id)->orderBy($sorting, $order)->paginate($request->limit));
  }

  public function createGroup() {
    $data = array(
      'page_title' => 'Add Customer Group',
      'active' => 'customer'
    );

    return $this->view('backend/customer/addgroup', $data);
  }

  public function storeGroup(Request $request) {
    $request->flash();

    $validator = Validator::make($request->all(), [
      'name' => 'required'
    ]);

    if ($validator->fails()) {
      return redirect()->route('group.add')->withErrors($validator)->withInput();
    }

    $input = $request->all();
    $input['store_id'] = session('store')->id;
    $input['discount'] = intval($input['discount']);
    UserGroup::create($input);
    Cache::tags('users'.session('store')->id.app_key())->flush();
    return redirect()->route('group.list')->with('status', 'Success add group');
  }

  public function editGroup($id) {
    $data = array(
      'page_title' => 'Edit Customer Group',
      'active' => 'customer',
      'group' => UserGroup::where('id', $id)->where('store_id', session('store')->id)->first()
    );

    return $this->view('backend/customer/editgroup', $data);
  }

  public function updateGroup(Request $request,$id) {
    $request->flash();

    $validator = Validator::make($request->all(), [
      'name' => 'required'
    ]);

    if ($validator->fails()) {
      return redirect()->route('group.edit',$id)->withErrors($validator)->withInput();
    }
    
    UserGroup::where('id',$id)->where('store_id', session('store')->id)->update(array('name' => $request->name, 'discount' => intval($request->discount)));
    Cache::tags('users'.session('store')->id.app_key())->flush();

    return redirect()->route('group.list')->with('status', 'Success edit group');
  }

  public function deleteGroup(Request $request) {
    if(is_array($request->input('ids')))
      UserGroup::whereIn('id', $request->input('ids'))->where('store_id', session('store')->id)->delete();
    else
      UserGroup::whereIn('id', json_decode($request->input('ids'), true))->where('store_id', session('store')->id)->delete();

    Cache::tags('users'.session('store')->id.app_key())->flush();

    if($request->input('back_url'))
      return redirect($request->input('back_url'));
  }

  public function updateCustomerGroup(Request $request) {
    User::where('id', $request->input('id'))->where('store_id', session('store')->id)->update(array('group_id' => $request->input('group_id')));
    Cache::tags('products'.session('store')->id.app_key())->flush();
    Cache::tags('users'.session('store')->id.app_key())->flush();
  }

  public function updateCustomerStatus(Request $request) {
    User::where('id', $request->input('id'))->where('store_id', session('store')->id)->update(array('status' => $request->input('status')));
    Cache::tags('users'.session('store')->id.app_key())->flush();
  }

  public function export(Request $request) {
    $data = array();

    if($request->input('group')) {
      $customers = User::where('group_id', $request->input('group'))->where('store_id', session('store')->id)->get();
    }else {
      $customers = User::where('store_id', session('store')->id)->get();  
    }

    $data[] = array('name', 'email', 'gender', 'dob', 'member group');
    foreach($customers as $customer) {
      $data[] = array($customer->name, $customer->email,ucfirst($customer->gender), $customer->dob, $customer->group->name);
    }
    return Excel::download(new Export($data), 'customer.xlsx');
  }

  public function dashboard(Request $request, $id) {
    $user = User::where('id',$id)->where('store_id', session('store')->id)->first();
    $data = array(
      'page_title' => 'Customer Dashboard',
      'active' => 'customer',
      'total_purchase' => VisitorRepository::getTotalPurchase($user->email),
      'transaction' => VisitorRepository::getTransactionCount($user->email),
      'user' => $user
    );
    return $this->view('backend/customer/dashboard', $data);
  }

  public function topUpPoint(Request $request, $id) {
    $user = User::where('id',$id)->where('store_id', session('store')->id)->first();
    $data = array(
      'page_title' => 'Point',
      'active' => 'customer',
      'user' => $user
    );
    return $this->view('backend/customer/topuppoint', $data); 
  }

  public function savePoint(Request $request, $id) {
    if(session('store')->multi_language == 1) {
      $validation = array(
        'description_id' => 'required',
        'description_en' => 'required',
      );
    }else if(session('store')->default_language == 'en') {
      $validation = array(
        'description_en' => 'required',
      );
    }else if(session('store')->default_language == 'id') {
      $validation = array(
        'description_id' => 'required',
      );
    }

    $validation['amount'] = 'required|integer';

    $validator = Validator::make($request->all(), $validation);
    if ($validator->fails()) {
      return redirect()->route('customer.topuppoint', $id)->withErrors($validator)->withInput();
    }

    $user = User::where('id',$id)->where('store_id', session('store')->id)->first();
    $type = $request->type;
    if($type == 'Get') {
      $current_point = $user->point+$request->input('amount');
    }else if($type == 'Use'){
      $current_point = $user->point-$request->input('amount');
    }if($type == 'Adjust') {
      $current_point = $request->input('amount');
      if($user->point > $request->input('amount')) {
        $type = 'Use';
      }else {
        $type = 'Get';
      }
    }
    $log = UserPointLog::create(array(
      "order_id" => 0,
      "user_id" => $user->id,
      "type" => $type,
      "last_point" => $user->point,
      "given_point" => $request->input('amount'),
      "current_point" => $current_point,
    ));

    UserPointContent::create(array(
      'log_id' => $log->id,
      'keyword' => 'description',
      'language' => 'id',
      'value' => $request->input('description_id')
    ));

    UserPointContent::create(array(
      'log_id' => $log->id,
      'keyword' => 'description',
      'language' => 'en',
      'value' => $request->input('description_en')
    ));

    $user->point = $current_point;
    $user->save();
    insert_admin_log('Melakukan top up point untuk user id '.$user->id.' sebesar '.$request->input('amount'));
    return redirect()->route('customer.dashboard', $user->id)->with('status', 'Success topup point');
  }

  public function datatableOrder(Request $request, $user_id) {
    $search = $request->search;
    $sorting = 'created_at';
    $order = $request->order;

    if($request->has('sort')) {
      $sorting = $request->sort;
    }

    return UserOrderResource::collection(Order::where('customer_id', $user_id)->where('store_id', session('store')->id)->orderBy($sorting, $order)->paginate($request->limit));
  }

  public function datatablePoint(Request $request, $user_id) {
    $search = $request->search;
    $sorting = 'created_at';
    $order = $request->order;

    if($request->has('sort')) {
      $sorting = $request->sort;
    }

    return UserPointResource::collection(UserPointLog::where('user_id', $user_id)->orderBy($sorting, $order)->paginate($request->limit));
  }
}