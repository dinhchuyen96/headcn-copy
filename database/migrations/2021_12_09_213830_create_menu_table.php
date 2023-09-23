<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menu', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned()->comment('Danh sach url');
			$table->string('name', 500)->unique()->comment('ten chuc nang');
			$table->string('code')->unique()->comment('ma chuc nang');
			$table->string('permission_name')->unique()->comment('tuong ung voi ten quyen han base');
			$table->string('alias')->unique()->comment('alias url trong router');
			$table->string('note', 1000)->comment('Mo ta chuc nang');
			$table->bigInteger('admin_id')->nullable()->comment('Nguoi tao');
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
		Schema::drop('menu');
	}

}
