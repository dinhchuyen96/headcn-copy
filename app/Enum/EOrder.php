<?php

namespace App\Enum;

class EOrder
{
    const CATE_ACCESSORY = 1; // phu tung
    const CATE_MOTORBIKE  = 2; // xe may
    const CATE_MAINTAIN  = 3; // bảo dưỡng
    const CATE_REPAIR  = 4; // sửa chữa
    const OTHER  = 5; // Nợ tồn (số tiền thực thu < số tiền cần thu)
    const SERVICE_OTHER  = 6; // Dịch vụ khác
    const MACHINING  = 7; // Gia công


    const TYPE_BANBUON  = 1; // bán buôn
    const TYPE_BANLE  = 2; // bán lẻ
    const TYPE_NHAP  = 3; // nhập hàng

    const STATUS_PAID  = 1; //đã thanh toán
    const STATUS_UNPAID  = 2; // chưa thanh toán

    const ORDER_TYPE_SELL  = 1; // bán hàng
    const ORDER_TYPE_BUY  = 2; // mua hàng

    const CASH_TO_BANK =100; //nop tien NH
    const BANK_TO_CASH =101; //rut tien NH vê quy
    const BANK_TO_BANK =102; //chuyen tien noi bo

    const REAL = 0;
    const VIRTUAL = 1;

}
