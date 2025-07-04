<div>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Mis Órdenes</h3>
            <div class="text-sm text-gray-500">
                {{ $orders->count() }} {{ $orders->count() === 1 ? 'orden' : 'órdenes' }}
            </div>
        </div>

        @if($orders->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-600">#{{ $order->id }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2">
                                            <p class="text-sm font-medium text-gray-900">
                                                Orden #{{ $order->id }}
                                            </p>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $this->getStatusColor($order->status) }}-100 text-{{ $this->getStatusColor($order->status) }}-800">
                                                {{ $this->getStatusLabel($order->status) }}
                                            </span>
                                        </div>
                                        <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                            <span>{{ $order->payment_method }}</span>
                                            <span>•</span>
                                            <span>{{ $order->delivery_method }}</span>
                                            <span>•</span>
                                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">
                                            ${{ number_format($order->total, 2) }}
                                        </p>
                                        @if($order->verification)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Verificado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Pendiente
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes órdenes</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Cuando realices una compra, aparecerá aquí.
                </p>
            </div>
        @endif
    </div>
</div> 