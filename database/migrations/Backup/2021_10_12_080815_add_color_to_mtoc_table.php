<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColorToMtocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mtoc', function (Blueprint $table) {
            $table->string('color', 255)->nullable()->comment('Tên màu xe');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mtoc', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
}
