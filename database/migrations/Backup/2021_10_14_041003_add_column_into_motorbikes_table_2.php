<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIntoMotorbikesTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorbikes', function (Blueprint $table) {
            $table->tinyInteger('status')->nullable()->comment('1: đã lưu, 0:lưu nháp');
            $table->date('buy_date')->nullable()->comment('ngày nhập');
            $table->tinyInteger('warehouse_id')->nullable()->comment('kho nhập xe');
            $table->bigInteger('admin_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motorbikes', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('buy_date');
            $table->dropColumn('warehouse_id');
            $table->dropColumn('admin_id');
        });
    }
}
