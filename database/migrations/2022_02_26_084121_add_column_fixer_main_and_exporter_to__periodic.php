<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnFixerMainAndExporterToPeriodic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periodic_checklist', function (Blueprint $table) {
            $table->bigInteger('service_user_fix_id')->nullable()->comment('Người sửa chữa chính');
            $table->bigInteger('service_user_export_id')->nullable()->comment('Người xuất kho');
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
            $table->dropIfExists('service_user_fix_id');
            $table->dropIfExists('service_user_export_id');
        });
    }
}
