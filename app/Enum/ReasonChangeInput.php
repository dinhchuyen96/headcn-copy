<?php

namespace App\Enum;

final class ReasonChangeInput
{
    const ONE = 1;
    const TWO = 2;
    const THREE = 3;
    const FOUR = 4;
    const FIVE = 5;
    const SIX = 6;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::ONE:
                return 'Nhập người mua trả lại';
                break;
            case self::TWO:
                return 'Nhập bù trừ sai lệch kho';
                break;
            case self::THREE:
                return 'Xử lý sai (bugs)';
                break;
            case self::FOUR:
                return 'Lỗi thao tác';
                break;
            case self::FIVE:
                return 'Nhập khác';
                break;
            case self::SIX:
                return 'Khởi tạo tồn kho đầu kỳ';
                break;
            default:
                break;
        }
    }
}
