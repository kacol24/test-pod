<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveProductionCostAndFulfillmentCostFieldOnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_products', function (Blueprint $table) {
            $table->dropColumn(['production_cost', 'fulfillment_cost']);
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
            $table->double('production_cost')->after('title');
            $table->double('fulfillment_cost')->after('production_cost');
        });
    }
}
