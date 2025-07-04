<div>
    <h3 class="text-lg font-semibold mb-4">Mis Direcciones</h3>
    @if(isset($user) && $user->addresses->count())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($user->addresses as $address)
                <div class="border rounded-lg p-4 relative">
                    @if($address->is_default)
                        <span class="absolute top-2 right-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                            Principal
                        </span>
                    @endif
                    <h4 class="font-medium text-gray-900">{{ $address->name }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ $address->address }}</p>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <x-heroicon-o-map-pin class="w-12 h-12 text-gray-400 mx-auto mb-4" />
            <p class="text-gray-500">No tienes direcciones guardadas</p>
        </div>
    @endif
</div>
