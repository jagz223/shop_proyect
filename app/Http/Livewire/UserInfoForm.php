<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class UserInfoForm extends Component implements Forms\Contracts\HasForms
{
    use InteractsWithForms;

    public $user;
    public $formData = [];

    public function mount($user = null)
    {
        $this->user = $user ?? Auth::user();
        $this->form->fill([
            'name' => $this->user->name,
            'email' => $this->user->email,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Información Personal')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('name')
                                ->label('Nombre')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Correo Electrónico')
                                ->email()
                                ->required()
                                ->maxLength(255),
                        ]),
                ])
                ->collapsible()
                ->collapsed(false),
        ];
    }

    public function save()
    {
        $data = $this->form->getState();
        $this->user->update($data);
        Notification::make()
            ->title('Información actualizada')
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.user-info-form');
    }
} 