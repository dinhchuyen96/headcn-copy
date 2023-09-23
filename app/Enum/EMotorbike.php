<?php

namespace App\Enum;

class EMotorbike
{
    const VITUAL = 4; // bán ảo
    const NEW_INPUT = 3; // mới nhập
    const PROCESS = 2; // chờ xử lý (lưu nháp bán)
    const SOLD = 1; // đã bán


    const NOT_OUT = 0; // xe trong
    const  OUT = 1; // xe ngoài
}
