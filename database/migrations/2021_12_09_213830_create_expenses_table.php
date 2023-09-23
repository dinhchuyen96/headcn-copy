<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expenses', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('expense_name', 50);
			$table->bigInteger('created_by')->unsigned()->nullable()->index('expenses_created_by_foreign');
			$table->bigInteger('updated_by')->unsigned()->nullable()->index('expenses_updated_by_foreign');
			$table->decimal('expense_amount');
			$table->softDeletes();
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
		Schema::drop('expenses');
	}

}
