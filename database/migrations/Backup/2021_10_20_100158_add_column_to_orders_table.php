<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('service_user_id')->nullable()->constrained('users')->comment('Map với users');
            $table->foreignId('service_user_check_id')->nullable()->constrained('users')->comment('Map với users');
            $table->string('bill_number',255)->nullable()->comment('Số phiếu');
            $table->dateTime('in_factory_date')->nullable()->comment('Ngày giờ vào xưởng');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('service_user_id');
            $table->dropColumn('service_user_check_id');
            $table->dropColumn('bill_number');
            $table->dropColumn('in_factory_date');

        });
    }
}
