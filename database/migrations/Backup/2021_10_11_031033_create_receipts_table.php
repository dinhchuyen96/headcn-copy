<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->comment('Khách hàng')->constrained('customers')->nullOnDelete();
            $table->bigInteger('money')->unsigned()->nullable()->comment('Số tiền thu');
            $table->text('note')->comment('Ghi chú');
            $table->foreignId('user_id')->nullable()->comment('Nhân viên thu')->constrained('users')->nullOnDelete();
            $table->date('receipt_date')->nullable()->comment('Ngày thu');
            $table->tinyInteger('type')->nullable()->comment('Loại thu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
