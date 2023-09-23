<?php

namespace App\Http\Livewire\Mtocs;

use App\Http\Livewire\Base\BaseLive;
use App\Models\HMSReceivePlan;
use Livewire\Component;
use App\Models\Mtoc;
use App\Models\MasterData;
use Illuminate\Http\Request;

class MtocCreate extends Component
{
    public $mtocd; // mã
    public $colorCode; // mã màu
    public $colorName; // màu
    public $modelCode; // tên đời xe
    public $modelType; // phân loại đời xe
    public $optionCode; // Danh mục đời xe
    public $suggest_price; // giá niên yết
    public $mtoclist;


    public function mount()
    {
        if ($this->mtoclist) {
            $this->mtocd = $this->mtoclist->getMTOC(); // done
            $this->colorCode = $this->mtoclist->color_code;
            $this->colorName = $this->mtoclist->color_name;
            $this->modelCode = $this->mtoclist->model_code;
            $this->modelType = $this->mtoclist->type_code;
            $this->optionCode = $this->mtoclist->option_code;
            $this->suggest_price = $this->mtoclist->suggest_price;
        }
    }

    public function render()
    {
        $this->mtocd = $this->modelCode . $this->modelType . $this->optionCode . $this->colorCode;
        return view('livewire.mtocs.mtoc-create');
    }
    public function store()
    {
        $this->validate([
            'mtocd' => 'required|max:255|unique:mtoc,mtocd',
            'colorCode' => 'required|max:255',
            'colorName' => 'required|max:255',
            'modelCode' => 'required|max:255',
            'modelType' => 'required|max:255',
            // 'optionCode' => 'required|max:255',
            'suggest_price' => 'required|min:1',

        ], [
            'suggest_price.required' => 'Giá đề xuất là bắt buộc'
        ], [

            'mtocd' => 'Mã MTOC',
            'colorCode' => 'Mã màu xe',
            'colorName' => 'Tên màu xe',
            'modelCode' => 'Tên đời xe',
            'modelType' => 'Phân loại đời xe',
            // 'optionCode' => 'Danh mục đời xe',
            'suggest_price' => 'giá đề xuất',
        ]);
        if (empty($this->mtoclist)) { //trường hợp rỗng thì tạo mới
            $this->mtoclist = new Mtoc;
        }
        $this->mtoclist->mtocd = $this->mtocd;
        $this->mtoclist->color_code = $this->colorCode;
        $this->mtoclist->color_name = $this->colorName;
        $this->mtoclist->model_code = $this->modelCode;
        $this->mtoclist->type_code = $this->modelType;
        $this->mtoclist->option_code = $this->optionCode;
        $this->mtoclist->suggest_price = $this->suggest_price;
        $Check = Mtoc::where('model_code', $this->modelCode)
            ->where('type_code', $this->modelType)
            ->where('option_code', $this->optionCode)
            ->where('color_code', $this->colorCode)
            ->where('color_name', $this->colorName)->count();
        if ($Check != 0) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'message' => 'Mã MTOC này đã được tạo ']);
        } else {
            $this->mtoclist->save();
            $this->resetInput();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Cập nhật thành công']);
        }
    }
    public function resetInput()
    {
        $this->mtocd = '';
        $this->colorCode = '';
        $this->colorName = '';
        $this->modelCode = '';
        $this->modelType = '';
        $this->optionCode = '';
        $this->suggest_price = '';
    }
}
