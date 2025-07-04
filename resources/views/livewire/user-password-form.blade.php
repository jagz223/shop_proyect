<form wire:submit.prevent="save">
    {{ $this->form }}
    <div class="flex justify-end mt-6">
        <x-filament::button type="submit" color="primary">
            Cambiar Contraseña
        </x-filament::button>
    </div>
</form>
