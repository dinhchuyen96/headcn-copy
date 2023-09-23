<?php

namespace Database\Seeders;

use App\Models\Accessory;
use App\Models\Motorbike;
use App\Models\OrderDetail;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(CreateAdminSeeder::class);
        $this->call(MotorbikesSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(MasterDataSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(OrderDetailSeeder::class);
        $this->call(HMSSeeder::class);
    }
}
