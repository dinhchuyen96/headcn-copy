<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPeriodicChecklistTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('periodic_checklist', function(Blueprint $table)
		{
			$table->foreign('customers_id')->references('id')->on('customers')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('motorbikes_id')->references('id')->on('motorbikes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('orders_id')->references('id')->on('orders')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('periodic_checklist', function(Blueprint $table)
		{
			$table->dropForeign('periodic_checklist_customers_id_foreign');
			$table->dropForeign('periodic_checklist_motorbikes_id_foreign');
			$table->dropForeign('periodic_checklist_orders_id_foreign');
		});
	}

}
