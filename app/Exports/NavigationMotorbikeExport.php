<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Motorbike;
use App\Enum\EMotorbike;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;

class NavigationMotorbikeExport implements WithMultipleSheets, SkipsUnknownSheets
{
    protected $Warehouses;
    protected $Model;
    protected $Color;
    protected $ChassicNumber;
    protected $EngineNumber;
    protected $FromDate;
    protected $ToDate;
    protected $key_name;
    protected $sortingName;
    protected $totalMoney ;

    function __construct($Warehouses, $Model, $Color, $ChassicNumber, $EngineNumber, $FromDate, $ToDate, $key_name, $sortingName)
    {
        $this->Warehouses = trim($Warehouses);
        $this->Model = trim($Model);
        $this->Color = trim($Color);
        $this->ChassicNumber = trim($ChassicNumber);
        $this->EngineNumber = trim($EngineNumber);
        $this->FromDate = trim($FromDate);
        $this->ToDate = trim($ToDate);
        $this->key_name = trim($key_name);
        $this->sortingName = trim($sortingName);
    }
    public function sheets(): array
    {
        return [
            new ReportMotorbikesExport($this->Warehouses, $this->Model, $this->Color, $this->ChassicNumber, $this->EngineNumber, $this->FromDate, $this->ToDate, $this->key_name, $this->sortingName),
            new MoneyInWarehouse($this->Warehouses, $this->Model, $this->Color, $this->ChassicNumber, $this->EngineNumber, $this->FromDate, $this->ToDate, $this->key_name, $this->sortingName),
            new MoneyInModel($this->Warehouses, $this->Model, $this->Color, $this->ChassicNumber, $this->EngineNumber, $this->FromDate, $this->ToDate, $this->key_name, $this->sortingName),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
}
