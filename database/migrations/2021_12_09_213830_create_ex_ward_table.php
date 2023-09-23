<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExWardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ex_ward', function(Blueprint $table)
		{
			$table->string('ward_code', 5)->unique('UC_EX_WARDWARD_CODE_COL');
			$table->string('created_by', 75)->nullable()->comment('User tạo (username)');
			$table->dateTime('created_date')->nullable()->comment('Thời điểm tạo');
			$table->string('last_modified_by', 75)->nullable()->comment('User cập nhật cuối');
			$table->dateTime('last_modified_date')->nullable()->comment('Thời điểm cập nhật cuối');
			$table->string('description')->nullable()->comment('Mô tả');
			$table->string('district_code', 5)->index('idx_district_code')->comment('Mã Quận/Huyện');
			$table->string('name')->comment('Tên Xã Phường');
			$table->string('short_name')->nullable()->comment('Tên ngắn (Không có tiền tố Xã/Phường)');
			$table->boolean('status')->default(1)->comment('Trạng thái');
			$table->string('type', 30)->comment('Loại (Xã/Phường)');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ex_ward');
	}

}
