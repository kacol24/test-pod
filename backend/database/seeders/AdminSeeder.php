<?php
namespace Database\Seeders;

use Illuminate\Http\File;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting\Admin;
use App\Models\Setting\FeaturePermission;
use App\Models\Setting\RolePermission;
use App\Models\Setting\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('roles')->truncate();
    DB::table('admins')->truncate();

    $insert = array(
      array('name' => 'Super Admin'),
      array('name' => 'Admin'),
    );
    Role::insert($insert);

    $admin = Admin::create(array(
      'name' => 'Phendy',
      'email' => 'phendy@goodcommerce.co',
      'password' => Hash::make('goodcommerce'),
      'remember_token' => '',
      'role_id' => 1
    ));

    $permissions = FeaturePermission::all();
    foreach($permissions as $permission) {
      RolePermission::create(array(
        'role_id' => $admin->role_id,
        'permission_id' => $permission->id
      ));
    }
  }
}
