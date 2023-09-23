<?php

namespace App\Enum;

final class ReasonType
{
    const INPUT = 1;
    const OUTPUT = 2;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::INPUT:
                return 'Nhập ngoại lệ';
                break;
            case self::OUTPUT:
                return 'Xuất ngoại lệ';
                break;
            default:
                break;
        }
    }
}
