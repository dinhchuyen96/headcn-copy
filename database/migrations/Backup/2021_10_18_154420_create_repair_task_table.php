<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repair_task', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('content')->nullable()->comment('Nội dung công việc');
            $table->bigInteger('price')->nullable()->comment('Tiền công');
            $table->foreignId('orders_id')->nullable()->constrained('orders')->comment('Map với orders');
            $table->bigInteger('admin_id')->nullable()->comment('Người tạo');
            $table->tinyInteger('status')->nullable()->comment('1: đã lưu, 0:lưu nháp');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repair_task');
    }
}
