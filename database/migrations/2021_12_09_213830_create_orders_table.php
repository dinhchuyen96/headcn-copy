<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('customer_id')->unsigned()->nullable()->index('orders_customer_id_foreign');
			$table->bigInteger('created_by')->nullable();
			$table->string('order_no')->nullable();
			$table->bigInteger('total_items')->nullable();
			$table->decimal('sub_total', 17)->nullable()->default(0.00);
			$table->decimal('tax', 17)->nullable()->default(0.00);
			$table->decimal('discount', 17)->nullable()->default(0.00);
			$table->decimal('total', 17)->nullable()->default(0.00);
			$table->integer('category')->nullable()->comment('1: phụ tùng, 2: xe máy, 3 Bảo dưỡng, 4 Sửa chữa, 5 Nợ cũ, 6 Chi phí khác');
			$table->integer('type')->nullable()->comment('1: bán buôn, 2: bán lẻ, 3: nhập hàng');
			$table->softDeletes();
			$table->timestamps();
			$table->boolean('status')->nullable()->comment('1: đã thanh toán, 2: Chưa thanh toán, 3: Chờ xử lý, 4: Đã hủy, 5: chờ xử lý hủy');
			$table->integer('order_type')->nullable()->default(1)->comment('1:bán hàng,2:mua hàng');
			$table->bigInteger('total_money')->nullable()->comment('Tổng số tiền');
			$table->bigInteger('warehouse_id')->nullable()->comment('Mã kho map master data 5');
			$table->dateTime('date_payment')->nullable()->comment('Hạn thanh toán');
			$table->bigInteger('bill_id')->nullable()->comment('id của phiếu thu, chi');
			$table->bigInteger('supplier_id')->unsigned()->nullable()->comment('mã nhà cung cấp');
			$table->text('note')->nullable();
			$table->string('head_sell')->nullable()->comment('Head bán xe');
			$table->string('motor_numbers')->nullable()->comment('Biển số xe');
			$table->bigInteger('service_user_id')->unsigned()->nullable()->index('orders_service_user_id_foreign');
			$table->bigInteger('service_user_check_id')->unsigned()->nullable()->index('orders_service_user_check_id_foreign');
			$table->string('bill_number')->nullable()->comment('Số phiếu');
			$table->dateTime('in_factory_date')->nullable()->comment('Ngày giờ vào xưởng');
			$table->bigInteger('admin_id')->nullable()->comment('Người tạo');
			$table->bigInteger('motorbikes_id')->unsigned()->nullable()->index('orders_motorbikes_id_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders');
	}

}
