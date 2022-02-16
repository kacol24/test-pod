<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitleFieldToProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_product_options', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->dropColumn(['key', 'option_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_product_options', function (Blueprint $table) {
            $table->dropColumn(['title']);
            $table->string('key')->after('id');
            $table->integer('option_id')->after('key');
        });
    }
}
