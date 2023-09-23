<?php

namespace App\Enum;

class EOrderDetail
{
    const STATUS_SAVED  = 1; // đã lưu
    const STATUS_SAVE_DRAFT   = 0; // lưu nháp
    const STATUS_SAVE_DRAFT_IMPORT_BANBUON   = 2; // lưu nháp import bán buôn xe máy
    const STATUS_SAVE_DRAFT_IMPORT_BUY   = 3; // lưu nháp import nhập xe máy


    const ATROPHY_ACCESSORY  = 1; // hao mòn phụ tùng
    const NOT_ATROPHY_ACCESSORY  = 0; // không hao mòn phụ tùng

    const CATE_ACCESSORY = 1; // phu tung
    const CATE_MOTORBIKE  = 2; // xe may
    const CATE_MAINTAIN  = 3; // bảo dưỡng
    const CATE_REPAIR  = 4; // sửa chữa

    const TYPE_BANBUON  = 1; // bán buôn
    const TYPE_BANLE  = 2; // bán lẻ - sửa chữa thông thường phụ tùng
    const TYPE_NHAP  = 3; // nhập hàng

    const SUPPLIER_TYPE  = 1; // ncc HVN
}
