<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorbikesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('motorbikes', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('chassic_no')->nullable()->comment('Số khung');
			$table->string('engine_no')->nullable()->comment('Số máy');
			$table->string('model_code')->nullable()->comment('Model');
			$table->string('color')->nullable()->comment('Màu xe');
			$table->bigInteger('quantity')->unsigned()->default(1)->comment('Số lượng');
			$table->bigInteger('price')->unsigned()->nullable()->comment('Đơn giá');
			$table->bigInteger('supplier_id')->unsigned()->nullable()->index('motorbikes_supplier_id_foreign')->comment('Nhà cung cấp');
			$table->timestamps();
			$table->bigInteger('customer_id')->unsigned()->nullable()->index('motorbikes_customer_id_foreign')->comment('khách hàng');
			$table->boolean('status')->nullable()->comment('1: đã lưu, 2:lưu nháp (chờ xử lý khi bán) ,3 mới nhập');
			$table->date('buy_date')->nullable()->comment('ngày nhập');
			$table->boolean('warehouse_id')->nullable()->comment('kho nhập xe');
			$table->bigInteger('admin_id')->nullable();
			$table->bigInteger('head_id')->nullable()->comment('Head nhận xe');
			$table->bigInteger('mtoc_id')->unsigned()->nullable()->index('motorbikes_mtoc_id_foreign');
			$table->date('sell_date')->nullable()->comment('Ngày bán hàng');
			$table->bigInteger('order_id')->unsigned()->nullable()->index('motorbikes_order_id_foreign')->comment('Đơn mua');
			$table->string('model_list')->nullable()->comment('Danh mục đời xe');
			$table->string('model_type')->nullable()->comment('Phân loại đời xe');
			$table->string('head_sell')->nullable()->comment('Head bán xe');
			$table->string('motor_numbers')->nullable()->comment('Biển số xe');
			$table->string('head_get')->nullable()->comment('Head nhận xe');
			$table->integer('is_out')->default(0)->comment('0: Xe của head, 1 : Xe ngoài head');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('motorbikes');
	}

}
