<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMotorbikesIdToRepairBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repair_bill', function (Blueprint $table) {
            $table->foreignId('motorbikes_id')->nullable()->constrained('motorbikes')->comment('Map với motorbikes');
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
            $table->dropColumn('motorbikes_id');
        });
    }
}
