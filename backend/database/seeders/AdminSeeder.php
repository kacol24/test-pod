<?php

namespace Database\Seeders;

use App\Models\Setting\Admin;
use App\Models\Setting\FeaturePermission;
use App\Models\Setting\Role;
use App\Models\Setting\RolePermission;
use Illuminate\Database\Seeder;
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

        $insert = [
            ['name' => 'Super Admin'],
            ['name' => 'Admin'],
        ];
        Role::insert($insert);

        $admin = Admin::create([
            'name'           => 'Phendy',
            'email'          => 'phendy@goodcommerce.co',
            'password'       => Hash::make('goodcommerce'),
            'remember_token' => '',
            'role_id'        => 1,
        ]);

        RolePermission::where(['role_id' => $admin->id])->delete();

        $permissions = FeaturePermission::all();
        foreach ($permissions as $permission) {
            RolePermission::create([
                'role_id'       => $admin->role_id,
                'permission_id' => $permission->id,
            ]);
        }
    }
}
