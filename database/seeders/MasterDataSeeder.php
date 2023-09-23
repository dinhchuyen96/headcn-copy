<?php

namespace Database\Seeders;

use App\Models\MasterData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->carTypeList();
        $this->carNameModel();
        $this->carTypeModel();
        $this->carColorCode();
        $this->warehouseList();
    }

    protected function uniqueInsertMany($data)
    {
        foreach ($data as $item) {
            if(!isset($item['v_key']) || !isset($item['v_value'])) continue;
            MasterData::query()->updateOrCreate(
                [
                    'type' => $item['type'],
                    'v_value' => $item['v_value'],
                    'v_key'=>$item['v_key'],
                    'order_number'=>$item['order_number'],
                    'parent_id'=>$item['parent_id'] ?? null,
                ],
            );
        }
    }

    public function carTypeList()
    {
        $data = require_once(database_path('raw/CarTypeList.php'));
        $this->uniqueInsertMany($data);
        echo "Seeded: CarTypeList" . PHP_EOL;
    }
    public function carNameModel()
    {
        $data = require_once(database_path('raw/CarNameModel.php'));
        $this->uniqueInsertMany($data);
        echo "Seeded: CarNameModel" . PHP_EOL;
    }
    public function carTypeModel()
    {
        $data = require_once(database_path('raw/CarTypeModel.php'));
        $this->uniqueInsertMany($data);
        echo "Seeded: CarTypeModel" . PHP_EOL;
    }
    public function carColorCode()
    {
        $data = require_once(database_path('raw/CarColorCode.php'));
        $this->uniqueInsertMany($data);
        echo "Seeded: CarColorCode" . PHP_EOL;
    }
    public function warehouseList()
    {
        $data = require_once(database_path('raw/WarehouseList.php'));
        $this->uniqueInsertMany($data);
        echo "Seeded: WarehouseList" . PHP_EOL;
    }

}
