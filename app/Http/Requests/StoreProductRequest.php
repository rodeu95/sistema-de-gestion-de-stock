<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'codigo' => 'required|string|max:250',
            'nombre' => 'required|string|max:250',
            'descripcion' => 'nullable|string|max:1000',
            'unidad' => 'required|string',
            'fchVto' => 'date',
            'stock_minimo' => 'required|numeric',
            'precio_costo' => 'required|numeric',
            'iva' => 'required|numeric',
            'utilidad' => 'required|numeric',
            'precio_venta' => 'required|numeric',
        ];
    }
}
