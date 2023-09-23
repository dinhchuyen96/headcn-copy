<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToPeriodicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periodic_checklist', function (Blueprint $table) {
            $table->bigInteger('service_user_id')->nullable()->after('admin_id')->comment('Người tiếp nhận');
            $table->bigInteger('service_user_check_id')->nullable()->after('service_user_id')->comment('Người kiểm tra');
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
            //
        });
    }
}
