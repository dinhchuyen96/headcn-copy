<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColSuggestPriceToMtocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mtoc', function (Blueprint $table) {
            $table->bigInteger('suggest_price');
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
            $table->dropColumn('suggest_price');
        });
    }
}
