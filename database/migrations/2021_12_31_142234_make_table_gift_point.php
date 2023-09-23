<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeTableGiftPoint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_point', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
            $table->string('gift_name')->comment('Tên quà tặng');
            $table->integer('gift_point')->comment('Điểm tương ứng quà tặng');
			$table->timestamps();
            $table->softDeletes();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gift_point');
    }
}
