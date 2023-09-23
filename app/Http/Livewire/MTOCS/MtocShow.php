<?php

namespace App\Http\Livewire\Mtocs;

use App\Models\MasterData;
use Livewire\Component;

class MtocShow extends Component
{
    public $mtocd; // mã
    public $colorCode; // mã màu
    public $colorName; // màu
    public $modelCode; // tên đời xe
    public $modelType; // phân loại đời xe
    public $optionCode; // Danh mục đời xe
    public $suggest_price; // giá niêm yết
    public $mtoclist;
    public $hmsList;


    public function render()
    {
        if ($this->mtoclist) {
            $this->mtocd = $this->mtoclist->mtocd; // done
            $this->colorCode = $this->mtoclist->color_code;
            $this->colorName = $this->mtoclist->color_name;
            $this->modelCode = $this->mtoclist->model_code;
            $this->modelType = $this->mtoclist->type_code;
            $this->optionCode = $this->mtoclist->option_code;
            $this->suggest_price = $this->mtoclist->suggest_price;
        }
        return view('livewire.mtocs.mtoc-show');
    }
}
