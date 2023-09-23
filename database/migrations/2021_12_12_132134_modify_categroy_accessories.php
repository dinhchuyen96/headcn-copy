<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCategroyAccessories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         //
         Schema::table('category_accessories', function (Blueprint $table) {
            $table->string('code',255)->nullable();
            $table->string('unit',20)->nullable();
            $table->string('parentcode',255)->nullable();
            $table->string('parentunit',20)->nullable();
            $table->Integer('warehouse_id')->nullable()->default(0);
            $table->Integer('position_in_warehouse_id')->nullable()->default(0);
            $table->Integer('changerate')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
