<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHMSReceivePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hms_receive_plan', function (Blueprint $table) {
            $table->id();
            $table->string('chassic_no',255)->nullable();
            $table->string('engine_no',255)->nullable();
            $table->string('mtoc',255)->nullable();
            $table->string('model_category',255)->nullable();
            $table->string('model_name',255)->nullable();
            $table->string('model_type',255)->nullable();
            $table->string('warranty_end_date',255)->nullable();
            $table->string('warranty_expiry_km',255)->nullable();
            $table->string('selling_dealer',255)->nullable();
            $table->string('last_service_dealer',255)->nullable();
            $table->string('last_service_date',255)->nullable();
            $table->string('last_service_kms',255)->nullable();
            $table->string('last_service_division',255)->nullable();
            $table->string('next_service_date',255)->nullable();
            $table->string('whole_sale_order_no',255)->nullable();
            $table->string('customer_name',255)->nullable();
            $table->string('contact_number',255)->nullable();
            $table->string('account_name',255)->nullable();
            $table->string('transporter_code',255)->nullable();
            $table->string('transporter_name',255)->nullable();
            $table->string('head_code',255)->nullable();
            $table->string('payment_amount_by_dealer',255)->nullable();
            $table->string('truck_number',255)->nullable();
            $table->string('invoice_no',255)->nullable();
            $table->string('head_area',255)->nullable();
            $table->string('head_region',255)->nullable();
            $table->string('order_no',255)->nullable();
            $table->string('head_province',255)->nullable();
            $table->string('destination_location',255)->nullable();
            $table->string('model_code',255)->nullable();
            $table->string('type_code',255)->nullable();
            $table->string('option_code',255)->nullable();
            $table->string('color',255)->nullable();
            $table->string('color_code',255)->nullable();
            $table->string('manufacturing_date',255)->nullable();
            $table->string('vehicle_status',255)->nullable();
            $table->string('remarks',255)->nullable();
            $table->string('hvn_pack_invoice_number',255)->nullable();
            $table->string('invoice_date',255)->nullable();
            $table->string('warranty_start_date',255)->nullable();
            $table->string('inventory_location',255)->nullable();
            $table->string('battery_expiry_date',255)->nullable();
            $table->string('test_ride_vehicle',255)->nullable();
            $table->string('battery_id_number',255)->nullable();
            $table->string('stock_out_date_time',255)->nullable();
            $table->string('hvn_receipt_date',255)->nullable();
            $table->string('arrival_date',255)->nullable();
            $table->string('eta',255)->nullable();
            $table->string('qc_number',255)->nullable();
            $table->string('actual_arrival_date_time',255)->nullable();
            $table->string('actual_invoice_date',255)->nullable();
            $table->string('dispatch_head_name',255)->nullable();
            $table->string('last_service_type',255)->nullable();
            $table->string('head_invoice_date',255)->nullable();
            $table->string('hvn_lot_number',255)->nullable();
            $table->string('hvn_pack_invoice_date',255)->nullable();
            $table->string('warrenty_booklet_number',255)->nullable();
            $table->string('received_from',255)->nullable();
            $table->string('lot_dispatch_date_time',255)->nullable();
            $table->string('model_service_code',255)->nullable();

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
        Schema::dropIfExists('hms_receive_plan');
    }
}
