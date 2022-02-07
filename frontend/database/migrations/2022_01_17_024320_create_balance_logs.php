<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('ref_id');
            $table->string('store_id');
            $table->enum('type',['topup','order']);
            $table->integer('last');
            $table->integer('given');
            $table->integer('current');
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
        Schema::dropIfExists('balance_logs');
    }
}
