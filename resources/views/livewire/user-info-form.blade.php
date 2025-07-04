<form wire:submit.prevent="save">
    {{ $this->form }}
    <div class="flex justify-end mt-6">
        <x-filament::button type="submit" color="primary">
            Guardar Cambios
        </x-filament::button>
    </div>
</form>
