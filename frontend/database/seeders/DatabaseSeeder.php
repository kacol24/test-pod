<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order\OrderStatus;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('order_status')->truncate();
        OrderStatus::insert(array(
            array(
                'title' => 'Pending'
            ),
            array(
                'title' => 'Paid'
            ),
            array(
                'title' => 'Canceled'
            ),
            array(
                'title' => 'In Progress'
            ),
            array(
                'title' => 'Undershipment'
            ),
            array(
                'title' => 'Delivered'
            ),
            array(
                'title' => 'Completed'
            )
        ));
    }
}
