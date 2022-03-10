<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderShippings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_shippings', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->string('name');
            $table->string('phone');
            $table->longText('address');
            $table->string('country');
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->string('postal_code');
            $table->string('shipping_code');
            $table->string('shipping_type');
            $table->string('awb')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_shippings');
    }
}
