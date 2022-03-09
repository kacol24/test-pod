<?php

use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeTemplateFieldsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }

        Schema::table('master_product_templates', function (Blueprint $table) {
            $table->string('design_name')->nullable()->change();
            $table->double('price')->nullable()->change();
        });
        Schema::table('master_template_designs', function (Blueprint $table) {
            $table->string('file')->nullable()->change();
            $table->string('page_name')->nullable()->change();
            $table->longText('customer_canvas')->nullable()->change();
        });
        Schema::table('master_template_previews', function (Blueprint $table) {
            $table->string('file')->nullable()->change();
            $table->string('preview_name')->nullable()->change();
            $table->string('thumbnail_name')->nullable()->change();
            $table->longText('customer_canvas')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }

        Schema::table('master_product_templates', function (Blueprint $table) {
            $table->string('design_name')->change();
            $table->double('price')->change();
        });
        Schema::table('master_template_designs', function (Blueprint $table) {
            $table->string('file')->change();
            $table->string('page_name')->change();
            $table->longText('customer_canvas')->change();
        });
        Schema::table('master_template_previews', function (Blueprint $table) {
            $table->string('file')->change();
            $table->string('preview_name')->change();
            $table->string('thumbnail_name')->change();
            $table->longText('customer_canvas')->change();
        });
    }
}
