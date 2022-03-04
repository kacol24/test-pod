<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductEditors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_editors', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('template_id');
            $table->string('state_id');
            $table->string('print_file');
            $table->string('proof_file');
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
        Schema::dropIfExists('product_editors');
    }
}
