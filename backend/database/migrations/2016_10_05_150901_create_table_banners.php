<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBanners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url')->nullable();
            $table->tinyInteger('is_active');
            $table->datetime('start_date');
            $table->datetime('end_date')->nullable();
            $table->string('type');
            $table->string('desktop_image');
            $table->string('mobile_image');
            $table->tinyInteger('order_weight')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index('is_active');
            $table->index('start_date');
            $table->index('end_date');
            $table->index('type');
            $table->index('deleted_at');
            $table->index('order_weight');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('banners');
    }
}
