<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repair_bill', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('service_user_id')->nullable()->constrained('users')->comment('Map với users');
            $table->foreignId('service_user_check_id')->nullable()->constrained('users')->comment('Map với users');
            $table->string('bill_number',255)->nullable()->comment('Số phiếu');
            $table->dateTime('in_factory_date')->nullable()->comment('Ngày giờ vào xưởng');
            $table->string('km',255)->nullable()->comment('Số km hiện tại');
            $table->foreignId('orders_id')->nullable()->constrained('orders')->comment('Map với orders');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repair_bill');
    }
}
