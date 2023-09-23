<?php

namespace App\Http\Livewire\Phutung;

use Livewire\Component;

class Inhoadonbanle extends Component
{
    public $data;
    public $details;
    public function render()
    {
        return view('livewire.phutung.inhoadonbanle', ['data' => $this->data, 'detail' => $this->details]);
    }
}
