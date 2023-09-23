<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSupplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('code',50)->unique()->comment('mã ncc');
            $table->text('url')->nullable()->comment('trang chủ');
            $table->tinyInteger('city_id')->nullable()->comment('địa chỉ theo quận huyện tỉnh thành phố');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('url');
            $table->dropColumn('address_id');
        });
    }
}
