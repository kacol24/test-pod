<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMockupFieldsToMasterTemplateDesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_template_designs', function (Blueprint $table) {
            $table->string('mockup')->nullable();
            $table->longText('mockup_customer_canvas')->nullable();
            $table->string('mockup_width')->nullable();
            $table->string('mockup_height')->nullable();
            $table->text('design_location')->nullable();
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
            $table->dropColumn([
                'mockup',
                'mockup_customer_canvas',
                'mockup_width',
                'mockup_height',
                'design_location',
            ]);
        });
    }
}
