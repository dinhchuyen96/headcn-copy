<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHmsPartReceivePlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hms_part_receive_plan', function (Blueprint $table) {
            $table->id();
            $table->string('head_code',5)->nullable(); // ma head duoc phan bo
            $table->string('order_number',50); // so order po number
            $table->dateTime('order_date'); // ngay tao order
            $table->string('order_type',50); //purchase order hay order thương
            $table->string('supplier_code',10); //supplier code
            $table->string('supplier_name',250); //supplier name
            $table->string('invoice_number',50)->nullable(); //so hoa don billing number
            $table->dateTime('create_invoice_date')->nullable(); //ngay gio tao hoa don
            $table->dateTime('invoice_date')->nullable(); // ngay gio hoa don
            $table->string('shipment_number',50)->nullable(); // ma giao hang
            $table->string('delivery_number',50)->nullable(); //so lenh giao
            $table->string('part_no',250); //ma phu tung
            $table->string('part_name',250); //ten phu tung
            $table->string('part_type',250); //phan loai phu tung
            $table->string('box_number',50)->nullable(); //ma so hop
            $table->bigInteger('allocated_qty')->default(0); //so luong phan bo
            $table->bigInteger('receive_price')->default(0); //gia nhap
            $table->string('created_by',50); //nguoi lap don
            $table->dateTime('created_date'); //nguoi lap don
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
        Schema::dropIfExists('hms_part_receive_plan');
    }
}
