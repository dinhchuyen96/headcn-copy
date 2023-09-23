<?php

namespace App\Http\Livewire\Base;

use App\Http\Livewire\TrimAndNullEmptyStrings;
use Livewire\Component;

abstract class BaseTrimString extends Component
{
    use TrimAndNullEmptyStrings;
    
}
