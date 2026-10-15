<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validación de entradas y salidas. El tercero (proveedor o cliente) se
 * indica con la clave que usa cada formulario: "proveedor_id" o "cliente_id".
 */
class MovimientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $tercero = $this->routeIs('entradas.*') ? 'proveedor' : 'cliente';
        $tabla = $tercero === 'proveedor' ? 'proveedores' : 'clientes';

        return [
            'producto_id' => ['required', Rule::exists('productos', 'id')->whereNull('deleted_at')],
            "{$tercero}_id" => ['required', Rule::exists($tabla, 'id')->whereNull('deleted_at')],
            'cantidad' => ['required', 'integer', 'min:1', 'max:1000000'],
        ];
    }
}
