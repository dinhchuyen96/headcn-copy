<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMotorNumberToPeriodicChecklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periodic_checklist', function (Blueprint $table) {
            $table->string('motor_number',255)->nullable()->comment('Biển số xe');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('periodic_checklist', function (Blueprint $table) {
            $table->dropColumn('motor_number');
        });
    }
}
