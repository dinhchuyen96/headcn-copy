<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodicChecklistTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('periodic_checklist', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->timestamps();
			$table->string('head_code')->nullable()->comment('Mã head');
			$table->string('km')->nullable()->comment('Số km');
			$table->date('check_date')->nullable()->comment('Ngày kiểm tra');
			$table->bigInteger('customers_id')->unsigned()->nullable()->index('periodic_checklist_customers_id_foreign');
			$table->bigInteger('motorbikes_id')->unsigned()->nullable()->index('periodic_checklist_motorbikes_id_foreign');
			$table->bigInteger('orders_id')->unsigned()->nullable()->index('periodic_checklist_orders_id_foreign');
			$table->bigInteger('admin_id')->nullable()->comment('Nguoi tao');
			$table->softDeletes();
			$table->string('motor_number')->nullable()->comment('Biển số xe');
			$table->bigInteger('periodic_level')->nullable()->comment('Lần kiểm tra định kỳ');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('periodic_checklist');
	}

}
