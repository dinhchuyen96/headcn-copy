<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorbikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motorbikes', function (Blueprint $table) {
            $table->id();
            $table->string('chassic_no')->nullable()->comment('Số khung');
            $table->string('engine_no')->nullable()->comment('Số máy');
            $table->string('model_code')->nullable()->comment('Model');
            $table->string('color')->nullable()->comment('Màu xe');
            $table->integer('quantity')->unsigned()->default(1)->comment('Số lượng');
            $table->integer('price')->unsigned()->nullable()->comment('Đơn giá');
            $table->foreignId('supplier_id')->nullable()->comment('Nhà cung cấp')->constrained('suppliers');
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
        Schema::dropIfExists('motorbikes');
    }
}
