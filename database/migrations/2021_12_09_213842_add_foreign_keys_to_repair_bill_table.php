<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRepairBillTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('repair_bill', function(Blueprint $table)
		{
			$table->foreign('motorbikes_id')->references('id')->on('motorbikes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('orders_id')->references('id')->on('orders')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('service_user_check_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('service_user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('repair_bill', function(Blueprint $table)
		{
			$table->dropForeign('repair_bill_motorbikes_id_foreign');
			$table->dropForeign('repair_bill_orders_id_foreign');
			$table->dropForeign('repair_bill_service_user_check_id_foreign');
			$table->dropForeign('repair_bill_service_user_id_foreign');
		});
	}

}
