<?php

namespace Database\Seeders;

use App\Models\Setting\Feature;
use App\Models\Setting\FeaturePermission;
use Illuminate\Database\Seeder;
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

        $features = [
            'product' => 'Product',
            'banner'  => 'Banner',
            'admin'   => 'Admin',
            'role'    => 'Role',
            'category'  => 'Product Category Menu',
            'option'    => 'Product Option Menu',
            'optionset' => 'Product Option Set Menu',
            'capacity'  => 'Capacity Menu',
        ];

        foreach ($features as $name => $feature) {
            $$name = $entity = Feature::create(['title' => $feature]);
        }

        $permissions = [
            [
                'action_name' => 'product.list',
                'feature_id'  => $product->id,
                'title'       => 'View Product List',
            ],
            [
                'action_name' => 'product.add',
                'feature_id'  => $product->id,
                'title'       => 'Add Product',
            ],
            [
                'action_name' => 'product.edit',
                'feature_id'  => $product->id,
                'title'       => 'Edit Product',
            ],
            [
                'action_name' => 'product.bulkdelete',
                'feature_id'  => $product->id,
                'title'       => 'Delete Product',
            ],
            [
                'action_name' => 'banner.index',
                'feature_id'  => $banner->id,
                'title'       => 'View Banner',
            ],
            [
                'action_name' => 'admin.list',
                'feature_id'  => $admin->id,
                'title'       => 'View Admin',
            ],
            #Banner
            [
                'action_name' => 'banner.create',
                'feature_id'  => $banner->id,
                'title'       => 'Add Banner',
            ],
            [
                'action_name' => 'banner.edit',
                'feature_id'  => $banner->id,
                'title'       => 'Edit Banner',
            ],
            [
                'action_name' => 'banner.destroy',
                'feature_id'  => $banner->id,
                'title'       => 'Delete Banner',
            ],
            [
                'action_name' => 'admin.add',
                'feature_id'  => $admin->id,
                'title'       => 'Add Admin',
            ],
            [
                'action_name' => 'admin.edit',
                'feature_id'  => $admin->id,
                'title'       => 'Edit Admin',
            ],
            [
                'action_name' => 'admin.delete',
                'feature_id'  => $admin->id,
                'title'       => 'Delete Admin',
            ],
            #Roles
            [
                'action_name' => 'role.list',
                'feature_id'  => $role->id,
                'title'       => 'View Role',
            ],
            [
                'action_name' => 'role.add',
                'feature_id'  => $role->id,
                'title'       => 'Add Role',
            ],
            [
                'action_name' => 'role.edit',
                'feature_id'  => $role->id,
                'title'       => 'Edit Role',
            ],
            [
                'action_name' => 'permission.edit',
                'feature_id'  => $role->id,
                'title'       => 'Edit Role Permission',
            ],
            [
                'action_name' => 'role.delete',
                'feature_id'  => $role->id,
                'title'       => 'Delete Role',
            ],

            [
                'action_name' => 'category.list',
                'feature_id'  => $category->id,
                'title'       => 'View Category',
            ],
            [
                'action_name' => 'category.add',
                'feature_id'  => $category->id,
                'title'       => 'Add Category',
            ],
            [
                'action_name' => 'category.edit',
                'feature_id'  => $category->id,
                'title'       => 'Edit Category',
            ],
            [
                'action_name' => 'category.delete',
                'feature_id'  => $category->id,
                'title'       => 'Delete Category',
            ],
            [
                'action_name' => 'option.list',
                'feature_id'  => $option->id,
                'title'       => 'View Option',
            ],
            [
                'action_name' => 'option.add',
                'feature_id'  => $option->id,
                'title'       => 'Add Option',
            ],
            [
                'action_name' => 'option.edit',
                'feature_id'  => $option->id,
                'title'       => 'Add Option',
            ],
            [
                'action_name' => 'option.delete',
                'feature_id'  => $option->id,
                'title'       => 'Delete Option',
            ],
            [
                'action_name' => 'optionset.list',
                'feature_id'  => $optionset->id,
                'title'       => 'View Option Set',
            ],
            [
                'action_name' => 'optionset.add',
                'feature_id'  => $optionset->id,
                'title'       => 'Add Option Set',
            ],
            [
                'action_name' => 'optionset.edit',
                'feature_id'  => $optionset->id,
                'title'       => 'Edit Option Set',
            ],
            [
                'action_name' => 'optionset.delete',
                'feature_id'  => $optionset->id,
                'title'       => 'Delete Option Set',
            ],
            #Capacity
            [
                'action_name' => 'capacity.list',
                'feature_id'  => $capacity->id,
                'title'       => 'View Capacity',
            ],
            [
                'action_name' => 'capacity.add',
                'feature_id'  => $capacity->id,
                'title'       => 'Add Capacity',
            ],
            [
                'action_name' => 'capacity.edit',
                'feature_id'  => $capacity->id,
                'title'       => 'Edit Capacity',
            ],
            [
                'action_name' => 'capacity.delete',
                'feature_id'  => $capacity->id,
                'title'       => 'Delete Capacity',
            ],
        ];

        FeaturePermission::insert($permissions);
    }
}
