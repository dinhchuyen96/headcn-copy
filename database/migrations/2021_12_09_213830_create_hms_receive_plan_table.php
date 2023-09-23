<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHmsReceivePlanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hms_receive_plan', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('chassic_no')->nullable();
			$table->string('engine_no')->nullable();
			$table->string('mtoc')->nullable();
			$table->string('model_category')->nullable();
			$table->string('model_name')->nullable();
			$table->string('model_type')->nullable();
			$table->string('warranty_end_date')->nullable();
			$table->string('warranty_expiry_km')->nullable();
			$table->string('selling_dealer')->nullable();
			$table->string('last_service_dealer')->nullable();
			$table->string('last_service_date')->nullable();
			$table->string('last_service_kms')->nullable();
			$table->string('last_service_division')->nullable();
			$table->string('next_service_date')->nullable();
			$table->string('whole_sale_order_no')->nullable();
			$table->string('customer_name')->nullable();
			$table->string('contact_number')->nullable();
			$table->string('account_name')->nullable();
			$table->string('transporter_code')->nullable();
			$table->string('transporter_name')->nullable();
			$table->string('head_code')->nullable();
			$table->string('payment_amount_by_dealer')->nullable();
			$table->string('truck_number')->nullable();
			$table->string('invoice_no')->nullable();
			$table->string('head_area')->nullable();
			$table->string('head_region')->nullable();
			$table->string('order_no')->nullable();
			$table->string('head_province')->nullable();
			$table->string('destination_location')->nullable();
			$table->string('model_code')->nullable();
			$table->string('type_code')->nullable();
			$table->string('option_code')->nullable();
			$table->string('color')->nullable();
			$table->string('color_code')->nullable();
			$table->string('manufacturing_date')->nullable();
			$table->string('vehicle_status')->nullable();
			$table->string('remarks')->nullable();
			$table->string('hvn_pack_invoice_number')->nullable();
			$table->string('invoice_date')->nullable();
			$table->string('warranty_start_date')->nullable();
			$table->string('inventory_location')->nullable();
			$table->string('battery_expiry_date')->nullable();
			$table->string('test_ride_vehicle')->nullable();
			$table->string('battery_id_number')->nullable();
			$table->string('stock_out_date_time')->nullable();
			$table->string('hvn_receipt_date')->nullable();
			$table->string('arrival_date')->nullable();
			$table->string('eta')->nullable();
			$table->string('qc_number')->nullable();
			$table->string('actual_arrival_date_time')->nullable();
			$table->string('actual_invoice_date')->nullable();
			$table->string('dispatch_head_name')->nullable();
			$table->string('last_service_type')->nullable();
			$table->string('head_invoice_date')->nullable();
			$table->string('hvn_lot_number')->nullable();
			$table->string('hvn_pack_invoice_date')->nullable();
			$table->string('warrenty_booklet_number')->nullable();
			$table->string('received_from')->nullable();
			$table->string('lot_dispatch_date_time')->nullable();
			$table->string('model_service_code')->nullable();
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
		Schema::drop('hms_receive_plan');
	}

}
