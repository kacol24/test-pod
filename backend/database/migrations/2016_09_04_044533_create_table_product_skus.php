<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTableProductSkus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_product_skus', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('option_detail_key1')->default(null)->nullable();
            $table->string('option_detail_key2')->default(null)->nullable();
            $table->string('sku_code')->nullable();
            $table->integer('weight');
            $table->integer('width');
            $table->integer('length');
            $table->integer('height');
            $table->integer('image_no')->default(0);
            $table->softDeletes();
            $table->index('deleted_at');
            $table->index('product_id');
            $table->index(['deleted_at', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('master_product_skus');
    }
}
