<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;

class SqlFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(1000);
        $path = database_path('sql/ex_district.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }

}