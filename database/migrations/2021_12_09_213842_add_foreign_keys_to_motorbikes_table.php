<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMotorbikesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('motorbikes', function(Blueprint $table)
		{
			$table->foreign('customer_id')->references('id')->on('customers')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('mtoc_id')->references('id')->on('mtoc')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('order_id')->references('id')->on('orders')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('motorbikes', function(Blueprint $table)
		{
			$table->dropForeign('motorbikes_customer_id_foreign');
			$table->dropForeign('motorbikes_mtoc_id_foreign');
			$table->dropForeign('motorbikes_order_id_foreign');
			$table->dropForeign('motorbikes_supplier_id_foreign');
		});
	}

}
