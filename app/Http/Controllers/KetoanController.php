<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\AccountMoney;
use App\Models\Receipt;
use App\Models\Payment;

class KetoanController extends Controller
{
    //
    public function thu()
    {
        return view('ketoan.thu');
    }
    public function chi()
    {
        return view('ketoan.chi');
    }
    public function baocaotonghopthu()
    {
        return view('ketoan.baocao.tonghopthu');
    }
    public function baocaochitietthu()
    {
        return view('ketoan.baocao.chitietthu');
    }
    public function baocaotonghopchi()
    {
        return view('ketoan.baocao.tonghopchi');
    }
    public function baocaochitietchi()
    {
        return view('ketoan.baocao.chitietchi');
    }

    public function doanhthutaichinh()
    {
        return view('ketoan.baocao.doanhthutaichinh');
    }

    public function xuatpdf($id)
    {
        $receipt = Receipt::with(['customer', 'accountMoney'])->findOrFail($id);
        $address = trim($receipt->customer->address)
            . (isset($receipt->customer->wardCustomer) ? ', ' . $receipt->customer->wardCustomer->name : '')
            . (isset($receipt->customer->districtCustomer) ? ', ' . $receipt->customer->districtCustomer->name : '')
            . (isset($receipt->customer->provinceCustomer) ? ', ' . $receipt->customer->provinceCustomer->name : '');
        $payDate = Carbon::createFromFormat('Y-m-d', $receipt->receipt_date);

        $data = [
            'name' => $receipt->customer->name,
            'address' => $address,
            'reason' => $receipt->note,
            'totalPrice' => $receipt->money,
            'day' => $payDate->day < 10 ? '0' . $payDate->day : '' . $payDate->day,
            'month' => $payDate->month,
            'year' => $payDate->year,
            'number' => $receipt->id,
            'have' => 131, // Phải thu của khách hàng
            'account_code' => $receipt->accountMoney->account_code
        ];
        // // instantiate and use the dompdf class
        // $dompdf = new Dompdf();
        // $html = mb_convert_encoding(view('ketoan.xuatpdf', ['data' => $data]), 'HTML-ENTITIES', 'UTF-8');
        // $dompdf->loadHtml($html);

        // // (Optional) Setup the paper size and orientation
        // $dompdf->setPaper('A4', 'landscape');

        // // Render the HTML as PDF
        // $dompdf->render();

        // // Output the generated PDF to Browser
        // $dompdf->stream(Carbon::now()->format('YmdHisu') . '.pdf', ['Attachment' => false]);
        return view('ketoan.xuatpdf', ['data' => $data]);
    }

    public function inPhieuChi($id)
    {
        $receipt = Payment::with(['supplier', 'accountMoney'])->findOrFail($id);
        $payDate = Carbon::createFromFormat('Y-m-d', $receipt->payment_date);
        $address = trim($receipt->supplier->address)
        . (isset($receipt->supplier->wardSupplier) ? ', ' . $receipt->supplier->wardSupplier->name : '')
        . (isset($receipt->supplier->districtSupplier) ? ', ' . $receipt->supplier->districtSupplier->name : '')
        . (isset($receipt->supplier->provinceSupplier) ? ', ' . $receipt->supplier->provinceSupplier->name : '');
        $data = [
            'name' => $receipt->supplier->name,
            'address' => $address,
            'reason' => $receipt->note,
            'totalPrice' => $receipt->money,
            'day' => $payDate->day < 10 ? '0' . $payDate->day : '' . $payDate->day,
            'month' => $payDate->month,
            'year' => $payDate->year,
            'number' => $receipt->id,
            //'have' => 131, // Phải thu của khách hàng
            'account_code' => $receipt->accountMoney->account_code
        ];
        // // instantiate and use the dompdf class
        // $dompdf = new Dompdf();
        // $html = mb_convert_encoding(view('ketoan.xuatpdf', ['data' => $data]), 'HTML-ENTITIES', 'UTF-8');
        // $dompdf->loadHtml($html);

        // // (Optional) Setup the paper size and orientation
        // $dompdf->setPaper('A4', 'landscape');

        // // Render the HTML as PDF
        // $dompdf->render();

        // // Output the generated PDF to Browser
        // $dompdf->stream(Carbon::now()->format('YmdHisu') . '.pdf', ['Attachment' => false]);
        return view('ketoan.inphieuchi', ['data' => $data]);
    }


    /**
     *
     */
    public function danhsachthu()
    {
        return view('ketoan.baocao.dsthu');
    }

    /**
     *
     */
    public function danhsachchi()
    {
        return view('ketoan.baocao.dschi');
    }
    /**
     *
     */
    public function danhsachtragop()
    {
        return view('ketoan.baocao.dstragop');
    }

    public function soquy()
    {
        return view('ketoan.baocao.soquy');
    }
}
