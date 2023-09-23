<?php

namespace App\Enum;

use BenSampo\Enum\Enum;

class EUserPosition extends Enum
{
    const GIAM_DOC = 1;
    const NV_BAN_HANG  = 2;
    const NV_KI_THUAT  = 18;
    const NV_KIEM_TRA  = 18;
    const NV_SUA_CHUA  = 18;
    const NV_KE_TOAN  = 4;
    const NV_KIEM_KHO  = 7;
    const NV_THU_QUY  = 8;
    const TRUONG_PHONG  = 9;
    const PHO_PHONG  = 10;
    const KHAC  = 11;
    const CSKH  = 19;
    const NV_BAN_HANG_XE_MAY  = 15;
    const NV_BAN_HANG_PHU_TUNG  = 16;
    const NV_BAN_HANG_DICH_VU  = 17;


    public static function getDescription($value): string
    {
        switch ($value) {
            case self::GIAM_DOC:
                return 'Giám đốc';
                break;
            case self::NV_BAN_HANG:
                return 'Nhân viên bán hàng';
                break;
            case self::NV_KI_THUAT:
                return 'Nhân viên kĩ thuật';
                break;
            case self::NV_SUA_CHUA:
                return 'Nhân viên sửa chữa';
                break;
            case self::NV_KIEM_TRA:
                return 'Nhân viên kiểm tra';
                break;
            case self::NV_KE_TOAN:
                return 'Nhân viên kế toán';
                break;
            case self::NV_KIEM_KHO:
                return 'Nhân viên kiểm kho';
                break;
            case self::NV_THU_QUY:
                return 'Nhân viên thủ quỹ';
                break;
            case self::TRUONG_PHONG:
                return 'Trường phòng';
                break;
            case self::PHO_PHONG:
                return 'Phó phòng';
                break;
            case self::KHAC:
                return 'Khác';
                break;

            default:
                return '';
                break;
        }
    }
    public static function toEnumArray()
    {
        $arr = [];
        foreach (self::getValues() as $val) {
            $arr[$val] = self::getDescription($val);
        }
        return $arr;
    }
}
