<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserPasswordForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $user;

    public function mount($user = null)
    {
        $this->user = $user ?? Auth::user();
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Cambiar Contraseña')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('currentPassword')
                                ->label('Contraseña Actual')
                                ->password()
                                ->required(),
                            TextInput::make('newPassword')
                                ->label('Nueva Contraseña')
                                ->password()
                                ->required(),
                            TextInput::make('confirmPassword')
                                ->label('Confirmar Nueva Contraseña')
                                ->password()
                                ->required(),
                        ]),
                ])
                ->collapsible()
                ->collapsed(false),
        ];
    }

    public function save()
    {
        $data = $this->form->getState();
        $this->validatePassword($data);
        $this->user->update([
            'password' => Hash::make($data['newPassword'])
        ]);
        Notification::make()
            ->title('Contraseña actualizada')
            ->success()
            ->send();
        $this->form->fill([
            'currentPassword' => '',
            'newPassword' => '',
            'confirmPassword' => '',
        ]);
    }

    protected function validatePassword($data)
    {
        if (!Hash::check($data['currentPassword'], $this->user->password)) {
            Notification::make()
                ->title('Error')
                ->body('La contraseña actual es incorrecta')
                ->danger()
                ->send();
            throw new \Exception('Contraseña actual incorrecta');
        }
        if ($data['newPassword'] !== $data['confirmPassword']) {
            Notification::make()
                ->title('Error')
                ->body('Las contraseñas no coinciden')
                ->danger()
                ->send();
            throw new \Exception('Las contraseñas no coinciden');
        }
        if (strlen($data['newPassword']) < 8) {
            Notification::make()
                ->title('Error')
                ->body('La nueva contraseña debe tener al menos 8 caracteres')
                ->danger()
                ->send();
            throw new \Exception('Contraseña muy corta');
        }
    }

    public function render()
    {
        return view('livewire.user-password-form');
    }
}
