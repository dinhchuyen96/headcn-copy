<?php

namespace App\Console\Commands;

use App\Enum\EOrder;
use App\Enum\EOrderDetail;
use App\Enum\EWarehouse;
use App\Models\Accessory;
use App\Models\WarehouseTranferHistory;
use Illuminate\Console\Command;
use App\Models\AccessoryChangeLog;

class InventoryReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate Inventory Now';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $accessories = Accessory::with('orderDetails.order', 'accessoryChangeLogs')->get();
        $accessoryIds = $accessories->pluck('id')->toArray();
        $warehouseAll = WarehouseTranferHistory::where('tranfer_type', EWarehouse::PHU_TUNG)->get();
        foreach ($accessories as $accessory) {
            $newAccessoryIds = $accessories->where('code', $accessory->code)->pluck('id')->toArray();
            $in = $warehouseAll->where('to_warehouse_id', $accessory->warehouse_id)
            ->where('to_position_in_warehouse_id', $accessory->position_in_warehouse_id)
            ->whereIn('product_id', $newAccessoryIds)
            ->sum('quantity');
            $out = $warehouseAll->where('from_warehouse_id', $accessory->warehouse_id)
            ->where('from_position_in_warehouse_id', $accessory->position_in_warehouse_id)
            ->whereIn('product_id', $newAccessoryIds)
            ->sum('quantity');
            foreach($accessory->orderDetails->where('type', EOrderDetail::TYPE_NHAP)->where('status', EOrderDetail::STATUS_SAVED)->where('category', EOrderDetail::CATE_ACCESSORY) as $orderDetail) {
                if ($orderDetail->order->isvirtual == EOrder::REAL) {
                    $in += $orderDetail->quantity;
                }
            };

            foreach($accessory->orderDetails->whereIn('type', [EOrderDetail::TYPE_BANBUON, EOrderDetail::TYPE_BANLE])->where('status', EOrderDetail::STATUS_SAVED)->where('category', EOrderDetail::CATE_ACCESSORY) as $orderDetail) {
                if ($orderDetail->order->isvirtual == EOrder::REAL) {
                    $out += $orderDetail->quantity;
                }
            };
            foreach ($accessory->accessoryChangeLogs as $accessoryChangeLog) {
                if ($accessoryChangeLog->type == AccessoryChangeLog::NHAP) {
                    $in += $accessoryChangeLog->accessory_quantity;
                } else {
                    $out += $accessoryChangeLog->accessory_quantity;
                }
            }
            $accessory->quantity = $in - $out;
            $accessory->save();
        }
        return 0;
    }
}
