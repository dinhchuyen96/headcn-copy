<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionInWarehouseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('position_in_warehouse', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->integer('warehouse_id')->comment('Thuộc kho');
			$table->string('name')->comment('Tên vị trí');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('position_in_warehouse');
	}

}
