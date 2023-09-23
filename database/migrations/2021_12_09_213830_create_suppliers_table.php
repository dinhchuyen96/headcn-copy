<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('suppliers', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned()->comment('Lưu thông tin nhà cung cấp');
			$table->string('name', 50);
			$table->string('email', 50)->nullable();
			$table->string('image', 100)->nullable();
			$table->text('address')->nullable();
			$table->softDeletes();
			$table->timestamps();
			$table->string('code', 50)->unique()->comment('mã ncc');
			$table->text('url')->nullable()->comment('trang chủ');
			$table->boolean('city_id')->nullable()->comment('địa chỉ theo quận huyện tỉnh thành phố');
			$table->string('province_id')->nullable()->comment('map voi ex_province_code');
			$table->string('district_id')->nullable()->comment('map voi ex_district_code');
			$table->string('ward_id')->nullable()->comment('map voi ex_ward_code');
			$table->string('phone', 20)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('suppliers');
	}

}
