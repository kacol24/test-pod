<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_products', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_publish')->default(0);
            $table->boolean('manage_stock')->default(1);
            $table->boolean('is_featured')->default(0);
            $table->integer('order_weight');

            $table->string('title');
            $table->double('production_cost');
            $table->double('fulfillment_cost');
            $table->double('selling_price');
            $table->string('prism_id');
            $table->integer('production_time');
            $table->integer('fulfillment_time');
            $table->text('description');
            $table->text('size_chart')->nullable();

            $table->string('shape');
            $table->string('orientation');
            $table->string('unit');
            $table->boolean('enable_resize');
            $table->text('bleed');
            $table->text('safety_line');
            $table->text('template_width');
            $table->text('template_height');
            $table->text('ratio');

            $table->timestamps();
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
        Schema::dropIfExists('master_products');
    }
}
