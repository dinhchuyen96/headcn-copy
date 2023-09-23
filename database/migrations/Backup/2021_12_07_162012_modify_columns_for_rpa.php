<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnsForRpa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('hms_part_order_plan_detail', function (Blueprint $table) {
            $table->string('abnormal_status',255)->nullable()->default(0)->change();
        });

         //
         Schema::table('hms_service_results', function (Blueprint $table) {
            $table->string('created_by',255)->nullable()->default(0)->change();
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
    }
}
