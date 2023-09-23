<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrdersBuyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('orders_buy', function(Blueprint $table)
		{
			$table->foreign('accessories_id')->references('id')->on('accessories')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('motorbikes_id')->references('id')->on('motorbikes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('suppliers_id')->references('id')->on('suppliers')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('orders_buy', function(Blueprint $table)
		{
			$table->dropForeign('orders_buy_accessories_id_foreign');
			$table->dropForeign('orders_buy_motorbikes_id_foreign');
			$table->dropForeign('orders_buy_suppliers_id_foreign');
		});
	}

}
