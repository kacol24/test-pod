<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id');
            $table->string('order_no');
            $table->double('total_amount');
            $table->double('shipping_fee');
            $table->double('insurance_fee')->default(0);
            $table->double('discount_voucher')->default(0);
            $table->double('pay_with_point')->default(0);
            $table->double('final_amount');
            $table->integer('status_id');
            $table->string('payment_method');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
