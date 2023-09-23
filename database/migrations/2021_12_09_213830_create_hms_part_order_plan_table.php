<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHmsPartOrderPlanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hms_part_order_plan', function(Blueprint $table)
		{
			$table->string('po_date', 22)->nullable()->default('');
			$table->string('order_number', 50)->nullable()->default('');
			$table->string('order_type', 50)->nullable()->default('');
			$table->string('status', 50)->nullable()->default('');
			$table->string('supplier_code')->nullable()->default('');
			$table->string('source_warehouse')->nullable()->default('');
			$table->string('total_amount')->nullable()->default('');
			$table->string('send_date', 22)->nullable()->default('');
			$table->string('part_order_type', 22)->nullable()->default('');
			$table->string('Head_name')->nullable()->default('');
			$table->string('sap_message')->nullable()->default('');
			$table->string('sap_status')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hms_part_order_plan');
	}

}
