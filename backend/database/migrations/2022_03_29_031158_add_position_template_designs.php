<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPositionTemplateDesigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_template_designs', function (Blueprint $table) {
            $table->string('position')->default("down");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_template_designs', function (Blueprint $table) {
            $table->dropColumn("position");
        });
    }
}
