<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInFactoryDateToRepairBill extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repair_bill', function (Blueprint $table) {
            $table->timestamp('in_factory_date')->useCurrent()->comment('Ngày giờ vào xưởng');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repair_bill', function (Blueprint $table) {
            $table->dropColumn(['in_factory_date']);
        });
    }
}
