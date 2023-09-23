<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderIdAccessories extends Migration
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
         Schema::table('accessories', function (Blueprint $table) {
            $table->bigInteger('order_id')->nullable()->after('supplier_id')->change();
            $table->tinyInteger('status')->nullable()->default(0)->after('updated_at')->change();
            $table->dateTime('buy_date')->nullable()->after('status')->change();
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
        Schema::table('accessories', function (Blueprint $table) {

            $exists = function (string $column) use ($table) {
                return (Schema::hasColumn($table->getTable(), $column));
            };
            $addUnlessExists = function (string $type, string $name, array $parameters = [])
                use ($table, $exists) {
                    return $exists($name) ? null : $table->addColumn($type, $name, $parameters);
                };
            $dropIfExists = function (string $column) use ($table, $exists) {
                return $exists($column) ? $table->dropColumn($column) : null;
            };


            $dropIfExists('order_id');
            $dropIfExists('status');
            $dropIfExists('buy_date');
        });
    }
}
