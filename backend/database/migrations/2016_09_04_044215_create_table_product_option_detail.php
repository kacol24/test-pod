<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductOptionDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_product_option_details', function (Blueprint $table) {
            $table->id();
            $table->integer('option_id');
            $table->string('title');
            $table->string('key');
            $table->string('image')->nullable();
            $table->index('option_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('master_product_option_details');
    }
}
