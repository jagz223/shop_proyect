<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cambia esto según tus necesidades de autorización
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Permite imágenes de hasta 2MB
            'price' => 'required|numeric|min:0.01',
            'discount' => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre del artículo es obligatorio.',
            'description.required' => 'La descripción del artículo es obligatoria.',
            'image.image' => 'El archivo de la imagen debe ser una imagen.',
            'price.required' => 'El precio es obligatorio.',
            'discount.numeric' => 'El descuento debe ser un número.',
        ];
    }
}
