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
                'title' => 'Pending' #1
            ),
            array(
                'title' => 'Paid' #2
            ),
            array(
                'title' => 'Canceled' #3
            ),
            array(
                'title' => 'In Progress' #4
            ),
            array(
                'title' => 'Undershipment' #5
            ),
            array(
                'title' => 'Delivered' #6
            ),
            array(
                'title' => 'Completed' #7
            )
        ));
    }
}
