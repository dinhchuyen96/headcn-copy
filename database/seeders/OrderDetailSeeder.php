<?php

namespace Database\Seeders;

use App\Models\Accessory;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Seeder;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AccessoriesTableSeeder::class);
        $this->call(OrderSeeder::class);
        OrderDetail::factory()->times(50)->create();
    }
}
