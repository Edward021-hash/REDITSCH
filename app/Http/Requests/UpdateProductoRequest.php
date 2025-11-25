<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateProductoRequest",
 *     required={"nombre", "categoria_id"},
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         maxLength=255,
 *         example="Camiseta Deportiva Actualizada"
 *     ),
 *     @OA\Property(
 *         property="descripcion",
 *         type="string",
 *         nullable=true,
 *         example="Descripción actualizada del producto"
 *     ),
 *     @OA\Property(
 *         property="categoria_id",
 *         type="integer",
 *         example=1
 *     )
 * )
 */

class UpdateProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    } 

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255|unique:productos,nombre,' . ($this->producto->id ?? 'NULL'),
         //   'descripcion' => 'nullable|string',
         //   'precio' => 'required|numeric|min:0',
          //  'stock' => 'required|integer|min:0',
        ];
    }
}
