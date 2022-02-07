<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Database\Seeders\FeaturePermissionSeeder;
use Database\Seeders\AdminSeeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call([
            FeaturePermissionSeeder::class,
        ]);
        $this->call(AdminSeeder::class);
        Model::reguard();
    }
}
