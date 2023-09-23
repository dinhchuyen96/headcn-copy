<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGiftMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //
        Schema::table('gift_transactions', function (Blueprint $table) {
            $table->bigInteger('customer_id')->nullable()->comment('id cua customer neu chon customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('gift_transactions', function (Blueprint $table) {
            $table->dropColumn('customer_id');
        });
    }
}
