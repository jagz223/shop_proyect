<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extraer los items del array de datos
        $items = $data['items'] ?? [];
        unset($data['items']); // Remover items del array principal

        // Calcular el total basado en los items
        $total = 0;
        $itemIds = [];
        foreach ($items as $itemData) {
            if (isset($itemData['item_price']) && is_numeric($itemData['item_price'])) {
                $total += $itemData['item_price'];
            }
            $itemIds[] = $itemData['item_id'];
        }

        // Actualizar el total en los datos
        $data['total'] = $total;

        // Guardar la orden
        $order = $this->getModel()::create($data);

        // Contar cuántas veces se seleccionó cada item
        $itemCounts = array_count_values($itemIds);

        // Procesar los items seleccionados
        foreach ($itemCounts as $itemId => $quantity) {
            $order->items()->attach($itemId, ['quantity' => $quantity]);
        }

        // Establecer el record para que Filament no cree otra orden
        $this->record = $order;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
