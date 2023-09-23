<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeKtdkAndScttTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('work_content', function (Blueprint $table) {
            $table->integer('type')->default(0)->comment('0. Công việc trong, 1 Công việc ngoài');
        });
        Schema::table('repair_bill', function (Blueprint $table) {
            $table->text('content_suggest')->nullable()->comment('Tư vấn sửa chữa');
            $table->boolean('before_repair')->default(false)->comment('Trước sửa chữa');
            $table->boolean('after_repair')->default(false)->comment('Sau sửa chữa');
            $table->boolean('not_need_wash')->default(false)->comment('Không cần rửa xe');
        });
        Schema::table('periodic_checklist', function (Blueprint $table) {
            $table->text('content_suggest')->nullable()->comment('Tư vấn sửa chữa');
            $table->boolean('before_repair')->default(false)->comment('Trước sửa chữa');
            $table->boolean('after_repair')->default(false)->comment('Sau sửa chữa');
            $table->boolean('not_need_wash')->default(false)->comment('Không cần rửa xe');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_content', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('repair_bill', function (Blueprint $table) {
            $table->dropColumn('content_suggest');
            $table->dropColumn('before_repair');
            $table->dropColumn('after_repair');
            $table->dropColumn('not_need_wash');
        });
        Schema::table('periodic_checklist', function (Blueprint $table) {
            $table->dropColumn('content_suggest');
            $table->dropColumn('before_repair');
            $table->dropColumn('after_repair');
            $table->dropColumn('not_need_wash');
        });
    }
}
