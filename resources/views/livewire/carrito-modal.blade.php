<x-filament::modal
    id="carrito-modal"
    close-button
    wire:model="isOpen"
>
    <x-slot name="header">
        <h2 class="text-lg font-bold">Tu Carrito</h2>
    </x-slot>

    <div class="p-4">
        <p>Aquí puedes mostrar los productos agregados al carrito.</p>
    </div>

    <x-slot name="footer">
        <x-filament::button color="danger" wire:click="close">
            Cerrar
        </x-filament::button>
    </x-slot>
</x-filament::modal>
