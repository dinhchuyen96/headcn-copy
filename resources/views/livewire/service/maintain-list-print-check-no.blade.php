<div class="container-fluid pr-5 pl-5">
    <div class="text-right">
        Khách hàng
    </div>
    <div class="text-center">
        <h5>PHIẾU KIỂM TRA ĐỊNH KỲ LẦN {{ $checkNo }}</h5>
    </div>
    <div class="border border-dark p-2">
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4 d-flex">
                <div class="text-center" style="width: calc(100%/3);">Ngày</div>
                <div class="text-center" style="width: calc(100%/3);">Tháng</div>
                <div class="text-center" style="width: calc(100%/3);">Năm</div>
            </div>
            <div class="col-4 d-flex">
                <div class="text-center" style="width: calc(100%/3);">Ngày</div>
                <div class="text-center" style="width: calc(100%/3);">Tháng</div>
                <div class="text-center" style="width: calc(100%/3);">Năm</div>
            </div>
        </div>
        <div class="row">
            <div class="col-4 d-flex">
                <div class="border border-dark text-center" style="width: calc(100%/5);">
                    {{ count($km) > 0 ? $km[0] : '' }}</div>
                <div class="border border-dark text-center" style="width: calc(100%/5);">
                    {{ count($km) > 1 ? $km[1] : '' }}</div>
                <div class="border border-dark text-center" style="width: calc(100%/5);">
                    {{ count($km) > 2 ? $km[2] : '' }}</div>
                <div class="border border-dark text-center" style="width: calc(100%/5);">
                    {{ count($km) > 3 ? $km[3] : '' }}</div>
                <div class="border border-dark text-center" style="width: calc(100%/5);">
                    {{ count($km) > 4 ? $km[4] : '' }}</div>
            </div>
            <div class="col-4 d-flex">
                <div class="border border-dark text-center" style="width: calc(100%/3);">
                    {{ isset($sellDate) ? $sellDate->day : '' }}
                </div>
                <div class="border border-dark text-center" style="width: calc(100%/3);">
                    {{ isset($sellDate) ? $sellDate->month : '' }}
                </div>
                <div class="border border-dark text-center" style="width: calc(100%/3);">
                    {{ isset($sellDate) ? $sellDate->year : '' }}
                </div>
            </div>
            <div class="col-4 d-flex">
                <div class="border border-dark text-center" style="width: calc(100%/3);">
                    {{ isset($checkDate) ? $checkDate->day : '' }}
                </div>
                <div class="border border-dark text-center" style="width: calc(100%/3);">
                    {{ isset($checkDate) ? $checkDate->month : '' }}
                </div>
                <div class="border border-dark text-center" style="width: calc(100%/3);">
                    {{ isset($checkDate) ? $checkDate->year : '' }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4 text-left">
                Số km
            </div>
            <div class="col-4 text-left">
                Ngày bán
            </div>
            <div class="col-4 text-left">
                Ngày kiểm tra
            </div>
        </div>
        <div class="row">
            <div class="col-6 text-left">
                Loại xe: {{ isset($motorbike) ? $motorbike->model_code : '' }}
            </div>
            <div class="col-6 text-left">
                Biển số: {{ isset($motorbike) ? $motorbike->motor_numbers : '' }}
            </div>

        </div>
        <div class="row text-left">
            <div class="col-12 text-left">
                Tên chủ xe: {{ isset($motorbike) ? $motorbike->customer->name : '' }}
            </div>

        </div>
        <div class="row text-left">
            <div class="col-12 text-left">
                Số khung: {{ isset($motorbike) ? $motorbike->chassic_no : '' }}
            </div>
        </div>
        <div class="row text-left">
            <div class="col-12 text-left">
                Số máy: {{ isset($motorbike) ? $motorbike->engine_no : '' }}
            </div>
        </div>
    </div>
    <div class="text-center mt-3">
        <h5>V: VỆ SINH, K: KIỂM TRA, T: THAY THẾ, B: BÔI TRƠN</h5>
    </div>
    {{-- KTĐK lần 1 --}}
    @if ($checkNo == 1)
        <div class="d-flex justify-content-around mt-3">

            <table style="width: 70%;text-align: center;" border="1">
                <tbody>
                    <tr>
                        <td rowspan="2">STT</td>
                        <td rowspan="2" style="width:300px">Thời hạn</td>
                        <td style="width:200px">Dưới 1000 km</td>
                        <td rowspan="2">Phương pháp kiểm tra</td>
                        <td rowspan="2">Thực hiện</td>
                        <td rowspan="2">Kiểm tra cuối</td>
                        <td rowspan="2">Ghi chú</td>
                    </tr>
                    <tr>
                        <td>1 tháng</td>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td class="text-left">Dầu động cơ</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td class="text-left">Tốc độ cầm chứng động cơ</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td class="text-left">Hệ thống phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td class="text-left">Hệ thống phanh ly hợp</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td class="text-left">Hoạt động khóa phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">6</td>
                        <td class="text-left">Vòng bi cổ lái</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">7</td>
                        <td class="text-left">Ốc, bu lông, ốc khóa</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">8</td>
                        <td class="text-left">Xích tải</td>
                        <td class="text-center">K,B</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">9</td>
                        <td class="text-left">Lọc dầu</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 29%;" border="1">
                <tbody>
                    <tr>
                        <td colspan="2" class="text-center">Loại xe thực hiện</td>
                    </tr>
                    <tr class="p-2">
                        <td class="text-center">Tất cả</td>
                        <td class="text-center">Chỉ áp dụng cho</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Xe số</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td>Áp dụng cho xe SH300i ABS</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex mt-3">
            <table style="width: 30%;" border="1">
                <tbody>
                    <tr class="p-2">
                        <td class="text-center" style="width: 50%;">Thời gian ước tính</td>
                        <td class="text-center" style="width: 50%;">15</td>
                    </tr>
                    <tr class="p-2">
                        <td class="text-center" style="width: 50%;">Thời gian thực tế</td>
                        <td class="text-center" style="width: 50%;"></td>
                    </tr>
                </tbody>
            </table>

        </div>
    @endif
    {{-- KTĐK lần 2 --}}
    @if ($checkNo == 2)
        <div class="d-flex justify-content-around mt-3">

            <table style="width: 70%;text-align: center;" border="1">
                <tbody>
                    <tr>
                        <td rowspan="2">STT</td>
                        <td rowspan="2" style="width:300px">Thời hạn</td>
                        <td style="width:200px">1001km ~ 6000km</td>
                        <td rowspan="2">Phương pháp kiểm tra</td>
                        <td rowspan="2">Thực hiện</td>
                        <td rowspan="2">Kiểm tra cuối</td>
                        <td rowspan="2">Ghi chú</td>
                    </tr>
                    <tr>
                        <td>1 tháng 1 ngày ~ 6 tháng</td>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td class="text-left">Đường ống xăng</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td class="text-left">Lưới lọc xăng</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td class="text-left">Hoạt động tay ga</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td class="text-left">Hoạt động le gió</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td class="text-left">Lọc gió</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">6</td>
                        <td class="text-left">Lọc gió</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">7</td>
                        <td class="text-left">Lọc gió phụ</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">8</td>
                        <td class="text-left">Thông hơi vách máy</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">9</td>
                        <td class="text-left">Bugi</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">10</td>
                        <td class="text-left">Khe hở xu páp</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">11</td>
                        <td class="text-left">Dầu động cơ</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">12</td>
                        <td class="text-left">Tốc độ cầm chừng động cơ</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">13</td>
                        <td class="text-left">Hộp lọc gió dây đai</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">14</td>
                        <td class="text-left">Bình điện</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">15</td>
                        <td class="text-left">Dầu phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">16</td>
                        <td class="text-left">Mòn má phanh/guốc phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">17</td>
                        <td class="text-left">Hệ thống phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">18</td>
                        <td class="text-left">Công tác đèn phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">19</td>
                        <td class="text-left">Hoạt động khóa phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">20</td>
                        <td class="text-left">Độ rọi đèn pha</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">21</td>
                        <td class="text-left">Hệ thống ly hợp</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">22</td>
                        <td class="text-left">Chân chống nghiêng</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">23</td>
                        <td class="text-left">Giảm xóc</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">24</td>
                        <td class="text-left">Bánh xe/lốp xe</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">25</td>
                        <td class="text-left">Xích tải</td>
                        <td class="text-center">K,B</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 29%;" border="1">
                <tbody>
                    <tr>
                        <td colspan="2" class="text-center">Loại xe thực hiện</td>
                    </tr>
                    <tr class="p-2">
                        <td class="text-center">Tất cả</td>
                        <td class="text-center">Chỉ áp dụng cho</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Xe số</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Lọc gió bằng xốp</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Lọc gió bằng giấy thường</td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td >Bugi tiêu chuẩn</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td >Loại điều chỉnh bằng vít</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Với đời xe có lọc gió buồng dây đai</td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Thay thế định kỳ: 2 năm</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Xe số</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex mt-3">
            <table style="width: 30%;" border="1">
                <tbody>
                    <tr class="p-2">
                        <td class="text-center" style="width: 50%;">Thời gian ước tính</td>
                        <td class="text-center" style="width: 50%;">20</td>
                    </tr>
                    <tr class="p-2">
                        <td class="text-center" style="width: 50%;">Thời gian thực tế</td>
                        <td class="text-center" style="width: 50%;"></td>
                    </tr>
                </tbody>
            </table>

        </div>
    @endif
    {{-- KTĐK lần 3 --}}
    @if ($checkNo == 3)
        <div class="d-flex justify-content-around mt-3">

            <table style="width: 70%;text-align: center;" border="1">
                <tbody>
                    <tr>
                        <td rowspan="2">STT</td>
                        <td rowspan="2" style="width:300px">Thời hạn</td>
                        <td style="width:200px">6001km ~ 12000km</td>
                        <td rowspan="2">Phương pháp kiểm tra</td>
                        <td rowspan="2">Thực hiện</td>
                        <td rowspan="2">Kiểm tra cuối</td>
                        <td rowspan="2">Ghi chú</td>
                    </tr>
                    <tr>
                        <td>6 tháng 1 ngày ~ 12 tháng</td>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td class="text-left">Đường ống xăng</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td class="text-left">Lưới lọc xăng</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td class="text-left">Lọc xăng</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td class="text-left">Hoạt động tay ga</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td class="text-left">Hoạt động le gió</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">6</td>
                        <td class="text-left">Lọc gió</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">7</td>
                        <td class="text-left">Lọc gió</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">8</td>
                        <td class="text-left">Lọc gió phụ</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">9</td>
                        <td class="text-left">Thông hơi vách máy</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">10</td>
                        <td class="text-left">Bugi</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">11</td>
                        <td class="text-left">Khe hở xu páp</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">12</td>
                        <td class="text-left">Dầu động cơ</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">13</td>
                        <td class="text-left">Lưới lọc dầu động cơ</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">14</td>
                        <td class="text-left">Lọc dầu ly tâm</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">15</td>
                        <td class="text-left">Tốc độ cầm chừng động cơ</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">16</td>
                        <td class="text-left">Dung dịch làm mát két tản nhiệt</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">17</td>
                        <td class="text-left">Hệ thống làm mát</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">18</td>
                        <td class="text-left">Đai truyền</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">19</td>
                        <td class="text-left">Hộp lọc gió dây đai</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">20</td>
                        <td class="text-left">Bình điện</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">21</td>
                        <td class="text-left">Dầu phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">22</td>
                        <td class="text-left">Mòn má phanh/guốc phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">23</td>
                        <td class="text-left">Hệ thống phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">24</td>
                        <td class="text-left">Công tác đèn phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">25</td>
                        <td class="text-left">Hoạt động khóa phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">26</td>
                        <td class="text-left">Độ rọi đèn pha</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">27</td>
                        <td class="text-left">Hệ thống ly hợp</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">28</td>
                        <td class="text-left">Mòn guốc ly hợp</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">29</td>
                        <td class="text-left">Chân chống nghiêng</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">30</td>
                        <td class="text-left">Giảm xóc</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">31</td>
                        <td class="text-left">Ốc, bu lông, ốc khóa</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">32</td>
                        <td class="text-left">Bánh xe/lốp xe</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">33</td>
                        <td class="text-left">Vòng bi cổ lái</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">34</td>
                        <td class="text-left">Xích tải</td>
                        <td class="text-center">K,B</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">35</td>
                        <td class="text-left">Lọc dầu</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 29%;" border="1">
                <tbody>
                    <tr>
                        <td colspan="2" class="text-center">Loại xe thực hiện</td>
                    </tr>
                    <tr class="p-2">
                        <td class="text-center">Tất cả</td>
                        <td class="text-center">Chỉ áp dụng cho</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Xe số</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Lọc gió bằng xốp</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Lọc gió bằng giấy thường</td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td >Bugi tiêu chuẩn</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td >Loại điều chỉnh bằng vít</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Xe có hệ thống làm mát bằng dung dịch</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Thay thế định kỳ: 3 năm</td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Với đời xe có lọc gió buồng dây đai</td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Thay thế định kỳ: 2 năm</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Xe số</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Áp dụng cho xe SH 300i ABS</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex mt-3">
            <table style="width: 30%;" border="1">
                <tbody>
                    <tr class="p-2">
                        <td class="text-center" style="width: 50%;">Thời gian ước tính</td>
                        <td class="text-center" style="width: 50%;">30</td>
                    </tr>
                    <tr class="p-2">
                        <td class="text-center" style="width: 50%;">Thời gian thực tế</td>
                        <td class="text-center" style="width: 50%;"></td>
                    </tr>
                </tbody>
            </table>

        </div>
    @endif

    {{-- KTĐK lần 4 --}}
    @if ($checkNo == 4)
        <div class="d-flex justify-content-around mt-3">

            <table style="width: 70%;text-align: center;" border="1">
                <tbody>
                    <tr>
                        <td rowspan="2">STT</td>
                        <td rowspan="2" style="width:300px">Thời hạn</td>
                        <td style="width:200px">12001km ~ 18000km</td>
                        <td rowspan="2">Phương pháp kiểm tra</td>
                        <td rowspan="2">Thực hiện</td>
                        <td rowspan="2">Kiểm tra cuối</td>
                        <td rowspan="2">Ghi chú</td>
                    </tr>
                    <tr>
                        <td>12 tháng 1 ngày ~ 18 tháng</td>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td class="text-left">Đường ống xăng</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td class="text-left">Lưới lọc xăng</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td class="text-center">3</td>
                        <td class="text-left">Hoạt động tay ga</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td class="text-left">Hoạt động le gió</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td class="text-left">Lọc gió</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">6</td>
                        <td class="text-left">Lọc gió</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">7</td>
                        <td class="text-left">Lọc gió</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">8</td>
                        <td class="text-left">Lọc gió phụ</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">9</td>
                        <td class="text-left">Thông hơi vách máy</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">10</td>
                        <td class="text-left">Bugi</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">11</td>
                        <td class="text-left">Khe hở xu páp</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">12</td>
                        <td class="text-left">Dầu động cơ</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">13</td>
                        <td class="text-left">Tốc độ cầm chừng động cơ</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">14</td>
                        <td class="text-left">Hệ thống cấp khí phụ</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">15</td>
                        <td class="text-left">Hệ thống kiểm soát hơi xăng</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">16</td>
                        <td class="text-left">Hộp lọc gió dây đai</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td class="text-center">17</td>
                        <td class="text-left">Bình điện</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">18</td>
                        <td class="text-left">Dầu phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">19</td>
                        <td class="text-left">Mòn má phanh/guốc phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">20</td>
                        <td class="text-left">Hệ thống phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">21</td>
                        <td class="text-left">Công tác đèn phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">22</td>
                        <td class="text-left">Hoạt động khóa phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">23</td>
                        <td class="text-left">Độ rọi đèn pha</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">24</td>
                        <td class="text-left">Hệ thống ly hợp</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td class="text-center">25</td>
                        <td class="text-left">Chân chống nghiêng</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">26</td>
                        <td class="text-left">Giảm xóc</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td class="text-center">27</td>
                        <td class="text-left">Bánh xe/lốp xe</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td class="text-center">28</td>
                        <td class="text-left">Xích tải</td>
                        <td class="text-center">K,B</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 29%;" border="1">
                <tbody>
                    <tr>
                        <td colspan="2" class="text-center">Loại xe thực hiện</td>
                    </tr>
                    <tr class="p-2">
                        <td class="text-center">Tất cả</td>
                        <td class="text-center">Chỉ áp dụng cho</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>

                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Xe số</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Lọc gió có thấm dầu</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Lọc gió bằng xốp</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Lọc gió bằng giấy thường</td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td >Bugi tiêu chuẩn</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td >Loại điều chỉnh bằng vít</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Với đời xe có lọc gió buồng dây đai</td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>

                    <tr>
                        <td class="text-center"></td>
                        <td >Thay thế định kỳ: 2 năm</td>
                    </tr>

                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>

                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>

                    <tr>
                        <td class="text-center"></td>
                        <td >Xe số</td>
                    </tr>

                </tbody>
            </table>
        </div>
        <div class="d-flex mt-3">
            <table style="width: 30%;" border="1">
                <tbody>
                    <tr class="p-2">
                        <td class="text-center" style="width: 50%;">Thời gian ước tính</td>
                        <td class="text-center" style="width: 50%;">45</td>
                    </tr>
                    <tr class="p-2">
                        <td class="text-center" style="width: 50%;">Thời gian thực tế</td>
                        <td class="text-center" style="width: 50%;"></td>
                    </tr>
                </tbody>
            </table>

        </div>
    @endif
    {{-- KTĐK lần 5 --}}
    @if ($checkNo == 5)
        <div class="d-flex justify-content-around mt-3">

            <table style="width: 70%;text-align: center;" border="1">
                <tbody>
                    <tr>
                        <td rowspan="2">STT</td>
                        <td rowspan="2" style="width:300px">Thời hạn</td>
                        <td style="width:200px">18001km ~ 24000km</td>
                        <td rowspan="2">Phương pháp kiểm tra</td>
                        <td rowspan="2">Thực hiện</td>
                        <td rowspan="2">Kiểm tra cuối</td>
                        <td rowspan="2">Ghi chú</td>
                    </tr>
                    <tr>
                        <td>18 tháng 1 ngày ~ 27 tháng</td>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td class="text-left">Đường ống xăng</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td class="text-left">Lưới lọc xăng</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td class="text-left">Lọc xăng</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td class="text-left">Hoạt động tay ga</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td class="text-left">Hoạt động le gió</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">6</td>
                        <td class="text-left">Lọc gió</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">7</td>
                        <td class="text-left">Lọc gió</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">8</td>
                        <td class="text-left">Lọc gió phụ</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">9</td>
                        <td class="text-left">Thông hơi vách máy</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">10</td>
                        <td class="text-left">Bugi</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">11</td>
                        <td class="text-left">Khe hở xu páp</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">12</td>
                        <td class="text-left">Khe hở xu páp</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">13</td>
                        <td class="text-left">Dầu động cơ</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">14</td>
                        <td class="text-left">Lưới lọc dầu động cơ</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">15</td>
                        <td class="text-left">Lọc dầu ly tâm</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">16</td>
                        <td class="text-left">Tốc độ cầm chừng động cơ</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">17</td>
                        <td class="text-left">Dung dịch làm mát két tản nhiệt</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">18</td>
                        <td class="text-left">Hệ thống làm mát</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">19</td>
                        <td class="text-left">Lọc khí phụ hệ thống cấp khí phụ</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">20</td>
                        <td class="text-left">Đai truyền</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">21</td>
                        <td class="text-left">Hộp lọc gió dây đai</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">22</td>
                        <td class="text-left">Bình điện</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">23</td>
                        <td class="text-left">Dầu phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">24</td>
                        <td class="text-left">Mòn má phanh/guốc phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">25</td>
                        <td class="text-left">Hệ thống phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">26</td>
                        <td class="text-left">Công tác đèn phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">27</td>
                        <td class="text-left">Hoạt động khóa phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">28</td>
                        <td class="text-left">Độ rọi đèn pha</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">29</td>
                        <td class="text-left">Hệ thống ly hợp</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">30</td>
                        <td class="text-left">Mòn guốc ly hợp</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">31</td>
                        <td class="text-left">Chân chống nghiêng</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">32</td>
                        <td class="text-left">Giảm xóc</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">33</td>
                        <td class="text-left">Ốc, bu lông, ốc khóa</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">34</td>
                        <td class="text-left">Bánh xe/lốp xe</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">35</td>
                        <td class="text-left">Vòng bi cổ lái</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">36</td>
                        <td class="text-left">Xích tải</td>
                        <td class="text-center">K,B</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">37</td>
                        <td class="text-left">Lọc dầu</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 29%;" border="1">
                <tbody>
                    <tr>
                        <td colspan="2" class="text-center">Loại xe thực hiện</td>
                    </tr>
                    <tr class="p-2">
                        <td class="text-center">Tất cả</td>
                        <td class="text-center">Chỉ áp dụng cho</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Xe số</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Lọc gió bằng xốp</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Lọc gió bằng giấy thường</td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td >Bugi tiêu chuẩn</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td >Loại điều chỉnh bằng vít</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Loại điều chỉnh bằng Tappet</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Xe có hệ thống làm mát bằng dung dịch</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Thay thế định kỳ: 3 năm</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Thay thế định kỳ: 3 năm</td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Với đời xe có lọc gió buồng dây đai</td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Thay thế định kỳ: 2 năm</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Xe số</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Áp dụng cho xe SH 300i ABS</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex mt-3">
            <table style="width: 30%;" border="1">
                <tbody>
                    <tr class="p-2">
                        <td class="text-center" style="width: 50%;">Thời gian ước tính</td>
                        <td class="text-center" style="width: 50%;">45</td>
                    </tr>
                    <tr class="p-2">
                        <td class="text-center" style="width: 50%;">Thời gian thực tế</td>
                        <td class="text-center" style="width: 50%;"></td>
                    </tr>
                </tbody>
            </table>

        </div>
    @endif
    {{-- KTĐK lần 6 --}}
    @if ($checkNo == 6)
        <div class="d-flex justify-content-around mt-3">

            <table style="width: 70%;text-align: center;" border="1">
                <tbody>
                    <tr>
                        <td rowspan="2">STT</td>
                        <td rowspan="2" style="width:300px">Thời hạn</td>
                        <td style="width:200px">24001km ~ 30000km</td>
                        <td rowspan="2">Phương pháp kiểm tra</td>
                        <td rowspan="2">Thực hiện</td>
                        <td rowspan="2">Kiểm tra cuối</td>
                        <td rowspan="2">Ghi chú</td>
                    </tr>
                    <tr>
                        <td>27 tháng 1 ngày ~ 36 tháng</td>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td class="text-left">Đường ống xăng</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td class="text-left">Lưới lọc xăng</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td class="text-left">Hoạt động tay ga</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td class="text-left">Hoạt động le gió</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td class="text-left">Lọc gió</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">6</td>
                        <td class="text-left">Lọc gió</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">7</td>
                        <td class="text-left">Lọc gió phụ</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">8</td>
                        <td class="text-left">Thông hơi vách máy</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">9</td>
                        <td class="text-left">Bugi</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">10</td>
                        <td class="text-left">Khe hở xu páp</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">11</td>
                        <td class="text-left">Dầu động cơ</td>
                        <td class="text-center">T</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">12</td>
                        <td class="text-left">Tốc độ cầm chừng động cơ</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">13</td>
                        <td class="text-left">Hộp lọc gió dây đai</td>
                        <td class="text-center">V</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">14</td>
                        <td class="text-left">Bình điện</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">15</td>
                        <td class="text-left">Dầu phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">16</td>
                        <td class="text-left">Mòn má phanh/guốc phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">17</td>
                        <td class="text-left">Hệ thống phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">18</td>
                        <td class="text-left">Công tác đèn phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">19</td>
                        <td class="text-left">Hoạt động khóa phanh</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">20</td>
                        <td class="text-left">Độ rọi đèn pha</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">21</td>
                        <td class="text-left">Hệ thống ly hợp</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">22</td>
                        <td class="text-left">Chân chống nghiêng</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">23</td>
                        <td class="text-left">Giảm xóc</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">24</td>
                        <td class="text-left">Bánh xe/lốp xe</td>
                        <td class="text-center">K</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">25</td>
                        <td class="text-left">Xích tải</td>
                        <td class="text-center">K,B</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 29%;" border="1">
                <tbody>
                    <tr>
                        <td colspan="2" class="text-center">Loại xe thực hiện</td>
                    </tr>
                    <tr class="p-2">
                        <td class="text-center">Tất cả</td>
                        <td class="text-center">Chỉ áp dụng cho</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Xe số</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Lọc gió bằng xốp</td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Lọc gió bằng giấy thường</td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td >Bugi tiêu chuẩn</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td >Loại điều chỉnh bằng vít</td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Hộp lọc gió dây đai</td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Thay thế định kỳ: 2 năm</td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">&nbsp;</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center">○</td>
                        <td ></td>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td >Xe số</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex mt-3">
            <table style="width: 30%;" border="1">
                <tbody>
                    <tr class="p-2">
                        <td class="text-center" style="width: 50%;">Thời gian ước tính</td>
                        <td class="text-center" style="width: 50%;">45</td>
                    </tr>
                    <tr class="p-2">
                        <td class="text-center" style="width: 50%;">Thời gian thực tế</td>
                        <td class="text-center" style="width: 50%;"></td>
                    </tr>
                </tbody>
            </table>

        </div>
    @endif
    <div class="mt-3">
        <div>*:Tùy trường hợp nào đến trước</div>
        <div>Tùy theo từng loại xe mà một số hạng mục không được áp dụng</div>
        <div>Tôi xác nhận rằng các mục trên đã được kiểm tra, điều chỉnh và hoạt động hoàn thành tốt
        </div>
    </div>

    <div class="d-flex mt-3 mb-3">
        <div class="text-center" style="width: calc(100%/3);">Khách hàng</div>
        <div class="text-center" style="width: calc(100%/3);">Kỹ thuật viên</div>
        <div class="text-center" style="width: calc(100%/3);">Kỹ thuật viên kiểm tra cuối</div>

    </div>
    <div class="mt-3">
        &nbsp;
    </div>
    <div class="d-flex mt-5">
        <div class="text-left align-self-end" style="width: calc(100%/3);">Số ĐT: </div>
        <div style="width: calc(100%/3);"></div>
        <div style="width: calc(100%/3);">
            <table style="width: 100%;" border="1">
                <tbody>
                    <tr class="p-2">
                        <td class="text-center" style="width: 50%;">Thời gian vào</td>
                        <td class="text-center" style="width: 50%;"></td>
                    </tr>
                    <tr class="p-2">
                        <td class="text-center" style="width: 50%;">Thời gian ra</td>
                        <td class="text-center" style="width: 50%;"></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        window.print();
    });
</script>
