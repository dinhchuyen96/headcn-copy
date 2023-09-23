<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtoDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mto_data', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('ACTFLG')->nullable();
			$table->string('MTOCD')->nullable();
			$table->string('MODEL_CODE')->nullable();
			$table->string('TYPE_CODE')->nullable();
			$table->string('OPTION_CODE')->nullable();
			$table->string('MODEL_CODE_S')->nullable();
			$table->string('MODEL_NAME_S')->nullable();
			$table->string('MODEL_CODE_W')->nullable();
			$table->string('MODEL_NAME_W')->nullable();
			$table->string('F_HEADER')->nullable();
			$table->string('E_HEADER')->nullable();
			$table->string('SRTORD')->nullable();
			$table->string('CLS_CODE')->nullable();
			$table->string('ODRFLG')->nullable();
			$table->string('PAYFLG')->nullable();
			$table->string('RPTFLG_S')->nullable();
			$table->string('RPTFLG_W')->nullable();
			$table->string('MDFUSR')->nullable();
			$table->string('MDFWKS')->nullable();
			$table->string('LSTMDF')->nullable();
			$table->string('MFGFLG')->nullable();
			$table->string('CTDUSR')->nullable();
			$table->string('CTDWKS')->nullable();
			$table->string('CTDDTM')->nullable();
			$table->string('CTDPGM')->nullable();
			$table->string('MDFPGM')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mto_data');
	}

}
