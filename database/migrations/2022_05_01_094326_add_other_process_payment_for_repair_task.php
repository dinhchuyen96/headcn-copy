<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherProcessPaymentForRepairTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repair_task', function (Blueprint $table) {

            $table->bigInteger('payment')->nullable()->comment('Số tiền gia công');
            $table->text('process_company')->nullable()->comment('Tên đơn vị gia công');
            $table->bigInteger('supply_id')->nullable()->comment('Chi cho đơn vị gia công');
            $table->bigInteger('order_payment_id')->nullable()->comment('Hóa đơn chi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repair_task', function (Blueprint $table) {
            $table->dropColumn('supply_id');
            $table->dropColumn('order_payment_id');
            $table->dropColumn('payment');
            $table->dropColumn('process_company');
        });
    }
}
