<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accessories', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('supplier_id')->unsigned()->nullable()->index('accessories_supplier_id_foreign');
			$table->bigInteger('order_id')->unsigned()->nullable()->index('accessories_order_id_foreign');
			$table->string('name')->nullable()->comment('mã phụ tùng');
			$table->string('code')->nullable()->comment('tên phụ tùng');
			$table->integer('quantity')->default(1)->comment('số lượng');
			$table->bigInteger('price')->nullable()->comment('đơn giá');
			$table->softDeletes();
			$table->timestamps();
			$table->boolean('status')->nullable()->comment('1: đã lưu, 0:lưu nháp');
			$table->dateTime('buy_date')->nullable()->comment('ngày nhập');
			$table->bigInteger('admin_id')->nullable();
			$table->bigInteger('warehouse_id')->nullable();
			$table->bigInteger('position_in_warehouse_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('accessories');
	}

}
