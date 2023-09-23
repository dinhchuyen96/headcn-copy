<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAverageRevenueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('average_revenue', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('month')->nullable()->comment('Tháng');
            $table->integer('year')->nullable()->comment('Năm');
            $table->string('average_rate')->nullable()->comment('Tỉ lệ trung bình');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('average_revenue');
    }
}
