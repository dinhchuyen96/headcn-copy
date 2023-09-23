<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairBillTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('repair_bill', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->timestamps();
			$table->bigInteger('service_user_id')->unsigned()->nullable()->index('repair_bill_service_user_id_foreign');
			$table->bigInteger('service_user_check_id')->unsigned()->nullable()->index('repair_bill_service_user_check_id_foreign');
			$table->string('km')->nullable()->comment('Số km hiện tại');
			$table->bigInteger('orders_id')->unsigned()->nullable()->index('repair_bill_orders_id_foreign');
			$table->bigInteger('motorbikes_id')->unsigned()->nullable()->index('repair_bill_motorbikes_id_foreign');
			$table->string('content_request')->comment('Triệu chứng/Yêu cầu KT');
			$table->string('code_request')->comment('Mã SR');
			$table->integer('service_type')->comment('Loại sửa chữa');
			$table->timestamp('in_factory_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Ngày giờ vào xưởng');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('repair_bill');
	}

}
