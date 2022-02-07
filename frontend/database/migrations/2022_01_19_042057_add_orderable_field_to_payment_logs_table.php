<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderableFieldToPaymentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->dropColumn(['ref_id']);
            $table->foreignId('orderable_id')->after('id');
            $table->string('orderable_type')->after('orderable_id');
        });
        Schema::table('topups', function (Blueprint $table) {
            $table->string('ref_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->integer('ref_id');
            $table->dropColumn(['orderable_id', 'orderable_type']);
        });
        Schema::table('topups', function (Blueprint $table) {
            $table->dropColumn(['ref_id']);
        });
    }
}
