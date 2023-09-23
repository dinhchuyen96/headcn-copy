<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFromToAccoutColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            //
            $table->bigInteger('to_account_money_id')->nullable();
        });
        Schema::table('receipts', function (Blueprint $table) {
            //
            $table->bigInteger('from_account_money_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('to_account_money_id');
        });
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropColumn('from_account_money_id');
        });
    }
}
