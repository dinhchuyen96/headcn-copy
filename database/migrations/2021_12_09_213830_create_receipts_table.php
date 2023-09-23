<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('receipts', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('customer_id')->unsigned()->nullable()->index('receipts_customer_id_foreign')->comment('Khách hàng');
			$table->bigInteger('money')->unsigned()->nullable()->comment('Số tiền thu');
			$table->bigInteger('user_id')->unsigned()->nullable()->index('receipts_user_id_foreign')->comment('Nhân viên thu');
			$table->date('receipt_date')->nullable()->comment('Ngày thu');
			$table->boolean('type')->nullable()->comment('Loại thu');
			$table->timestamps();
			$table->text('note')->nullable()->comment('Ghi chú');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('receipts');
	}

}
