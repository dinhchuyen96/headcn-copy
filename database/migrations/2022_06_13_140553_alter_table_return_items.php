<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableReturnItems extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('return_items', function (Blueprint $table) {
            if (!Schema::hasColumn('return_items', 'item_price')) {
                $table->bigInteger('item_price')->default(0)->comment('gia nhap lai');
            }
            if (!Schema::hasColumn('return_items', 'paid_status')) {
                $table->bigInteger('paid_status')->default(0)->comment('trang thai thanh toan  0 chua 1 da tt');
            }
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_items', function (Blueprint $table) {
            $table->dropColumn('item_price');
            $table->dropColumn('paid_status');
        });
    }
}
