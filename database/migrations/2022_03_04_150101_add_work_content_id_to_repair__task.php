<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkContentIdToRepairTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repair_task', function (Blueprint $table) {
            $table->bigInteger('work_content_id')->nullable()->comment('Nội dung công việc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repair_task', function (Blueprint $table) {
            $table->dropIfExists('work_content_id');
        });
    }
}
