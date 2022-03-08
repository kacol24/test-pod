<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPlatform extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_platforms', function (Blueprint $table) {
            $table->integer('product_id');
            $table->string('platform');
            $table->string('platform_product_id');
            $table->softDeletes();
            $table->unique(array('product_id', 'platform'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_platforms');
    }
}
