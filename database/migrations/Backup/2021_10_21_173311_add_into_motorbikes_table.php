<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntoMotorbikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorbikes', function (Blueprint $table) {
            $table->string('model_list')->nullable()->comment('Danh mục đời xe');
            $table->string('model_type')->nullable()->comment('Phân loại đời xe');
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
            $table->dropColumn('model_list');
            $table->dropColumn('model_type');
        });
    }
}
