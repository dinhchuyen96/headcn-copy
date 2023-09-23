<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIntoMotorbikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motorbikes', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->comment('khách hàng')->constrained('customers');
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
            $table->dropColumn('customer_id');
        });
    }
}
