<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('created_by')->unsigned()->nullable()->index('users_created_by_foreign');
			$table->bigInteger('updated_by')->unsigned()->nullable()->index('users_updated_by_foreign');
			$table->string('name')->nullable();
			$table->string('email', 100)->nullable();
			$table->dateTime('email_verified_at')->nullable();
			$table->string('password')->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->softDeletes();
			$table->timestamps();
			$table->string('head_name')->nullable()->comment('Head Name');
			$table->boolean('positions')->nullable()->comment('1: Giám đốc, 2: Nhân viên bán hàng, 3: Nhân viên kỹ thuật, 4: Nhân viên kiểm tra, 5: Nhân viên sửa chữa ,6. Kế toán,7: Kiểm kho,8: Thủ quỹ,9: Trưởng phòng, 10: Phó phòng, 11 Khác');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
