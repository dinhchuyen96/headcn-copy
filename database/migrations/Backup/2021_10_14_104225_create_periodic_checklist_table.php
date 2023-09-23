<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodicChecklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodic_checklist', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('head_code',255)->nullable()->comment('Mã head');
            $table->string('km',255)->nullable()->comment('Số km');
            $table->date('check_date')->nullable()->comment('Ngày kiểm tra');
            $table->foreignId('customers_id')->nullable()->constrained('customers')->comment('Map với customer');
            $table->foreignId('motorbikes_id')->nullable()->constrained('motorbikes')->comment('Map với motorbikes');
            $table->foreignId('orders_id')->nullable()->constrained('orders')->comment('Map với orders');
            $table->bigInteger('admin_id')->nullable()->comment('Nguoi tao');
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
        Schema::dropIfExists('periodic_checklist');
    }
}
