<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodicLevelToPeriodicChecklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periodic_checklist', function (Blueprint $table) {
            $table->bigInteger('periodic_level')->nullable()->comment('Lần kiểm tra định kỳ');
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
            $table->dropColumn('periodic_level');
        });
    }
}
