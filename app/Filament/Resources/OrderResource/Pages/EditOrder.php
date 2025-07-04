<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Obtener los items de la orden y expandirlos según la cantidad
        $order = $this->getRecord();
        $items = [];
        $total = 0;

        foreach ($order->items as $item) {
            for ($i = 0; $i < $item->pivot->quantity; $i++) {
                $items[] = [
                    'item_id' => $item->id,
                    'item_price' => $item->price
                ];
                $total += $item->price;
            }
        }

        $data['items'] = $items;
        $data['total'] = $total;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

        // Actualizar la orden
        $this->getRecord()->update($data);

        // Limpiar items existentes
        $this->getRecord()->items()->detach();

        // Contar cuántas veces se seleccionó cada item
        $itemCounts = array_count_values($itemIds);

        // Procesar los items seleccionados
        foreach ($itemCounts as $itemId => $quantity) {
            $this->getRecord()->items()->attach($itemId, ['quantity' => $quantity]);
        }

        return $data;
    }
}
