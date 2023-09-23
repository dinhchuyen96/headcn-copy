<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableInstallmentCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installment_company', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('company_name')->comment('Tên công ty tài chính');
            $table->string('company_address')->nullable()->comment('Địa chỉ công ty tài chính');
            $table->timestamps();
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
        Schema::drop('installment_company');
    }
}
