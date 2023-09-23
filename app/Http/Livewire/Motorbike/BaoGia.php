<?php

namespace App\Http\Livewire\Motorbike;
use App\Http\Livewire\Base\BaseLive;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Mtoc;
use App\Models\Proposal;
use Maatwebsite\Excel\Facades\Excel;

class BaoGia extends BaseLive
{
    public $data = [];
    public $perPage =50;
    public $proposaltype ;
    public $mtoclist = [];
    public $mtoc;
    public $title ;

    public function render()
    {
        $data =$this->getProposal();
        if (isset($data)) {
            # code...
            $data->paginate($this->perPage);
        }
        //$data->paginate($this->perPage);
        return view('livewire.motorbike.bao-gia',['data'=>$data]);
    }
    /**
     * get mtoc and it prices
     */
    public function getmtoclist(){

    }
    /**
     *
     */
    public function getProposal(){
        $query = Proposal::whereNull('deleted_at')
        ->select('*')
        ->get();
    }
}
