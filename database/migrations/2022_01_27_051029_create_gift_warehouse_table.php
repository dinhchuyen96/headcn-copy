<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_warehouse', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
			$table->string('name')->comment('Tên kho');
			$table->string('address')->comment('Địa chỉ kho');
			$table->timestamp('established_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Ngày thành lập kho');
			$table->string('province_id')->nullable();
			$table->string('district_id')->nullable();
			$table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gift_warehouse');
    }
}
