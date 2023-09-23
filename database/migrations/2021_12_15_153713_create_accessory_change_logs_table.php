<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessoryChangeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessory_change_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('accessory_id');
            $table->string('accessory_code')->nullable();
            $table->integer('accessory_quantity');
            $table->bigInteger('warehouse_id');
            $table->bigInteger('position_in_warehouse_id')->nullable();
            $table->integer('reason')->nullable();
            $table->string('description')->nullable();
            $table->string('quantity_log')->nullable();
            $table->integer('type')->nullable()->default(1);
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
        Schema::dropIfExists('accessory_change_logs');
    }
}
