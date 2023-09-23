<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneNumberToMotorbike extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorbikes', function (Blueprint $table) {
            $table->string('customer_phone', 20)->nullable()->comment('Số điện thoại khách hàng');
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
            $table->dropColumn('customer_phone');
        });
    }
}
