<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMtocIdAndHeadIdToMotorbikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorbikes', function (Blueprint $table) {
            $table->bigInteger('head_id')->nullable()->comment('Head nhận xe');
            $table->foreignId('mtoc_id')->nullable()->constrained('mtoc')->comment('Map với MTOC');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motorbikes', function (Blueprint $table) {
            $table->dropColumn('head_id');
            $table->dropColumn('mtoc_id');
        });
    }
}
