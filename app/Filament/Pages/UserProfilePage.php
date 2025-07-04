<?php

namespace App\Filament\Pages;

use App\Models\Address;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class UserProfilePage extends Page
{
    protected static ?string $title = 'Mi Perfil';
    protected static ?string $navigationLabel = 'Mi Perfil';
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static string $view = 'filament.pages.user-profile-page';
    protected static ?string $slug = 'profile';

    public string $activeTab = 'info';
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function changePassword()
    {
        $this->activeTab = 'password';
    }

    public function showAddresses()
    {
        $this->activeTab = 'addresses';
    }

    public function showOrders()
    {
        $this->activeTab = 'orders';
    }

    public function showInfo()
    {
        $this->activeTab = 'info';
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('filament.admin.auth.login');
    }

    protected function getViewData(): array
    {
        return [
            'user' => $this->user,
            'activeTab' => $this->activeTab,
        ];
    }
} 