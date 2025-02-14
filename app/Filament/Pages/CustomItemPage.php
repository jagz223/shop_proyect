<?php

namespace App\Filament\Pages;

use App\Models\Item;
use Filament\Pages\Page;
use Filament\Infolists\Components\Card;

class CustomItemPage extends Page
{
    protected static ?string $title = 'Items';
    protected static ?string $navigationLabel = 'Items';
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static string $view = 'filament.pages.custom-item-page';

    // Cargar los items para la vista
    public function getItems()
    {
        return Item::all(); // Aquí puedes aplicar filtros o personalizaciones
    }

    // Pasar los datos a la vista utilizando la propiedad viewData
    protected function getViewData(): array
    {
        return [
            'items' => $this->getItems(),
        ];
    }

    protected static function getNavigation() {
        return null;
    }

    // Deshabilitar el panel de la izquierda
    public static function panel(): void
    {
        // Sobrescribir panel y poner título "Orden"
        \Filament\Facades\Filament::panel()
            ->default()
            ->id('orden')
            ->path('orden')
            ->header('<h2>Orden</h2>'); // Título en el panel
    }
}
