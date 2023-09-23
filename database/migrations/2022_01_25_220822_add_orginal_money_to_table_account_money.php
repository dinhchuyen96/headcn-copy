<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrginalMoneyToTableAccountMoney extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_money', function (Blueprint $table) {
            $table->bigInteger('orginal_money')->comment('Số tiền dư đầu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_money', function (Blueprint $table) {
            $table->dropColumn('orginal_money');
        });
    }
}
