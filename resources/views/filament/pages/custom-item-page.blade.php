<x-filament::page class="relative min-h-screen">
    <!-- 🔹 Campo de búsqueda en tiempo real -->
    <div class="flex gap-2 mb-4 items-center">
        <div class="relative flex-1 max-w-md">
            <x-filament::input
                type="search"
                wire:model.live="searchQuery"
                placeholder="Buscar productos..."
                class="w-full"
            />
            @if($searchQuery)
                <button
                    wire:click="resetSearch"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                >
                    <x-heroicon-o-x-mark class="w-5 h-5" />
                </button>
            @endif
        </div>
    </div>

    <!-- 🔹 Filtros de categorías -->
    <div class="flex gap-2 mb-4">
        <x-filament::button
            color="{{ is_null($selectedCategory) ? 'primary' : 'secondary' }}"
            wire:click="clearFilter"
        >
            Todos
        </x-filament::button>

        @foreach($categories as $category)
            <x-filament::button
                color="{{ $selectedCategory === $category->id ? 'primary' : 'secondary' }}"
                wire:click="filterByCategory({{ $category->id }})"
            >
                {{ $category->name }}
            </x-filament::button>
        @endforeach
    </div>

    <!-- 🔹 Lista de items filtrados -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @foreach($items as $item)
            <x-filament::card>
                <div class="p-4">
                    <img src="{{ Storage::url($item->image) }}"
                        alt="{{ $item->name }}"
                        class="w-full h-auto object-cover rounded-lg aspect-square"
                        style="height: 225px"
                    />

                    <h3 class="font-semibold text-lg text-left" style="margin-top: 5px">{{ $item->name }}</h3>
                    <p class="text-sm text-gray-600 mb-4 text-left">{{ $item->description }}</p>
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-bold text-xl text-left">$ {{ number_format($item->price, 2) }}</span>
                        <x-filament::button
                            color="primary"
                            class="w-12 h-12 rounded-full text-2xl flex items-center justify-center p-0"
                            wire:click="addToCart({{ $item->id }})"
                        >
                            +
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::card>
        @endforeach
    </div>
</x-filament::page>
