<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResultToDichVu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repair_bill', function (Blueprint $table) {
            $table->text('result_repair')->nullable()->comment('Ghi chú sau kiểm tra');
        });
        Schema::table('periodic_checklist', function (Blueprint $table) {
            $table->text('result_repair')->nullable()->comment('Ghi chú sau kiểm tra');
            $table->text('content_request')->nullable()->comment('Triệu chứng/Yêu cầu KT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repair_bill', function (Blueprint $table) {
            $table->dropIfExists('result_repair');
        });
        Schema::table('periodic_checklist', function (Blueprint $table) {
            $table->dropIfExists('result_repair');
            $table->dropIfExists('content_request');
        });
    }
}
