<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting\Feature;
use App\Models\Setting\FeatureContent;
use App\Models\Setting\FeaturePermission;
use Illuminate\Support\Facades\DB;

class FeaturePermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('features')->truncate();
    DB::table('feature_contents')->truncate();
    DB::table('feature_permissions')->truncate();

    $features = array(
      'banner' => 'Banner',
      'admin' => 'Admin',
      'role' => 'Role'
    );

    foreach($features as $name => $feature) {
      $$name = $entity = Feature::create(array('title' => $feature));
    }

    $permissions = array(
      array(
        'action_name' => 'banner.index',
        'feature_id' => $banner->id,
        'title' => 'View Banner'
      ),
      array(
        'action_name' => 'admin.list',
        'feature_id' => $admin->id,
        'title' => 'View Admin'
      ),
      #Banner
      array(
        'action_name' => 'banner.create',
        'feature_id' => $banner->id,
        'title' => 'Add Banner'
      ),
      array(
        'action_name' => 'banner.edit',
        'feature_id' => $banner->id,
        'title' => 'Edit Banner'
      ),
      array(
        'action_name' => 'banner.destroy',
        'feature_id' => $banner->id,
        'title' => 'Delete Banner'
      ),
      array(
        'action_name' => 'admin.add',
        'feature_id' => $admin->id,
        'title' => 'Add Admin'
      ),
      array(
        'action_name' => 'admin.edit',
        'feature_id' => $admin->id,
        'title' => 'Edit Admin'
      ),
      array(
        'action_name' => 'admin.delete',
        'feature_id' => $admin->id,
        'title' => 'Delete Admin'
      ),
        #Roles
        array(
            'action_name' => 'role.list',
            'feature_id'  => $role->id,
            'title'       => 'View Role'
        ),
        array(
            'action_name' => 'role.add',
            'feature_id'  => $role->id,
            'title'       => 'Add Role'
        ),
        array(
            'action_name' => 'role.edit',
            'feature_id'  => $role->id,
            'title'       => 'Edit Role'
        ),
        array(
            'action_name' => 'permission.edit',
            'feature_id'  => $role->id,
            'title'       => 'Edit Role Permission'
        ),
        array(
            'action_name' => 'role.delete',
            'feature_id'  => $role->id,
            'title'       => 'Delete Role'
        )
    );

    FeaturePermission::insert($permissions);
  }
}
