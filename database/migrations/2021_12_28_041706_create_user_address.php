<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_address', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('title')
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->longText('address');
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('subdistrict');
            $table->string('postcode');
            $table->integer('is_primary');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('geolabel')->nullable();
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
        Schema::dropIfExists('user_address');
    }
}
