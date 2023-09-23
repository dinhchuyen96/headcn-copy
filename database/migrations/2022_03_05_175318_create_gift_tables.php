<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_master', function (Blueprint $table) {
            $table->id();
            $table->string('code',50);
            $table->string('name',255);
            $table->bigInteger('rate')->comment('so diem can quy doi')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        //create table gift transaction
        Schema::create('gift_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('gift_id');
            $table->dateTime('trans_date')->nullable()->comment('Ngay giao dich');
            $table->bigInteger('from_warehouse_id')->nullable()->comment('Tu kho');
            $table->bigInteger('from_position_in_warehouse_id')->nullable()->comment('Tu vi tri');
            $table->bigInteger('to_warehouse_id')->nullable()->comment('den kho');
            $table->bigInteger('to_position_in_warehouse_id')->nullable()->comment('den vi tri');
            $table->bigInteger('qty')->comment('so luong')->default(0);
            $table->bigInteger('trans_type')->comment('loai 1 NK 2 xk 3 ck')->default(1);
            $table->string('note')->nullable()->comment('note of transaction');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('gift_balance', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('gift_id');
            $table->bigInteger('warehouse_id')->nullable()->comment('kho');
            $table->bigInteger('position_in_warehouse_id')->nullable()->comment('vi tri');
            $table->bigInteger('qty')->comment('so luong con lai')->default(0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('gift_master');
        Schema::dropIfExists('gift_transactions');
        Schema::dropIfExists('gift_balance');
    }
}
