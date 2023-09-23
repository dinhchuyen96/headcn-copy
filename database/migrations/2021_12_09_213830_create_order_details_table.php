<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_details', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('order_id')->nullable();
			$table->bigInteger('product_id')->nullable();
			$table->integer('quantity')->nullable()->default(1);
			$table->softDeletes();
			$table->timestamps();
			$table->bigInteger('admin_id')->nullable()->comment('Nguoi tao');
			$table->bigInteger('price')->nullable();
			$table->string('code')->nullable();
			$table->string('chassic_no')->nullable()->comment('Số khung');
			$table->string('engine_no')->nullable()->comment('Số máy');
			$table->string('model_code')->nullable()->comment('đời xe');
			$table->string('color')->nullable()->comment('màu xe');
			$table->bigInteger('mtoc_id')->unsigned()->nullable()->index('order_details_mtoc_id_foreign');
			$table->date('buy_date')->nullable()->comment('ngày mua');
			$table->boolean('status')->nullable()->comment('1: đã lưu, 0: lưu nháp');
			$table->integer('category')->nullable()->comment('1: phụ tùng, 2: xe máy, 3 Bảo dưỡng, 4 Sửa chữa');
			$table->boolean('type')->nullable()->comment(' 1: bán buon, 2:bán lẻ, 3:nhập ');
			$table->bigInteger('actual_price')->nullable()->comment('giá thực tế');
			$table->bigInteger('vat_price')->nullable()->comment('giá in hóa đơn');
			$table->bigInteger('listed_price')->nullable()->comment('giá niêm yết');
			$table->string('model_list')->nullable()->comment('Danh mục đời xe');
			$table->string('model_type')->nullable()->comment('Phân loại đời xe');
			$table->bigInteger('supplier_type')->nullable()->comment('1: HVN');
			$table->string('order_number')->nullable()->comment('mã đơn hàng code');
			$table->string('name')->nullable()->comment('tên');
			$table->integer('warehouse_id')->nullable()->comment('Nhập hàng và xuất hàng từ kho');
			$table->integer('position_in_warehouse_id')->nullable()->comment('Nhập hàng và xuất hàng từ vị trí kho');
			$table->integer('promotion')->default(0)->comment('Khuyến mãi');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_details');
	}

}
