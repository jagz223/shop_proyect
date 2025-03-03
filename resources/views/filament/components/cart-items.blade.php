@if ($cart->isNotEmpty())
    <div class="space-y-2" wire:poll>
        @foreach ($cart as $item)
            <div class="flex items-center justify-between p-2 bg-gray-100 dark:bg-gray-800 rounded-lg shadow">
                <div class="flex items-center space-x-3">
                    <!-- Imagen del producto -->
                    <img src="{{ Storage::url($item['image'] ?? 'default.jpg') }}" alt="{{ $item['name'] }}" class="w-12 h-12 rounded-lg object-cover" style="width: 75px; height: 50px;">

                    <!-- Nombre del producto -->
                    <span class="text-sm font-medium" style="margin-right: 10px; margin-left: 10px;">{{ $item['name'] }}</span>
                </div>

                <!-- Precio del producto -->
                <div class="flex items-center space-x-4">
                    <span class="text-lg font-bold text-primary-600 dark:text-primary-400" style="margin-left: 10px; margin-right: 10px;">$ {{ number_format($item['price'], 2) }}</span>

                    <!-- Botón de eliminar -->
                    <x-filament::button
                        color="danger"
                        class="w-12 h-10 flex items-center justify-center rounded-lg"
                        wire:click="removeFromCart('{{ $item['instanceId'] }}')"
                    >
                        <x-filament::icon icon="heroicon-o-trash" class="w-5 h-5" />
                    </x-filament::button>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-gray-500">Tu carrito está vacío.</p>
@endif
