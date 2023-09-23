<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtoDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mto_data', function (Blueprint $table) {
            $table->id();
            $table->string('ACTFLG',255)->nullable();
            $table->string('MTOCD',255)->nullable();
            $table->string('MODEL_CODE',255)->nullable();
            $table->string('TYPE_CODE',255)->nullable();
            $table->string('OPTION_CODE',255)->nullable();
            $table->string('MODEL_CODE_S',255)->nullable();
            $table->string('MODEL_NAME_S',255)->nullable();
            $table->string('MODEL_CODE_W',255)->nullable();
            $table->string('MODEL_NAME_W',255)->nullable();
            $table->string('F_HEADER',255)->nullable();
            $table->string('E_HEADER',255)->nullable();
            $table->string('SRTORD',255)->nullable();
            $table->string('CLS_CODE',255)->nullable();
            $table->string('ODRFLG',255)->nullable();
            $table->string('PAYFLG',255)->nullable();
            $table->string('RPTFLG_S',255)->nullable();
            $table->string('RPTFLG_W',255)->nullable();
            $table->string('MDFUSR',255)->nullable();
            $table->string('MDFWKS',255)->nullable();
            $table->string('LSTMDF',255)->nullable();
            $table->string('MFGFLG',255)->nullable();
            $table->string('CTDUSR',255)->nullable();
            $table->string('CTDWKS',255)->nullable();
            $table->string('CTDDTM',255)->nullable();
            $table->string('CTDPGM',255)->nullable();
            $table->string('MDFPGM',255)->nullable();
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
        Schema::dropIfExists('mto_data');
    }
}
