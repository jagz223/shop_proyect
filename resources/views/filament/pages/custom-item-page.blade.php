<x-filament::page>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @foreach($items as $item)
            <x-filament::card>
                <div class="p-4">
                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover mb-4">
                    <h3 class="font-semibold text-lg text-left">{{ $item->name }}</h3>
                    <p class="text-sm text-gray-600 mb-4 text-left">{{ $item->description }}</p>
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-bold text-xl text-left">{{ number_format($item->price, 2) }}</span>
                        <!-- Botón circular pequeño con un '+' dentro -->
                        <x-filament::button color="primary" class="w-12 h-12 rounded-full text-2xl flex items-center justify-center p-0">
                            +
                        </x-filament::button>
                    </div>
                    <div class="mt-4">
                        <!-- Puedes agregar un segundo botón aquí si lo necesitas -->
                    </div>
                </div>
            </x-filament::card>
        @endforeach
    </div>
</x-filament::page>
