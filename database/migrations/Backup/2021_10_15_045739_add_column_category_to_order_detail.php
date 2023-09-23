<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCategoryToOrderDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->bigInteger('admin_id')->nullable()->comment('Nguoi tao');
            $table->bigInteger('price')->nullable();
            $table->string('code')->nullable();
            $table->string('chassic_no')->nullable()->comment('Số khung');
            $table->string('engine_no')->nullable()->comment('Số máy');
            $table->string('model_code')->nullable()->comment('đời xe');
            $table->string('color')->nullable()->comment('màu xe');
            $table->foreignId('mtoc_id')->nullable()->constrained('mtoc')->comment('Map với MTOC');
            $table->date('buy_date')->nullable()->comment('ngày mua');
            $table->tinyInteger('status')->nullable()->comment('1: đã lưu, 0: lưu nháp');
            $table->integer('category')->nullable()->comment('1: phụ tùng, 2: xe máy, 3 Bảo dưỡng, 4 Sửa chữa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('admin_id');
            $table->dropColumn('price');
            $table->dropColumn('code');
            $table->dropColumn('chassic_no');
            $table->dropColumn('engine_no');
            $table->dropColumn('model_code');
            $table->dropColumn('mtoc_id');
            $table->dropColumn('color');
            $table->dropColumn('category');
            $table->dropColumn('buy_date');
            $table->dropColumn('status');
        });
    }
}
