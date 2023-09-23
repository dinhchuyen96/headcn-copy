<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHmsServiceResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hms_service_results', function (Blueprint $table) {
            $table->id();
            $table->string('source',20)->nullable();
            $table->dateTime('sr_closed_date_time')->nullable();
            $table->string('job_card',50)->nullable();
            $table->string('description',255)->nullable();
            $table->string('account_phone',20)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('division',50)->nullable();
            $table->date('first_sale_date')->nullable();
            $table->string('reason_for_cancellation',255)->nullable();
            $table->string('selling_dealer',50)->nullable();
            $table->date('last_visit_date')->nullable();
            $table->string('last_visit_km')->nullable();
            $table->date('warranty_expiry_date')->nullable();
            $table->string('sr')->nullable();
            $table->string('service_type',50)->nullable();
            $table->string('frame',50)->nullable();
            $table->string('current_kms',50)->nullable();
            $table->string('last_name',50)->nullable();
            $table->string('first_name',50)->nullable();
            $table->string('company',20)->nullable();
            $table->string('status',20)->nullable();
            $table->string('operation',20)->nullable();
            $table->string('other_vehicle_types',50)->nullable();
            $table->string('plate',50)->nullable();
            $table->string('parts_price_list',22)->nullable();
            $table->date('service_receptionist_attended_date_time')->nullable();
            $table->date('booking_date')->nullable();
            $table->string('labour_rate_list',50)->nullable();
            $table->string('repeat_complaint_flag',1)->nullable();
            $table->string('repeat_complaint_reason',50)->nullable();
            $table->dateTime('sr_created_date_time')->nullable();
            $table->string('latest_contact_no',50)->nullable();
            $table->string('type_of_contact',50)->nullable();
            $table->string('committed',50)->nullable();
            $table->string('fuel_level',50)->nullable();
            $table->string('loyalty_card',50)->nullable();
            $table->string('service_campaign_recall_code',50)->nullable();
            $table->string('temporary_frame_no',50)->nullable();
            $table->string('observation',50)->nullable();
            $table->string('summary',50)->nullable();

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
        Schema::dropIfExists('hms_service_results');
    }
}
