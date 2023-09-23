<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAndAddColumnToHmsReceivePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hms_receive_plan', function (Blueprint $table) {
            $table->dropColumn('actual_arrival_date_time');

        });
        Schema::table('hms_receive_plan', function (Blueprint $table) {
            $table->date('actual_arrival_date_time')->nullable()->comment('Ngày thực tế');
            $table->bigInteger('physical_status')->nullable()->comment('trạng thái');
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
