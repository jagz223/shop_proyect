<?php

namespace App\Livewire;

use Livewire\Component;

class UserAddresses extends Component
{
    public $user;

    public function mount($user = null)
    {
        $this->user = $user ? $user->load('addresses') : auth()->user()->load('addresses');
    }

    public function render()
    {
        return view('livewire.user-addresses');
    }
}
