<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInstallmentcompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('installment_company', function (Blueprint $table) {
            $table->bigInteger('benefit_percentage')->nullable()->default(0)->comment('Ti le % chia hoa hong tren moi don ap dung cho head');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('installment_company', function (Blueprint $table) {
            $table->dropColumn('benefit_percentage');
        });
    }
}
