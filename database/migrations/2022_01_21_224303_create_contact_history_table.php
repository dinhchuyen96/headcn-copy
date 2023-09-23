<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_history', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('customer_id')->unsigned()->comment('Khách hàng liên lạc');
            $table->bigInteger('contact_method_id')->unsigned()->comment('Phương thức liên hệ');
            $table->text('note')->comment('Ghi chú việc liên hệ');
            $table->date('date_contact')->comment('Ngày liên lạc');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('contact_method_id')->references('id')->on('contact_method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_history');
    }
}
