<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $role1 = Role::create([
            'name' => 'nv_kythuat',
            'guard_name' => 'web',
        ]);
        $role2 = Role::create([
            'name' => 'nv_kiemtra',
            'guard_name' => 'web',
        ]);
        $role3 = Role::create([
            'name' => 'administrator',
            'guard_name' => 'web',
        ]);
    }
}
