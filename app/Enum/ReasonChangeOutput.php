<?php

namespace App\Enum;

final class ReasonChangeOutput
{
    const ONE = 1;
    const TWO = 2;
    const THREE = 3;
    const FOUR = 4;
    const FIVE = 5;
    const SIX = 6;
    const SEVEN = 7;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::ONE:
                return 'Xuất trả lại người bán';
                break;
            case self::TWO:
                return 'Xuất bù trừ sai lệch kho';
                break;
            case self::THREE:
                return 'Xuất hủy';
                break;
            case self::FOUR:
                return 'Xuất khuyến mãi';
                break;
            case self::FIVE:
                return 'Xử lý sai (bugs)';
                break;
            case self::SIX:
                return 'Lỗi thao tác';
                break;
            case self::SEVEN:
                return 'Xuất khác';
                break;
            default:
                break;
        }
    }
}
