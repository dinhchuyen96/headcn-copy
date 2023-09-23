<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPostionsIntoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('positions')->nullable()->comment('1: Giám đốc, 2: Nhân viên bán hàng, 3: Nhân viên kỹ thuật, 4: Nhân viên kiểm tra, 5: Kế toán,6: Kiểm kho,7: Thủ quỹ,8: Khác');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(
                'positions'
            );
        });
    }
}
