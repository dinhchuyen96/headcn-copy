<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColCustomerCareToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_sent_ktdk')->default(0)->comment('1: Đã gửi, 0 Chưa gửi');
            $table->boolean('is_sent_4s')->default(0)->comment('1: Đã gửi, 0 Chưa gửi');;
            $table->boolean('is_sent_thank_you')->default(0)->comment('1: Đã gửi, 0 Chưa gửi');
            $table->boolean('is_sent_repair')->default(0)->comment('1: Đã gửi, 0 Chưa gửi');
            $table->boolean('is_sent_birtday')->default(0)->comment('1: Đã gửi, 0 Chưa gửi');
            $table->dateTime('last_datetime_sent_ktdk')->nullable()->comment('Thời gian gửi cuối cùng');
            $table->dateTime('last_datetime_sent_4s')->nullable()->comment('Thời gian gửi cuối cùng');
            $table->dateTime('last_datetime_sent_thank_you')->nullable()->comment('Thời gian gửi cuối cùng');
            $table->dateTime('last_datetime_sent_repair')->nullable()->comment('Thời gian gửi cuối cùng');
            $table->dateTime('last_datetime_sent_birtday')->nullable()->comment('Thời gian gửi cuối cùng');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('is_sent_ktdk');
            $table->dropColumn('is_sent_4s');
            $table->dropColumn('is_sent_thank_you');
            $table->dropColumn('is_sent_repair');
            $table->dropColumn('is_sent_birtday');
            $table->dropColumn('last_datetime_sent_ktdk');
            $table->dropColumn('last_datetime_sent_4s');
            $table->dropColumn('last_datetime_sent_thank_you');
            $table->dropColumn('last_datetime_sent_repair');
            $table->dropColumn('last_datetime_sent_birtday');
        });
    }
}
