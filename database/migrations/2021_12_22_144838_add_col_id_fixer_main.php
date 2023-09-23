<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColIdFixerMain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('repair_bill', function (Blueprint $table) {
            $table->bigInteger('id_fixer_main')->nullable()->comment('Người sửa chữa chính');
            $table->bigInteger('id_export_warehouse')->nullable()->comment('Người xuất kho');
        });
        Schema::table('repair_task', function (Blueprint $table) {
            $table->bigInteger('id_fixer_main')->nullable()->comment('Người sửa chữa chính');
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
            $table->dropColumn('id_fixer_main');
        });
        Schema::table('repair_task', function (Blueprint $table) {
            $table->dropColumn('id_fixer_main');
        });
    }
}
