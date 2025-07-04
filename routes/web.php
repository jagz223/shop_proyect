<?php

use Illuminate\Support\Facades\Route;
use App\Models\Item;

Route::get('/', function () {
    return view('welcome');
});

// Ruta API para obtener el precio de un item
Route::get('/api/items/{id}', function ($id) {
    $item = Item::find($id);
    if ($item) {
        return response()->json(['price' => $item->price]);
    }
    return response()->json(['error' => 'Item not found'], 404);
});
