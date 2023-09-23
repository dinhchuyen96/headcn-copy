<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audits', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('user_type')->nullable();
			$table->bigInteger('user_id')->unsigned()->nullable();
			$table->string('event');
			$table->string('auditable_type');
			$table->bigInteger('auditable_id')->unsigned();
			$table->text('old_values')->nullable();
			$table->text('new_values')->nullable();
			$table->text('url')->nullable();
			$table->string('ip_address', 45)->nullable();
			$table->string('user_agent', 1023)->nullable();
			$table->string('tags')->nullable();
			$table->string('note', 1000)->nullable();
			$table->timestamps();
			$table->index(['user_id','user_type']);
			$table->index(['auditable_type','auditable_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('audits');
	}

}
