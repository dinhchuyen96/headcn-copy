<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

class ChangeTableRepairBill extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repair_bill', function (Blueprint $table) {
            $table->dropColumn(['bill_number', 'in_factory_date']);
            $table->text('content_request')->nullable()->comment('Triệu chứng/Yêu cầu KT');
            $table->string('code_request')->comment('Mã SR');
            $table->integer('service_type')->comment('Loại sửa chữa');
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
            $table->dropColumn(['content_request', 'code_request', 'service_type']);
            $table->string('bill_number')->comment('Số phiếu');
            $table->string('in_factory_date')->comment('Ngày giờ vào xưởng');
        });
    }
}
