<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHmsPartOrderPlanDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hms_part_order_plan_detail', function(Blueprint $table)
		{
			$table->string('line_number', 5)->nullable();
			$table->string('part_no', 50)->nullable()->default('');
			$table->string('part_description')->nullable();
			$table->float('quantity_requested', 10)->nullable();
			$table->string('status', 50)->nullable();
			$table->string('part_category', 16)->nullable()->default('');
			$table->string('remarks')->nullable();
			$table->float('dnp', 10)->nullable();
			$table->string('total_amount', 11)->nullable()->default('');
			$table->string('abnormal_status')->nullable();
			$table->float('allocated_qty', 10)->nullable();
			$table->string('so_no', 50)->nullable();
			$table->string('back_order_qty', 50)->nullable();
			$table->string('eta', 50)->nullable();
			$table->string('etd', 50)->nullable();
			$table->string('sap_status', 50)->nullable();
			$table->float('cancel_quantity', 10)->nullable();
			$table->string('dnp_discount_rate', 50)->nullable();
			$table->string('noticemark', 50)->nullable();
			$table->string('order_number', 50)->nullable()->default('');
			$table->string('weighted_average', 50)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hms_part_order_plan_detail');
	}

}
