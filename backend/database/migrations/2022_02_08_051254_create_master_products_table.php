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
            $table->string('prism_id');
            $table->integer('production_time');
            $table->integer('fulfillment_time');
            $table->text('description');
            $table->text('size_chart')->nullable();
            $table->double('threshold')->default(0);

            $table->string('shape')->nullable();
            $table->string('orientation')->nullable();
            $table->string('unit')->nullable();
            $table->boolean('enable_resize')->nullable();
            $table->text('bleed')->nullable();
            $table->text('safety_line')->nullable();
            $table->text('template_width')->nullable();
            $table->text('template_height')->nullable();
            $table->text('ratio')->nullable();

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
