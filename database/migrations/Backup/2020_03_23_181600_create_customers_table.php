<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id()->comment('Lưu thông tin khách hàng');
            $table->string('name', 50);
            $table->string('code', 50);
            // $table->unsignedBigInteger('created_by')->nullable();
            // $table->unsignedBigInteger('updated_by')->nullable();
            $table->integer('age')->nullable();
            $table->tinyInteger('sex')->nullable();
            $table->string('job', 100)->nullable();
            $table->string('identity_code', 100)->nullable();
            $table->date('birthday')->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('image', 100)->nullable();
            $table->text('address')->nullable();
            $table->text('district')->nullable();
            $table->text('city')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // $table->foreign('created_by')->references('id')->on('users')
            //     ->onUpdate('cascade')->onDelete('cascade');
            // $table->foreign('updated_by')->references('id')->on('users')
            //     ->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
