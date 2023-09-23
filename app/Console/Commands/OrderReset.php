<?php

namespace App\Console\Commands;

use App\Enum\EOrderDetail;
use App\Models\Accessory;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Console\Command;

class OrderReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order_phutung:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $orders = Order::where('category', EOrderDetail::CATE_ACCESSORY)->get();
        $orderIds = $orders->pluck('id')->toArray();
        OrderDetail::whereIn('order_id', $orderIds)->delete();
        OrderDetail::whereNull('order_id')->delete();
        Accessory::whereIn('order_id', $orderIds)->delete();
        Order::whereIn('id', $orderIds)->delete();
        return 0;
    }
}
