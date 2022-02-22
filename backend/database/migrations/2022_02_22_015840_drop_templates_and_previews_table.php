<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTemplatesAndPreviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('master_product_templates');
        Schema::dropIfExists('master_product_previews');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('master_product_templates', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('file');
            $table->string('design_name');
            $table->string('page_name');
            $table->longText('customer_canvas');
            $table->timestamps();
        });

        Schema::create('master_product_previews', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('file');
            $table->string('preview_name');
            $table->string('thumbnail_name');
            $table->string('file_config')->nullable();
            $table->longText('customer_canvas');
            $table->timestamps();
        });
    }
}
