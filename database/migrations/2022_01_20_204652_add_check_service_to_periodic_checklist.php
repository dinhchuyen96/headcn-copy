<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckServiceToPeriodicChecklist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periodic_checklist', function (Blueprint $table) {
            $table->string('check_service')->nullable()->comment('Kiểm tra trước khi tiếp nhận và khi sửa chữa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('periodic_checklist', function (Blueprint $table) {
            $table->dropColumn('check_service');
        });
    }
}
