<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustTemplatesAndPreviewsTableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_products', function (Blueprint $table) {
            $table->dropColumn([
                'shape',
                'orientation',
                'unit',
                'enable_resize',
                'bleed',
                'safety_line',
                'template_width',
                'template_height',
                'ratio',
            ]);
        });

        Schema::create('master_product_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->string('design_name');
            $table->double('price');
            $table->string('shape')->nullable();
            $table->string('orientation')->nullable();
            $table->string('unit')->nullable();
            $table->boolean('enable_resize')->nullable();
            $table->text('bleed')->nullable();
            $table->text('safety_line')->nullable();
            $table->text('template_width')->nullable();
            $table->text('template_height')->nullable();
            $table->text('ratio')->nullable();
        });

        Schema::create('master_template_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id');
            $table->string('file');
            $table->string('page_name');
            $table->longText('customer_canvas');
        });

        Schema::create('master_template_previews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id');
            $table->string('file');
            $table->string('preview_name');
            $table->string('thumbnail_name');
            $table->string('file_config')->nullable();
            $table->longText('customer_canvas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_products', function (Blueprint $table) {
            $table->string('shape')->nullable();
            $table->string('orientation')->nullable();
            $table->string('unit')->nullable();
            $table->boolean('enable_resize')->nullable();
            $table->text('bleed')->nullable();
            $table->text('safety_line')->nullable();
            $table->text('width')->nullable();
            $table->text('height')->nullable();
            $table->text('ratio')->nullable();
        });

        Schema::dropIfExists('master_product_templates');
        Schema::dropIfExists('master_template_designs');
        Schema::dropIfExists('master_template_previews');
    }
}
