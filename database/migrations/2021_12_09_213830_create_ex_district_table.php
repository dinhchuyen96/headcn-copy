<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExDistrictTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ex_district', function(Blueprint $table)
		{
			$table->string('district_code', 5)->unique('UC_EX_DISTRICTDISTRICT_CODE_COL');
			$table->string('created_by', 75)->nullable()->comment('User tạo (username)');
			$table->dateTime('created_date')->nullable()->comment('Thời điểm tạo');
			$table->string('last_modified_by', 75)->nullable()->comment('User cập nhật cuối');
			$table->dateTime('last_modified_date')->nullable()->comment('Thời điểm cập nhật cuối');
			$table->string('description')->nullable()->comment('Mô tả');
			$table->string('name')->comment('Tên Phường/Xã');
			$table->string('province_code', 5)->index('idx_province_code')->comment('Mã Tỉnh/Thành');
			$table->string('short_name')->nullable()->comment('Tên rút gọn (Không có tiền tố Phường/Xã)');
			$table->boolean('status')->default(1)->comment('Trạng thái, -1/0/1 tương ứng Xóa/Ko hoạt động/Hoạt động');
			$table->string('type', 30)->comment('Loại (Phường/Xã)');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ex_district');
	}

}
