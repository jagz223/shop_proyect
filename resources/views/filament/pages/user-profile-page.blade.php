<x-filament::page class="relative min-h-screen">
    <!-- 🔹 Navegación de pestañas -->
    <div class="flex gap-2 mb-6 border-b border-gray-200">
        <button
            wire:click="showInfo"
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors {{ $activeTab === 'info' ? 'bg-primary-500 text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}"
        >
            <x-heroicon-o-user class="w-4 h-4 inline mr-2" />
            Información
        </button>
        
        <button
            wire:click="showAddresses"
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors {{ $activeTab === 'addresses' ? 'bg-primary-500 text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}"
        >
            <x-heroicon-o-map-pin class="w-4 h-4 inline mr-2" />
            Direcciones
        </button>
        
        <button
            wire:click="showOrders"
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors {{ $activeTab === 'orders' ? 'bg-primary-500 text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}"
        >
            <x-heroicon-o-shopping-bag class="w-4 h-4 inline mr-2" />
            Órdenes
        </button>
        
        <button
            wire:click="changePassword"
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors {{ $activeTab === 'password' ? 'bg-primary-500 text-white' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}"
        >
            <x-heroicon-o-key class="w-4 h-4 inline mr-2" />
            Cambiar Contraseña
        </button>
        
        <button
            wire:click="logout"
            class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors text-red-600 hover:text-red-700 hover:bg-red-50 ml-auto"
        >
            <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4 inline mr-2" />
            Cerrar Sesión
        </button>
    </div>

    <!-- 🔹 Contenido de las pestañas -->
    <div class="space-y-6">
        <!-- Pestaña de Información -->
        @if($activeTab === 'info')
            <x-filament::card>
                <div class="p-6">
                    @livewire('user-info-form', ['user' => $user])
                </div>
            </x-filament::card>
        @endif

        <!-- Pestaña de Direcciones -->
        @if($activeTab === 'addresses')
            <x-filament::card>
                <div class="p-6">
                    @livewire('user-addresses', ['user' => $user])
                </div>
            </x-filament::card>
        @endif

        <!-- Pestaña de Órdenes -->
        @if($activeTab === 'orders')
            <x-filament::card>
                <div class="p-6">
                    @livewire('user-orders', ['user' => $user])
                </div>
            </x-filament::card>
        @endif

        <!-- Pestaña de Cambiar Contraseña -->
        @if($activeTab === 'password')
            <x-filament::card>
                <div class="p-6">
                    @livewire('user-password-form', ['user' => $user])
                </div>
            </x-filament::card>
        @endif
    </div>
</x-filament::page> 