<?php 

namespace App\Http\Controllers;

use Validator, Image, Cache, DB;
use Theme, View, Form, Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting\Role;
use Illuminate\Validation\Rule;
use App\Models\Setting\RolePermission;
use App\Models\Setting\Feature;
use App\Models\Setting\Admin;
use App\Http\Controllers\Resources\Role as RoleResource;
use App\Http\Controllers\Resources\Admin as AdminResource;

class SettingController extends Controller {
  public function getRoleList() {
    $data = array(
      'page_title' => 'Roles & Permissions',
      'active' => 'setting'
    );
    return view('setting/role/list', $data);
  }

  public function getAdminList() {
    $data = array(
      'page_title' => 'Admin List',
      'active' => 'setting'
    );
    return view('setting/admin/list', $data);
  }

  public function getRoleDatatable(Request $request) {
    $search = $request->search;
    $sorting = 'updated_at';
    $order = $request->order;

    if($request->has('sort')) {
      $sorting = $request->sort;
    }
    
    return RoleResource::collection(Role::when(! empty($search), function ($query) use ($search) {
      return $query->where('name', 'like', "%{$search}%");
    })->orderBy($sorting, $order)->paginate($request->limit));
  }

  public function getAdminDatatable(Request $request) {
    $search = $request->search;
    $sorting = 'updated_at';
    $order = $request->order;

    if($request->has('sort')) {
      $sorting = $request->sort;
    }
    
    return AdminResource::collection(Admin::join('roles','roles.id','=','admins.role_id')->when(! empty($search), function ($query) use ($search) {
      return $query->where('admins.name', 'like', "%{$search}%");
    })->orderBy($sorting, $order)->select('admins.*','roles.name as role')->paginate($request->limit));
  }

  public function createRole() {
    $data = array(
      'page_title' => 'Create Role',
      'active' => 'setting'
    );

    return view('setting/role/add', $data);
  }

  public function createAdmin() {
    $data = array(
      'page_title' => 'Create Admin',
      'active' => 'setting',
      'roles' => Role::all()
    );

    return view('setting/admin/add', $data);
  }

  public function storeRole(Request $request) {
    $request->flash();
    $validation = ['name' => 'required'];
    $validator = Validator::make($request->all(), $validation);
    if ($validator->fails()) {
      return redirect()->route('role.add')->withErrors($validator)->withInput();
    }

    Role::create(array('name' => $request->name));
    return redirect()->route('role.list')->with('status', 'Success add role');
  }

  public function storeAdmin(Request $request) {
    $request->flash();

    $validation = [
      'name' => 'required',
      'email' => ['required','email',Rule::unique('admins')],
      'password' => 'required',
      'role' => 'required'
    ];
    $validator = Validator::make($request->all(), $validation);
    if ($validator->fails()) {
      return redirect()->route('admin.add')->withErrors($validator)->withInput();
    }

    $input = $request->all();
    Admin::create(array(
      'name' => $input['name'],
      'email' => $input['email'], 
      'password' => Hash::make($input['password']), 
      'role_id' => $input['role'],
    ));
    return redirect()->route('admin.list')->with('status', 'Success add admin');
  }

  public function editRole($id) {
    $data = array(
      'page_title' => 'Edit Role',
      'active' => 'setting',
      'role' => Role::find($id)
    );

    return view('setting/role/edit', $data);
  }

  public function editAdmin($id) {
    $data = array(
      'page_title' => 'Edit Admin',
      'active' => 'setting',
      'roles' => Role::all(),
      'admin' => Admin::find($id)
    );

    return view('setting/admin/edit', $data);
  }

  public function updateRole(Request $request, $id) {
    $request->flash();
    $validation = ['name' => 'required'];
    $validator = Validator::make($request->all(), $validation);
    $role = Role::find($id);
    $role->name = $request->name;
    $role->save();
    return redirect()->route('role.list')->with('status', 'Success edit role');
  }

  public function updateAdmin(Request $request, $id) {
    $request->flash();

    $validation = [
      'name' => 'required',
      'email' => 'required',
      'role' => 'required'
    ];
    
    $validator = Validator::make($request->all(), $validation);

    $input = $request->all();

    $admin = Admin::find($id);
    $admin->name = $input['name'];
    $admin->email = $input['email'];
    if(isset($input['password']) && strlen($input['password'])>1) {
      $admin->password = Hash::make($input['password']);
    }
    $admin->role_id = $input['role'];
    $admin->save();
    return redirect()->route('admin.list')->with('status', 'Success edit admin');
  }

  public function deleteRole(Request $request) {
    if(is_array($request->input('ids')))
      Role::whereIn('id', $request->input('ids'))->delete();
    else
      Role::whereIn('id', json_decode($request->input('ids'), true))->delete();

    if($request->input('back_url'))
      return redirect($request->input('back_url'));
  }

  public function deleteAdmin(Request $request) {
    if(is_array($request->input('ids')))
      Admin::whereIn('id', $request->input('ids'))->delete();
    else
      Admin::whereIn('id', json_decode($request->input('ids'), true))->delete();
    
    if($request->input('back_url'))
      return redirect($request->input('back_url'));
  }

  public function getPermission($id) {
    $role = Role::find($id);
    $data = array(
      'page_title' => 'Role Permissions',
      'active' => 'setting',
      'features' => Feature::orderBy('title','asc')->select('features.*')->get(),
      'id' => $role->id,
      'role_permissions' => RolePermission::join('roles','roles.id','=','role_permissions.role_id')->where('role_id', $role->id)->pluck('permission_id')->toArray()
    );

    return view('setting/role/permission', $data);
  }

  public function updatePermission(Request $request, $id) {
    $role = Role::find($id);
    RolePermission::where('role_id',$role->id)->delete();
    foreach($request->input('permission') as $permission) {
      RolePermission::create(array('role_id' => $role->id,'permission_id' => $permission));
    }
    session(['admin' => Admin::find(session('admin')->id)]);
    return redirect()->route('role.list')->with('status', 'Success update permissions');
  }
}