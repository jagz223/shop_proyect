<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Order;

class UserOrders extends Component
{
    public User $user;
    public $orders;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->orders = $this->user->orders()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getStatusLabel($status)
    {
        return match ($status) {
            'pending' => 'Pendiente',
            'processing' => 'En Proceso',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            default => $status,
        };
    }

    public function getStatusColor($status)
    {
        return match ($status) {
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function render()
    {
        return view('livewire.user-orders');
    }
} 