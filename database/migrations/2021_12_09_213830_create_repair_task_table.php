<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairTaskTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('repair_task', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->timestamps();
			$table->string('content')->nullable()->comment('Nội dung công việc');
			$table->bigInteger('price')->nullable()->comment('Tiền công');
			$table->bigInteger('orders_id')->unsigned()->nullable()->index('repair_task_orders_id_foreign');
			$table->bigInteger('admin_id')->nullable()->comment('Người tạo');
			$table->boolean('status')->nullable()->comment('1: đã lưu, 0:lưu nháp');
			$table->softDeletes();
			$table->integer('promotion')->comment('Khuyến mãi');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('repair_task');
	}

}
