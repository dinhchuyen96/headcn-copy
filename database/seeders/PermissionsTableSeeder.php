<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;

class PermissionsTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $urls = [];
        foreach (Route::getRoutes() as $value) {
            if (strpos($value->getName(), '.index') !== false) {
                $urls[] = $value->getName();
            }
        }
        if (!empty($urls)) {
            foreach ($urls as $url) {
                Permission::updateOrCreate([
                    'name' => $url,
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
