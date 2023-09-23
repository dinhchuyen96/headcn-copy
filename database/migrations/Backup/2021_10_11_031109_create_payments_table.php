<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->comment('Nhà cung cấp')->constrained('suppliers')->nullOnDelete();
            $table->bigInteger('money')->unsigned()->nullable()->comment('Số tiền chi');
            $table->text('note')->comment('Ghi chú');
            $table->foreignId('user_id')->nullable()->comment('Nhân viên chi')->constrained('users')->nullOnDelete();
            $table->date('payment_date')->nullable()->comment('Ngày chi');
            $table->tinyInteger('type')->nullable()->comment('Loại chi');
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
        Schema::dropIfExists('payments');
    }
}
