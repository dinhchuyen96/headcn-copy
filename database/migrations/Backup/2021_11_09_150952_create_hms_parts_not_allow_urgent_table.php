<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHmsPartsNotAllowUrgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hms_parts_not_allow_urgent', function (Blueprint $table) {
            $table->id();
            $table->string('part_no',50)->nullable()->comment();
            $table->string('part_name_en',255)->nullable()->comment();
            $table->string('part_name_vn',255)->nullable()->comment();
            $table->string('category',50)->nullable()->comment();
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
        Schema::dropIfExists('hms_parts_not_allow_urgent');
    }
}
