<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('parent_id')->default(0);
            $table->integer('is_active')->default(1);
            $table->integer('is_featured')->default(0);
            $table->integer('order_weight')->default(0);
            $table->string('image');
            $table->timestamps();
            $table->softDeletes();
            $table->index('deleted_at','order_weight');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('master_categories');
    }
}
