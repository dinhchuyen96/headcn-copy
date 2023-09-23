<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('supplier_id')->unsigned()->nullable()->comment('Nhà cung cấp');
            $table->bigInteger('customer_id')->unsigned()->nullable()->comment('khach hang');
			$table->bigInteger('money')->unsigned()->nullable()->comment('Số tiền chi');
			$table->bigInteger('user_id')->unsigned()->nullable()->index('payments_user_id_foreign')->comment('Nhân viên chi');
			$table->date('payment_date')->nullable()->comment('Ngày chi');
			$table->boolean('type')->nullable()->comment('1: Bán  lẻ xe máy, 2 Bán buôn xe máy, 3 Bán lẻ phụ tùng, 4 Bán buôn phụ tùng, 5 KTĐK, 6 Sửa chữa thông thường, 7 Nợ cũ, 8 Nhập phụ tùng, 9 Nhập xe, 10 Dịch vụ khác');
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
		Schema::drop('payments');
	}

}
