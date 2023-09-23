<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableMtoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtoc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('actflg')->nullable();
            $table->string('mtocd')->nullable();
            $table->string('col_cd')->nullable();
            $table->string('model_code')->nullable();
            $table->string('type_code')->nullable();
            $table->string('option_code')->nullable();
            $table->string('color_code')->nullable();
            $table->string('color_name')->nullable();
            $table->string('odrflg')->nullable();
            $table->string('payflg')->nullable();
            $table->string('rptflg_s')->nullable();
            $table->string('mdfusr')->nullable();
            $table->string('mdfwks')->nullable();
            $table->dateTime('lstmdf', $precision = 0)->nullable();
            $table->string('ctdusr')->nullable();
            $table->bigInteger('suggest_price')->default(0)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('mtoc');
    }
}
