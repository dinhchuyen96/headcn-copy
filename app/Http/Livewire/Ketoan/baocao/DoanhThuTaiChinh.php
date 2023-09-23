<?php

namespace App\Http\Livewire\Ketoan\Baocao;

use App\Http\Livewire\Base\BaseLive;
use App\Models\Customer;
use Maatwebsite\Excel\Facades\Excel;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer as Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Livewire\Component;

class DoanhThuTaiChinh extends BaseLive
{

    public $yearStart;
    public $monthStart;

    public $yearEnd;
    public $monthEnd;

    public function mount()
    {
        $this->yearStart = $this->yearEnd = date('Y');
        $this->monthStart = (int)date('m') - 1;
        $this->monthEnd = (int)date('m');
    }
    public function render()
    {
        $this->updateUI();
        return view('livewire.ketoan.baocao.doanh-thu-tai-chinh');
    }
    public function updateUI()
    {

        $this->dispatchBrowserEvent('setSelect2');
    }
    public function export()
    {

        $endTime = $this->yearEnd . ($this->monthEnd < 10 ? ('0' . $this->monthEnd) : $this->monthEnd);
        $startTime = $this->yearStart . ($this->monthStart < 10 ? ('0' . $this->monthStart) : $this->monthStart);
        if ((int)$startTime > (int)$endTime) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc']);
            return;
        }
        // IN p_reportName VARCHAR(255),
        // IN p_curMonth INT,
        // IN p_prevMonth INT,
        // IN p_orderType INT, 1: bán buôn, 2 bán lẻ, 3 : nhập hàng
        // IN p_orderCategory INT, 1: phụ tùng, 2: xe máy, 3 Bảo dưỡng, 4 Sửa chữa
        // IN p_orderDetailType INT,  1: bán buon, 2:bán lẻ, 3:nhập
        // IN p_orderDetailCategory INT, 1: phụ tùng, 2: xe máy, 3 Bảo dưỡng, 4 Sửa chữa
        // IN p_orderDetailStatus INT 1: đã lưu, 0: lưu nháp

        $para = [
            "BAN LE XE MAY",
            $endTime,
            $startTime,
            2, 2, 2, 2, 1
        ];
        $doanhThuBanLeXe = DB::select('call sp_getSaleReport(?,?,?,?,?,?,?,?)', $para);

        $para = [
            "BAN BUON XE MAY",
            $endTime,
            $startTime,
            1, 2, 1, 2, 1
        ];
        $doanhThuBanBuonXe = DB::select('call sp_getSaleReport(?,?,?,?,?,?,?,?)', $para);

        $para = [
            "BAN BUON PT",
            $endTime,
            $startTime,
            1, 1, 1, 1, 1
        ];
        $doanhThuBanBuonPT = DB::select('call sp_getSaleReport(?,?,?,?,?,?,?,?)', $para);

        $para = [
            "BAN LE PT",
            $endTime,
            $startTime,
            2, 1, 2, 1, 1
        ];
        $doanhThuBanLePT = DB::select('call sp_getSaleReport(?,?,?,?,?,?,?,?)', $para);

        $para = [
            "BAN SUA CHUA PT",
            $endTime,
            $startTime,
            null, 4, 2, 1, 1
        ];
        $doanhThuBanSuaChuaPT = DB::select('call sp_getSaleReport(?,?,?,?,?,?,?,?)', $para);

        $para = [
            "BAN BAO DUONG PT",
            $endTime,
            $startTime,
            null, 3, 2, 1, 1
        ];
        $doanhThuBanBaoDuongPT = DB::select('call sp_getSaleReport(?,?,?,?,?,?,?,?)', $para);



        $para = [
            "GIA VON PT",
            $endTime,
            $startTime,
            3, 1, 3, 1, 1
        ];
        $giaVonPT = DB::select('call sp_getCostReport(?,?,?,?,?,?,?,?)', $para);

        $para = [
            "GIA VON Xe",
            $endTime,
            $startTime,
            3, 2, 3, 2, 1
        ];
        $giaVonXe = DB::select('call sp_getCostReport(?,?,?,?,?,?,?,?)', $para);

        $luongNhanVien = DB::select('call sp_getRoleSaleSalary(?,?)', [$endTime, $startTime]);

        $chiNoiBoHoaDon = DB::select('call sp_getOtherExpense(?,?)', [$endTime, $startTime]);

        $hoaHongTraGop = DB::select('call sp_getInstallmentBenefit(?,?)', [$endTime, $startTime]);

        $dongTienXe = DB::select('call sp_cashFlowInMotobike(?)', [$endTime]);

        $dongTienPhuTung = DB::select('call sp_cashFlowInPart(?)', [$endTime]);

        $dongTienChi = DB::select('call sp_cashFlowOut(?)', [$endTime]);

        $fileTemplatePath = public_path() . "/export-template/DoanhThuTaiChinh.xlsx";
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load($fileTemplatePath);
        // Tháng
        $spreadsheet->getActiveSheet()->setCellValue('D3', $this->yearEnd . '-' . ($this->monthEnd < 10 ? ('0' . $this->monthEnd) : $this->monthEnd));
        $spreadsheet->getActiveSheet()->setCellValue('C3', $this->yearStart . '-' . ($this->monthStart < 10 ? ('0' . $this->monthStart) : $this->monthStart));
        // Doanh thu bán lẻ xe
        $spreadsheet->getActiveSheet()->setCellValue('C7', $doanhThuBanLeXe[0]->PREV_QTY);
        $spreadsheet->getActiveSheet()->setCellValue('D7', $doanhThuBanLeXe[0]->CUR_MONTH_QTY);

        // Doanh thu bán buôn xe
        $spreadsheet->getActiveSheet()->setCellValue('C9', $doanhThuBanBuonXe[0]->PREV_QTY);
        $spreadsheet->getActiveSheet()->setCellValue('D9', $doanhThuBanBuonXe[0]->CUR_MONTH_QTY);

        // Doanh thu bán buôn PT
        $spreadsheet->getActiveSheet()->setCellValue('C11', $doanhThuBanBuonPT[0]->PREV_QTY);
        $spreadsheet->getActiveSheet()->setCellValue('D11', $doanhThuBanBuonPT[0]->CUR_MONTH_QTY);

        // Doanh thu bán lẻ PT
        $spreadsheet->getActiveSheet()->setCellValue('C12', $doanhThuBanLePT[0]->PREV_QTY);
        $spreadsheet->getActiveSheet()->setCellValue('D12', $doanhThuBanLePT[0]->CUR_MONTH_QTY);

        // Doanh thu bán lẻ PT Sửa chữa
        $spreadsheet->getActiveSheet()->setCellValue('C13', $doanhThuBanSuaChuaPT[0]->PREV_QTY);
        $spreadsheet->getActiveSheet()->setCellValue('D13', $doanhThuBanSuaChuaPT[0]->CUR_MONTH_QTY);

        // Doanh thu bán lẻ PT Bảo dưỡng
        $spreadsheet->getActiveSheet()->setCellValue('C14', $doanhThuBanBaoDuongPT[0]->PREV_QTY);
        $spreadsheet->getActiveSheet()->setCellValue('D14', $doanhThuBanBaoDuongPT[0]->CUR_MONTH_QTY);

        // Giá vốn PT
        $spreadsheet->getActiveSheet()->setCellValue('C19', $giaVonPT[0]->PREV_QTY);
        $spreadsheet->getActiveSheet()->setCellValue('D19', $giaVonPT[0]->CUR_MONTH_QTY);

        // Giá vốn Xe
        $spreadsheet->getActiveSheet()->setCellValue('C20', $giaVonXe[0]->PREV_QTY);
        $spreadsheet->getActiveSheet()->setCellValue('D20', $giaVonXe[0]->CUR_MONTH_QTY);

        // Lương nhân viên
        if (count($luongNhanVien) > 0) {
            $spreadsheet->getActiveSheet()->insertNewRowBefore(25, count($luongNhanVien));
        }
        foreach ($luongNhanVien as $key => $itemLuong) {
            $spreadsheet->getActiveSheet()->setCellValue('B' . (24 + $key), $itemLuong->roles_name);
            $spreadsheet->getActiveSheet()->setCellValue('C' . (24 + $key), $itemLuong->prev_month_qty);
            $spreadsheet->getActiveSheet()->setCellValue('D' . (24 + $key), $itemLuong->cur_month_qty);
        }
        // Hóa đơn chi nội bộ
        if (count($chiNoiBoHoaDon) > 0) {
            $spreadsheet->getActiveSheet()->insertNewRowBefore(25 + count($luongNhanVien) + 4, count($chiNoiBoHoaDon));
        }
        foreach ($chiNoiBoHoaDon as $key => $itemChiNoiBo) {
            $spreadsheet->getActiveSheet()->setCellValue('B' . (24 + count($luongNhanVien) + 4 + $key), $itemChiNoiBo->service_name);
            $spreadsheet->getActiveSheet()->setCellValue('C' . (24 + count($luongNhanVien) + 4  + $key), $itemChiNoiBo->prev_qty);
            $spreadsheet->getActiveSheet()->setCellValue('D' . (24 + count($luongNhanVien) + 4 + $key), $itemChiNoiBo->cur_qty);
        }
        // Hoa hồng trả góp
        $spreadsheet->getActiveSheet()->setCellValue('B' . (24 + 6 + count($luongNhanVien) + count($chiNoiBoHoaDon)), $hoaHongTraGop[0]->item_name);
        $spreadsheet->getActiveSheet()->setCellValue('C' . (24 + 6 + count($luongNhanVien) + count($chiNoiBoHoaDon)), $hoaHongTraGop[0]->prev_qty);
        $spreadsheet->getActiveSheet()->setCellValue('D' . (24 + 6 + count($luongNhanVien) + count($chiNoiBoHoaDon)), $hoaHongTraGop[0]->cur_qty);

        // Dòng tiền từ xe
        if (count($dongTienXe) > 0) {
            $spreadsheet->getActiveSheet()->insertNewRowBefore(25 + 35 + count($luongNhanVien) + count($chiNoiBoHoaDon), count($dongTienXe));
        }
        foreach ($dongTienXe as $key => $itemDongTien) {
            $dongTienText = "";
            switch ($itemDongTien->type) {
                case 1:
                    $dongTienText = "Bán lẻ xe máy";
                    break;
                case 2:
                    $dongTienText = "Bán buôn xe máy";
                    break;
                case 7:
                    $dongTienText = "Nợ cũ";
                    break;
                case 9:
                    $dongTienText = "Trả góp";
                    break;
                default:
                    # code...
                    break;
            }
            $spreadsheet->getActiveSheet()->setCellValue('B' . (24 + 35 + count($luongNhanVien) + count($chiNoiBoHoaDon) + $key), $dongTienText);
            $spreadsheet->getActiveSheet()->setCellValue('D' . (24 + 35 + count($luongNhanVien) + count($chiNoiBoHoaDon) + $key), $itemDongTien->receipt_qty);
        }

        // Dòng tiền phụ tùng
        if (count($dongTienPhuTung) > 0) {
            $spreadsheet->getActiveSheet()->insertNewRowBefore(25 + 37 + count($luongNhanVien) + count($chiNoiBoHoaDon) + count($dongTienXe), count($dongTienPhuTung));
        }
        foreach ($dongTienPhuTung as $key => $itemDongTien) {
            $dongTienText = "";
            switch ($itemDongTien->type) {
                case 3:
                    $dongTienText = "Bán lẻ phụ tùng";
                    break;
                case 4:
                    $dongTienText = "Bán buôn phụ tùng";
                    break;
                case 5:
                    $dongTienText = "Kiểm tra định kỳ";
                    break;
                case 6:
                    $dongTienText = "Sửa chữa thông thường";
                    break;
                default:
                    # code...
                    break;
            }
            $spreadsheet->getActiveSheet()->setCellValue('B' . (24 + 37 + count($luongNhanVien) + count($chiNoiBoHoaDon) + count($dongTienXe) + $key), $dongTienText);
            $spreadsheet->getActiveSheet()->setCellValue('D' . (24 + 37 + count($luongNhanVien) + count($chiNoiBoHoaDon) + count($dongTienXe) + $key), $itemDongTien->receipt_qty);
        }

        // Dòng tiền chi
        if (count($dongTienChi) > 0) {
            $spreadsheet->getActiveSheet()->insertNewRowBefore(25 + 43 + count($luongNhanVien) + count($chiNoiBoHoaDon) + count($dongTienXe) + count($dongTienPhuTung), count($dongTienChi));
        }
        foreach ($dongTienChi as $key => $itemDongTien) {
            $dongTienText = "";
            switch ($itemDongTien->type) {
                case 8:
                    $dongTienText = "Nhập phụ tùng";
                    break;
                case 9:
                    $dongTienText = "Nhập xe";
                    break;
                case 10:
                    $dongTienText = "Chi nội bộ";
                    break;
                default:
                    # code...
                    break;
            }
            $spreadsheet->getActiveSheet()->setCellValue('B' . (24 + 43 + count($luongNhanVien) + count($chiNoiBoHoaDon) + count($dongTienXe) + count($dongTienPhuTung) + $key), $dongTienText);
            $spreadsheet->getActiveSheet()->setCellValue('D' . (24 + 43 + count($luongNhanVien) + count($chiNoiBoHoaDon) + count($dongTienXe) + count($dongTienPhuTung) + $key), $itemDongTien->paid_qty);
        }


        $writer = new Writer\Xls($spreadsheet);

        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . "DoanhThuTaiChinh_" . Carbon::now()->format('YmdHis') . "xlsx" . '"');
        $response->headers->set('Cache-Control', 'max-age=0');
        return $response;
    }
}
