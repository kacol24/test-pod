<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeWeightAndDimensionFieldsToDouble extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_product_skus', function (Blueprint $table) {
            $table->float('weight')->change();
            $table->float('width')->change();
            $table->float('length')->change();
            $table->float('height')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_product_skus', function (Blueprint $table) {
            $table->integer('weight')->change();
            $table->integer('width')->change();
            $table->integer('length')->change();
            $table->integer('height')->change();
        });
    }
}
