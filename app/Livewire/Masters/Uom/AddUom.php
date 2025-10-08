<?php

namespace App\Livewire\Masters\Uom;

use App\Livewire\Masters\UOM\UOMList;
use Livewire\Component;

class AddUom extends UOMList
{

    // Form properties
    // public $code = '';
    // public $name = '';
    // public $description = '';
    // public $is_active = true;

    public function render()
    {
        return view('livewire.add-uom');
    }
}
