<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExProvinceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ex_province', function(Blueprint $table)
		{
			$table->string('province_code', 5)->unique('UC_EX_PROVINCEPROVINCE_CODE_COL');
			$table->string('created_by', 75)->nullable()->comment('User tạo (username)');
			$table->dateTime('created_date')->nullable()->comment('Thời điểm tạo');
			$table->string('last_modified_by', 75)->nullable()->comment('User cập nhật cuối');
			$table->dateTime('last_modified_date')->nullable()->comment('Thời điểm cập nhật cuối');
			$table->string('coordinate')->nullable()->comment('Tọa độ');
			$table->string('description')->nullable()->comment('Mô tả');
			$table->string('name')->comment('Tên Tỉnh/Thành');
			$table->string('short_name')->nullable()->comment('Tên rút gon (Không có tiền tố TỈnh/Thành)');
			$table->boolean('status')->default(1)->comment('Trạng thái, -1/0/1 tương ứng Xóa/Ko hoạt động/Hoạt động');
			$table->string('type', 30)->comment('Loại (Tỉnh/Thành)');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ex_province');
	}

}
