<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker;
class AccessoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker\Factory::create();

        $limit = 50;

        for ($i = 0; $i < $limit; $i++) {
            DB::table('accessories')->insert([
                'supplier_id'=>Supplier::pluck('id')->random(),
                'name' => $faker->name,
                'code' => $faker->randomNumber(),
                'quantity' => $faker->randomNumber(),
                'price' => $faker->randomNumber(),
                'created_at' => \Carbon\Carbon::now('Asia/Ho_Chi_Minh')
            ]);
        }
    }
}
