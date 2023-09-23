<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->nullable()->comment('customer  id');
            $table->string('customer_name',255)->nullable()->comment('customer name');
            $table->string('phone_number',255)->nullable()->comment('customer name');
            $table->string('address',255)->nullable()->comment('customer address');
            $table->bigInteger('type')->nullable()->comment('business type 1-xe 2-pt 3-dv');
            $table->string('mtoc')->nullable()->comment('mtoc');
            $table->string('partno')->nullable()->comment('partno');
            $table->string('jobcode')->nullable()->comment('jobcode');
            $table->bigInteger('price')->nullable()->default(0)->comment('price');
            $table->string('issued_by')->nullable()->comment('issued user');
            $table->string('created_by')->nullable()->comment('created by');
            $table->string('updated_by')->nullable()->comment('updated by');
            $table->string('note')->nullable()->comment('note and comment');
            $table->softDeletes();
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
        Schema::dropIfExists('proposal');
    }
}
