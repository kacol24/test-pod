<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPreviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_product_previews', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('file');
            $table->string('preview_name');
            $table->string('thumbnail_name');
            $table->string('file_config');
            $table->longText('customer_canvas');
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
        Schema::dropIfExists('master_product_previews');
    }
}
