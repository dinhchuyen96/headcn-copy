<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeForTableMotobike extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorbikes', function (Blueprint $table) {
            $table->integer('is_out')->default(0)->comment('0: Xe của head, 1 : Xe ngoài head');
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
            $table->dropColumn('is_out');
        });
    }
}
