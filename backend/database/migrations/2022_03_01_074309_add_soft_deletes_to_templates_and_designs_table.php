<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToTemplatesAndDesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_product_templates', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('master_template_designs', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('master_template_previews', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_product_templates', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
        });

        Schema::table('master_template_designs', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
        });

        Schema::table('master_template_previews', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
        });
    }
}
