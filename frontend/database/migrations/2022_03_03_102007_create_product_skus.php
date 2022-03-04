<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSkus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_skus', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('option_detail_key1');
            $table->string('option_detail_key2');
            $table->string('sku_code');
            $table->integer('stock');
            $table->double('price');
            $table->integer('weight');
            $table->integer('width');
            $table->integer('length');
            $table->integer('height');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_skus');
    }
}
