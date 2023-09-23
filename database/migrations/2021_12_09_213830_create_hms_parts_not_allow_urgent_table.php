<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHmsPartsNotAllowUrgentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hms_parts_not_allow_urgent', function(Blueprint $table)
		{
			$table->string('part_no', 50)->nullable()->default('');
			$table->string('part_name_en')->nullable()->default('');
			$table->string('part_name_vn')->nullable()->default('');
			$table->string('category', 50)->nullable()->default('');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hms_parts_not_allow_urgent');
	}

}
