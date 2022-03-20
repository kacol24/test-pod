<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferralsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table){
            $table->string('ref_code')->after('id')->nullable();
        });
        \DB::statement("ALTER TABLE `balance_logs` CHANGE `type` `type` ENUM('topup', 'order', 'commission','refund');");
        Schema::create('store_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ref_id');
            $table->foreignId('store_id');
            $table->double('total_commission')->default(0);
            $table->timestamp('expired_at');
            $table->timestamps();
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
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['ref_code']);
        });
        \DB::statement("ALTER TABLE `balance_logs` CHANGE `type` `type` ENUM('topup', 'order');");
        Schema::dropIfExists('store_referrals');
    }
}
