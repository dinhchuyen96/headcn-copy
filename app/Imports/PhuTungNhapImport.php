<?php

namespace App\Imports;

use App\Models\Accessory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Supplier;
use App\Models\PositionInWarehouse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use App\Enum\EOrder;
use Carbon\Carbon;
use DateTime;


class PhuTungNhapImport implements ToCollection, WithStartRow
{
    //test cd/id
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $codes = $orderIds = $orderData = [];
        $supplierCodes = Supplier::all()->pluck('id', 'code');
        $positions = PositionInWarehouse::with('warehouse')->whereHas('warehouse')->get();
        $positionArr = $accessoryArr = [];
        foreach ($positions as $position) {
            $positionArr[$position->warehouse->name][$position->name] = [
                'warehouse_id' => $position->warehouse_id,
                'id' => $position->id,
            ];
        }
        $arrInserts = [];
        $arrOrderInserts = [];
        $accessories = Accessory::select('id', 'code', 'quantity', 'supplier_id', 'position_in_warehouse_id')->get();
        $accessoriesMaxId = $accessories->max('id') + 1000;
        foreach ($accessories as $accessory) {
            $accessoryArr[$accessory->code][$accessory->supplier_id][$accessory->position_in_warehouse_id] = [
                'quantity' => $position->quantity,
                'id' => $position->id,
            ];
        }
        $now = Carbon::now()->format('Y-m-d');
        $authId = auth()->id();
        foreach ($collection as $row) {
            if ($row[0]) {
                $supplier_id = @$supplierCodes[$row[0]];
                if (!array_key_exists($row[0], $codes)) {
                    $order = Order::create([
                        'supplier_id' => $supplier_id,
                        'admin_id' => $authId,
                        'order_no' => $row[2],
                        'category' => EOrder::CATE_ACCESSORY,
                        'order_type' => EOrder::ORDER_TYPE_BUY,
                        'type' => EOrder::TYPE_NHAP,
                        'status' => EOrder::STATUS_UNPAID,
                    ]);
                    $codes[$row[0]] = $order->id;
                    $orderIds[] = $order->id;
                    $orderData[$codes[$row[0]]]['id'] = $codes[$row[0]];
                }

                $position = $positionArr[trim($row[12])][trim($row[13])];
                $warehouseImport = $position['warehouse_id'];
                $positionInWarehouseImport = $position['id'];
                $accessory = @$accessoryArr[$row[4]][$supplier_id][$row[13]];

                $accessoryId = @$accessory['id'] ? $accessory['id'] : $accessoriesMaxId;
                if ($accessory) {
                    $arrInserts[] = [
                        'id' => $accessoryId,
                        'code' => $row[4],
                        'name' => $row[5],
                        'price' => $row[10],
                        'admin_id' => $authId,
                        'supplier_id' => $supplier_id,
                        'warehouse_id' => $warehouseImport,
                        'position_in_warehouse_id' => $positionInWarehouseImport,
                        'quantity' => $accessory['quantity'] + $row[6],
                        'updated_at' => Carbon::now(),
                    ];
                } else {
                    $arrInserts[] = [
                        'id' => $accessoriesMaxId,
                        'code' => $row[4],
                        'name' => $row[5],
                        'price' =>isset($row[10]) ? intval($row[10]) : 0,
                        'admin_id' => $authId,
                        'supplier_id' => $supplier_id,
                        'warehouse_id' => $warehouseImport,
                        'position_in_warehouse_id' => $positionInWarehouseImport,
                        'quantity' => isset($row[6]) ? intval($row[6]) :0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    $accessoriesMaxId++;
                }
                $arrOrderInserts[] = [
                    'product_id' => $accessoryId,
                    'code' => $row[4],
                    'quantity' => $row[6],
                    'name' => $row[5],
                    'listed_price' => $row[10],
                    'actual_price' => $row[10],
                    'price' => $row[10],
                    'admin_id' => $authId,
                    'status' => 1,
                    'category' => 1,
                    'type' => 3,
                    'buy_date' => $row[1] ? Carbon::createFromFormat('d/m/Y', $row[1])->format('Y-m-d') : $now,
                    'order_number' => $row[2],
                    'warehouse_id' => $warehouseImport,
                    'position_in_warehouse_id' => $positionInWarehouseImport,
                    'order_id' => $codes[$row[0]],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

                $orderData[$codes[$row[0]]]['total_items'] = (@$orderData[$codes[$row[0]]]['total_items'] ?: 0) + ($row[6] ?: 0);
                $orderData[$codes[$row[0]]]['total_money'] = (@$orderData[$codes[$row[0]]]['total_money'] ?: 0) + ($row[10] ?: 0);
            }
        }
        Accessory::upsert($arrInserts, ['id'], ['code', 'name', 'price', 'admin_id', 'supplier_id', 'warehouse_id', 'position_in_warehouse_id', 'quantity']);
        OrderDetail::insert($arrOrderInserts);
        Order::upsert($orderData, ['id'], ['total_items', 'total_money']);
    }

    public function startRow(): int
    {
        return 2;
    }
}
