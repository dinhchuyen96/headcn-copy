<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnToHmsReceivePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hms_receive_plan', function (Blueprint $table) {
            $table->dropColumn('eta','arrival_date');
        });

        Schema::table('hms_receive_plan', function (Blueprint $table) {
            $table->date('eta')->nullable()->comment('Ước tính ngày đến');
            $table->date('arrival_date')->nullable()->comment('Ngày tới');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hms_receive_plan', function (Blueprint $table) {
            //
        });
    }
}
