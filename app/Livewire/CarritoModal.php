<?php

namespace App\Livewire;

use Livewire\Component;

class CarritoModal extends Component
{
    public $isOpen = false;

    protected $listeners = [
        'openCarritoModal' => 'open',
        'closeCarritoModal' => 'close',
    ];

    public function open()
    {
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.carrito-modal');
    }
}
