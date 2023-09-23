<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableAccountNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_money', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('type')->comment('Loại tài khoản');
			$table->integer('level')->nullable()->comment('Phân cấp cha con');
			$table->string('account_code')->comment('Mã tài khoản');
			$table->string('account_number')->comment('Số tài khoản');
			$table->string('account_owner')->comment('Chủ tài khoản');
            $table->string('bank_name')->comment('Tên ngân hàng');
            $table->bigInteger('balance')->comment('Số dư');
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
        Schema::drop('account_money');
    }
}
