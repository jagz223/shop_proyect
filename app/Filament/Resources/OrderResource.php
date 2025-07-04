<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $isEdit = request()->routeIs('filament.resources.orders.edit');
        return $form->schema([
            Forms\Components\Hidden::make('user_id')
                ->default(fn () => request()->user()?->id),
            Forms\Components\Select::make('status')
                ->label('Estado')
                ->options([
                    'pending' => 'Pendiente',
                    'in_progress' => 'En Proceso',
                    'in_review' => 'Por Verificar',
                    'canceled' => 'Cancelado',
                    'completed' => 'Completado',
                ])
                ->default('pending')
                ->visible(fn ($context) => $context === 'edit')
                ->required(),
            Forms\Components\Select::make('payment_method')
                ->label('Método de pago')
                ->options([
                    'pago_movil' => 'Pago móvil',
                    'efectivo' => 'Efectivo',
                ])
                ->required()
                ->reactive(),
            Forms\Components\Hidden::make('verification')
                ->default(0),
            Forms\Components\Select::make('delivery_method')
                ->label('Forma de entrega')
                ->options([
                    'delivery' => 'Delivery',
                    'pick_up' => 'Pick Up',
                    'in_site' => 'En sitio',
                ])
                ->required(),
            Forms\Components\Select::make('address_id')
                ->label('Dirección')
                ->options(fn () => \App\Models\Address::where('user_id', request()->user()?->id)->pluck('name', 'id'))
                ->searchable()
                ->required(),
            Forms\Components\Textarea::make('details')
                ->label('Detalles')
                ->rows(4),
            Forms\Components\TextInput::make('cash_payment_amount')
                ->label('Monto en efectivo')
                ->numeric()
                ->visible(fn ($get) => $get('payment_method') === 'efectivo'),
            Forms\Components\Hidden::make('total')
                ->default(0),
            Forms\Components\Repeater::make('items')
                ->label('Items')
                ->schema([
                    Forms\Components\Select::make('item_id')
                        ->label('Item')
                        ->options(fn () => \App\Models\Item::pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $set, $get, $context) {
                            if ($state) {
                                $item = \App\Models\Item::find($state);
                                if ($item) {
                                    $set('item_price', $item->price);
                                }
                            }
                        }),
                    Forms\Components\Hidden::make('item_price'),
                ])
                ->columns(1)
                ->defaultItems(1)
                ->minItems(1)
                ->required()
                ->columnSpanFull()
                ->live()
                ->afterStateUpdated(function ($state, $set, $get) {
                    self::calculateTotal($get, $set);
                }),
            Forms\Components\Hidden::make('show_total')
                ->default(false),
            Forms\Components\Hidden::make('display_total')
                ->default(0),
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\Placeholder::make('total_display')
                        ->label('Total Calculado')
                        ->content(function ($get) {
                            $displayTotal = $get('display_total') ?? 0;
                            return 'VES ' . number_format($displayTotal, 2, ',', '.');
                        })
                        ->visible(fn ($get) => $get('show_total')),
                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('calculate_total')
                            ->label('Calcular Total')
                            ->button()
                            ->color('primary')
                            ->size('sm')
                            ->action(function ($get, $set) {
                                // Recalcular el total cuando se presiona el botón
                                $displayTotal = 0;
                                $items = $get('items') ?? [];
                                if (is_array($items)) {
                                    foreach ($items as $itemData) {
                                        if (isset($itemData['item_id']) && $itemData['item_id']) {
                                            $item = \App\Models\Item::find($itemData['item_id']);
                                            if ($item) {
                                                $displayTotal += $item->price;
                                            }
                                        }
                                    }
                                }
                                // Asignar el total calculado a la variable de display
                                $set('display_total', $displayTotal);
                                // Hacer visible el total
                                $set('show_total', true);
                            }),
                    ])
                        ->alignRight(),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->sortable(),
                Tables\Columns\TextColumn::make('items.name')
                    ->label('Items')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(3)
                    ->expandableLimitedList(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('VES')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Método de pago')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pago_movil' => 'Pago móvil',
                        'efectivo' => 'Efectivo',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('delivery_method')
                    ->label('Entrega')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'delivery' => 'Delivery',
                        'pick_up' => 'Pick Up',
                        'in_site' => 'En sitio',
                        default => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'in_progress' => 'En Proceso',
                        'in_review' => 'Por Verificar',
                        'canceled' => 'Cancelado',
                        'completed' => 'Completado',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    private static function calculateTotal($get, $set)
    {
        $total = 0;
        $items = $get('items') ?? [];
        if (is_array($items)) {
            foreach ($items as $itemData) {
                if (isset($itemData['item_price']) && is_numeric($itemData['item_price'])) {
                    $total += $itemData['item_price'];
                }
            }
        }
        $set('total', $total);
    }
}
