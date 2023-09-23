<div>
    <table style="m">
        <tr>
            <td colspan="3">
                <div class="content">
                    <div class="head">
                        <div class="left-store-print">
                            <span><strong>Cửa hàng do Honda ủy nhiệm HEAD</strong></span>
                            <br>
                            <span><strong>{{ $headName }}</strong></span>
                            <br>
                            <span><strong>{{ $headAddress }}</strong></span>
                            <br>
                            <span><strong>Số điện thoại:{{ $headPhoneNumber }}</strong></span>
                            <br>
                            <span><strong>Hotline:{{ $headHotline }}</strong></span>
                            <br>
                            <span><strong>Email:{{ $headEmail }}</strong></span>
                        </div>
                        <div class="content-head-print">
                            <h1>PHIẾU SỬA CHỮA</h1>
                        </div>
                        <div class="right-head-print">
                            <div style="border: 1px solid black;margin-top: 40%;padding: 6% 0 6% 0;">
                                <span><strong>STT:158</strong></span>
                            </div>
                            <span>Mức nhiên liệu: E | F </span>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td rowspan="2" style="width: 20.00%;">Tên khách hàng:<br>{{ $customer->name }}</td>
            <td rowspan="3" style="width: 33.33%;">Địa chỉ:{{ $address }}</td>
            <td style="width: 40.00%;">Thời gian nhận xe:<br>{{ $in_factory_date }} </td>
        </tr>
        <tr>
            <td>Thời gian trả xe dự kiến:</td>
        </tr>
        <tr>
            <td>Số điện thoại:<br>{{ $customer->phone }}</td>
            <td>Thời gian trả xe thực tế:<br></td>
        </tr>
        <tr>
            <td>Loại xe:<br>{{ $motorbike->model_code }}</td>
            <td>Số khung:<br>{{ $motorbike->chassic_no }}</td>
            @if ($repairPreodic != null)
                <td>Số Km: {{ empty($repairPreodic->km) ? '' : number_format($repairPreodic->km) }}</td>
            @else
                <td>Số Km: {{ empty($repairBill->km) ? '' : number_format($repairBill->km) }}</td>
            @endif

        </tr>
        <tr>
            <td>Biển số: {{ $motorbike->motor_numbers }}</td>
            <td>Số máy: {{ $motorbike->engine_no }}</td>
            <td>Ngày mua:{{ reFormatDate($motorbike->sell_date) }}</td>
        </tr>
    </table>



    @php $indexRowStart = 1 @endphp
    <table style="margin-top: 10px;">
        <tr>
            <th>Yêu cầu của khách hàng</th>
            <th>Tư vấn sửa chữa</th>
            <th>Rửa xe</th>
        </tr>
        <tr>
            <td rowspan="3">{{ $customerServiceRequest }}</td>
            <td rowspan="3">{{ $contentSuggest }}</td>
            <td><input type="checkbox" wire:model="beforeRepair" value="{{ $beforeRepair }}">Trước sửa chữa</td>
        </tr>
        <tr>
            <td><input type="checkbox" wire:model="afterRepair" value="{{ $afterRepair }}">Sau sửa chữa</td>
        </tr>
        <tr>
            <td><input type="checkbox" wire:model="notNeedWash" value="{{ $notNeedWash }}">Không cần rửa xe</td>
        </tr>
    </table>
    <div style="text-align: center;">Kiểm tra/tư vấn các phụ tùng hao mòn Ký Hiệu: O:OK -D:Điều chỉnh -T:Thay
        thế
        -V:Vệ sinh -B:Bôi trơn</div>
    <div>
        <table style="width: 100%;text-align: center; margin-top:2.5rem" border="1">
            <tbody>
                <tr>
                    <td colspan="12"><strong>Kiểm tra phụ tùng tại quầy tiếp nhận</strong></td>
                    <td colspan="12"><strong>Kiểm tra phụ tùng tại xưởng dịch vụ</strong></td>
                </tr>
                <tr>
                    <td rowspan="2">Phụ tùng</td>
                    <td colspan="5">Kiểm tra (✔)</td>
                    <td rowspan="2">Phụ tùng</td>
                    <td colspan="5">Kiểm tra (✔)</td>
                    <td rowspan="2">Phụ tùng</td>
                    <td colspan="5">Kiểm tra (✔)</td>
                    <td rowspan="2">Phụ tùng</td>
                    <td colspan="5">Kiểm tra (✔)</td>
                </tr>
                <tr>
                    <td>O</td>
                    <td>D</td>
                    <td>T</td>
                    <td>V</td>
                    <td>B</td>
                    <td>O</td>
                    <td>D</td>
                    <td>T</td>
                    <td>V</td>
                    <td>B</td>
                    <td>O</td>
                    <td>D</td>
                    <td>T</td>
                    <td>V</td>
                    <td>B</td>
                    <td>O</td>
                    <td>D</td>
                    <td>T</td>
                    <td>V</td>
                    <td>B</td>
                </tr>
                <tr style="text-align: left;">
                    <td>Dầu phanh</td>
                    @for ($i = 1; $i <= 5; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Lốp trước</td>
                    @for ($i = 6; $i <= 10; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Dây phanh</td>
                    @for ($i = 11; $i <= 15; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Côn</td>
                    @for ($i = 16; $i <= 20; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                </tr>
                <tr style="text-align: left;">
                    <td>Phanh trước</td>
                    @for ($i = 21; $i <= 25; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Lốp sau</td>
                    @for ($i = 26; $i <= 30; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Dầu số</td>
                    @for ($i = 31; $i <= 35; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Chổi than</td>
                    @for ($i = 36; $i <= 40; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                </tr>
                <tr style="text-align: left;">
                    <td>Phanh sau</td>
                    @for ($i = 41; $i <= 45; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Dầu máy</td>
                    @for ($i = 46; $i <= 50; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Dây đai</td>
                    @for ($i = 51; $i <= 55; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Họng ga</td>
                    @for ($i = 56; $i <= 60; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                </tr>
                <tr style="text-align: left;">
                    <td>Bóng đèn</td>
                    @for ($i = 61; $i <= 65; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Làm mát</td>
                    @for ($i = 66; $i <= 70; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Ắc quy</td>
                    @for ($i = 71; $i <= 75; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Bugi</td>
                    @for ($i = 76; $i <= 80; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                </tr>
                <tr style="text-align: left;">
                    <td>Công tắc</td>
                    @for ($i = 81; $i <= 85; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Xích</td>
                    @for ($i = 86; $i <= 90; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Lọc gió</td>
                    @for ($i = 91; $i <= 95; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="text-align: left;">
                    <td>Còi</td>
                    @for ($i = 96; $i <= 100; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Công tơ mét</td>
                    @for ($i = 101; $i <= 105; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td>Nhông xích</td>
                    @for ($i = 106; $i <= 110; $i++)
                        <td><input type="checkbox" wire:model="checkService" value="{{ $i }}" /></td>
                    @endfor
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        Chú ý: Các hạng mục đã được kiểm tra, đánh dấu mà không được thay thế khách hàng cần theo dõi
        và
        thay thế sớm nhất để đảm bảo tính năng an toàn cũng như khả năng vận hành
    </div>
    <table>
        <tr>
            <th>STT</th>
            <th>Tên phụ tùng/ Nội dung công việc</th>
            <th>Mã phụ tùng</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Tổng tiền phụ tùng(1)</th>
            <th>Tiền công(2)</th>
            <th>Tổng tiền công + phụ tùng(1+2)</th>

            @foreach ($orderDetail as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->accessorie->name }}</td>
            <td>{{ $item->accessorie->code }}</td>
            <td>{{ number_format($item->price) }}</td>
            <td>{{ number_format($item->quantity) }}</td>
            @if ($item->actual_price)
                <td>{{ number_format($item->actual_price) }} (KM: {{ $item->promotion }}%)</td>
            @else
                <td>
                    {{ round(($item->price * $item->quantity * (100 - $item->promotion)) / 100) }} (KM:
                    {{ $item->promotion }}%)
                </td>
            @endif
            <td>0</td>
            @if ($item->actual_price)
                <td>{{ number_format($item->actual_price) }} (KM: {{ $item->promotion }}%)</td>
            @else
                <td>
                    {{ round(($item->price * $item->quantity * (100 - $item->promotion)) / 100) }} (KM:
                    {{ $item->promotion }}%)
                </td>
            @endif
        </tr>
        @endforeach
        @foreach ($repairTask as $key => $item)
            <tr>
                <td>{{ $key + 1 + count($orderDetail) }}</td>
                <td>{{ $item->workContent->name }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>0</td>
                <td>{{ number_format(round(($item->price * (100 - $item->promotion)) / 100)) }}
                    (KM:
                    {{ $item->promotion }}%)
                </td>
                <td>{{ number_format(round(($item->price * (100 - $item->promotion)) / 100)) }}
                    (KM:
                    {{ $item->promotion }}%)
                </td>
            </tr>
        @endforeach


        <div style="display: none">{{ $it = count($orderDetail) + count($repairTask) }}</div>
        @if ($it <= 20)
            @for ($items = $it + 1; $items <= 20; $items++)
                <tr>
                    <td>{{ $items }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor
        @endif
        <tr>
            <td colspan="7"><span style="text-align: center;">Tổng tiền = Tổng
                    tiền phụ tùng thay thế(1) + Tổng tiền công việc sửa chữa(2)</span></td>
            <td colspan="2"><span style="text-align: center;">{{ number_format($total) }}</span></td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <br>
    <div class="footer-print">
        <div class="footer-info">
            <div class="table-left">
                <table>
                    <tr>
                        <th>Xác nhận lấy lại phụ tùng cũ</th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="checkbox"> <span><strong>Khách hàng lấy lại phụ tùng cũ</strong></span>
                            <br>
                            <input type="checkbox"> <span><strong>Khách hàng không lấy lại phụ tùng cũ</strong></span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="table-right">
                <table>
                    <tr>
                        <th colspan="3">Thời gian kiểm tra lần tới</th>
                    </tr>
                    <tr>
                        <th>Hạng mục</th>
                        <th>Thời gian</th>
                        <th>KM</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="head-table-print">
            <span>Chú ý:Hạng mục không kiểm tra</span>
        </div>
        <div class="footer-info">
            <div class="table-left">
                <table>
                    <tr>
                        <th rowspan="3">STT</th>
                        <th rowspan="3">Kiểm tra cuối</th>
                        <th colspan="3">Xác nhận (x)</th>
                    <tr>
                        <th>Sửa chữa nặng</th>
                        <th>Sửa chũa nhỏ</th>
                        <th>Thay dầu</th>
                    </tr>
                    </tr>
                    <tr>
                    <tr>
                        <td><span>1</span></td>
                        <td><span>Mức dầu rò rỉ dầu bu lông xả dầu</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span>2</span></td>
                        <td><span>Ống dẫn nhiên liệu(rò rỉ,long ốc,...)</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span>3</span></td>
                        <td><span>Vật dễ cháy và vị trí các góc khuất(chuột Rẻ...)</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span>4</span></td>
                        <td><span>Hệ thống phanh(Hành trình tự do, hoạt động của phanh,...)</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span>5</span></td>
                        <td><span>Đèn lái, đèn phanh, đèn tín hiệu,còi,…</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span>6</span></td>
                        <td><span>Ốc và đai ốc, đai ốc trục trước và trục sau</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span>7</span></td>
                        <td><span>Xác nhận hạng mục đã sửa chữa</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span>8</span></td>
                        <td><span>Kiểm tra ngoại quan(rửa xe,...)</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span>9</span></td>
                        <td><span>Khả năng vận hành và hoạt động họng ga</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span>10</span></td>
                        <td><span>Tốc độ cầm chừng</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span>11</span></td>
                        <td><span>Chạy thử</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    </tr>
                </table>
            </div>
            <div class="table-right">
                <table>
                    <tr>
                        <th colspan="2">Khách hàng xác nhận</th>
                        <th rowspan="2">Tiếp nhận</th>
                        <th rowspan="2">Kĩ thuật viên</th>
                        <th rowspan="2">Kiểm tra cuối</th>
                    </tr>
                    <tr>
                        <th>Trước sửa chữa</th>
                        <th>Sau sửa chữa</th>
                    </tr>
                    <tr>
                        <td rowspan="3" style="height: 80px"></td>
                        <td rowspan="3" style="height: 80px"></td>
                        <td rowspan="3" style="height: 80px"></td>
                        <td rowspan="3" style="height: 80px"></td>
                        <td rowspan="3" style="height: 80px"></td>
                    </tr>
                </table>
                <span>Chú ý: Nội dung sửa chữa không kể phụ tùng thay thế sẽ được bảo hành trong 3 tháng kể từ ngày sửa
                    chữa.</span>
                <br>
                <span><strong>*Thời gian kiểm tra lần tới căn cứ theo số km hoặc ngày tháng ,tùy theo điều kiện nào đến
                        trước</strong></span>
                <br>
                <span>
                    Trong trường hợp đặc biệt, nếu xe của quý khách gặp sự cố mà không thể đến được, xin quý khách vui
                    lòng
                    gọi điện trực tiếp đến số điện {{ $headPhoneNumber }}.
                </span>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        window.print();
    });
</script>
