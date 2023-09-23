<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->comment('mã nhà cung cấp');
            $table->string('name')->nullable()->comment('mã phụ tùng');
            $table->string('code')->nullable()->comment('tên phụ tùng');
            $table->integer('quantity')->default(1)->comment('số lượng');
            $table->bigInteger('price')->nullable()->comment('đơn giá');

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
        Schema::dropIfExists('accessories');
    }
}
