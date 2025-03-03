<?php

namespace App\Filament\Pages;

use App\Models\Category;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;

class CustomItemPage extends Page
{
    use WithPagination;

    protected static ?string $title = 'Items';
    protected static ?string $navigationLabel = 'Items';
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static string $view = 'filament.pages.custom-item-page';

    public Collection $cart;
    public ?int $selectedCategory = null;
    public $searchQuery = '';

    public function mount()
    {
        $this->cart = collect();
    }

    public function getCategories()
    {
        return Category::all();
    }

    // 🔹 Obtener los items filtrados por la categoría seleccionada
    public function getItems()
    {
        $query = Item::query();

        if ($this->selectedCategory) {
            $query->whereHas('categories', function ($q) {
                $q->where('categories.id', $this->selectedCategory);
            });
        }

        if ($this->searchQuery) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $this->searchQuery . '%');
            });
        }

        return $query->get();
    }

    protected function getViewData(): array
    {
        return [
            'categories' => $this->getCategories(),
            'items' => $this->getItems(),
            'cartCount' => $this->cart->count(),
            'cartTotal' => $this->getCartTotal(),
            'searchQuery' => $this->searchQuery,
        ];
    }

    public function filterByCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->resetPage(); // 🔹 Reinicia paginación al cambiar de categoría
    }

    public function clearFilter()
    {
        $this->selectedCategory = null;
    }

    public function resetSearch()
    {
        $this->searchQuery = '';
        $this->resetPage();
    }

    // 🔹 Método para agregar items al carrito
    public function addToCart($itemId)
    {
        $item = Item::find($itemId);
        if ($item) {
            // Crear un identificador único para cada instancia del item
            $instanceId = uniqid(); // Esto genera un ID único para cada producto agregado

            // Agregar una nueva instancia del item al carrito
            $this->cart->push([
                'instanceId' => $instanceId, // Asignamos un ID único a la instancia
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'image' => $item->image, // Asegúrate de pasar la imagen del producto
            ]);
        }
    }

    // 🔹 Método para eliminar una instancia específica del carrito
    public function removeFromCart($instanceId)
    {
        // Eliminamos solo la instancia específica
        $this->cart = $this->cart->reject(fn ($item) => $item['instanceId'] === $instanceId);

        // Emitimos el evento para actualizar el modal usando dispatch
        $this->dispatch('cartUpdated');
    }




    // 🔹 Definir la acción del modal
    protected function getActions(): array
    {
        return [
            Action::make('verCarrito')
                ->label(fn () => 'Abrir Carrito ($' . number_format($this->getCartTotal(), 2) . ')') // Mostrar total en $
                ->modalHeading('Tu Carrito')
                ->modalSubheading('Estos son los productos que has agregado.')
                ->modalButton('Cerrar')
                ->form([
                    \Filament\Forms\Components\View::make('filament.components.cart-items')
                        ->viewData(['cart' => $this->cart]), // Pasamos el carrito
                ])
                ->action(fn () => null),
        ];
    }

    // 🔹 Método para calcular el total del carrito
    public function getCartTotal(): float
    {
        return $this->cart->sum(fn ($item) => $item['price']);
    }

}
