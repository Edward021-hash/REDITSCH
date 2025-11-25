<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreCategoriaRequest",
 *     required={"nombre"},
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         maxLength=255,
 *         example="Ropa Deportiva"
 *     ),
 *     @OA\Property(
 *         property="descripcion",
 *         type="string",
 *         nullable=true,
 *         example="Categoría para ropa deportiva"
 *     )
 * )
 */

class StoreCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cambiar si usas políticas
    }

    public function rules(): array
    
    {
        return [
           // 'categoria_id' => 'sometimes|exists:categorias,id', // La categoria_id es opcional y debe existir en la tabla categorias
            //'user_id' => 'sometimes|exists:users,id', // La usuario_id es opcional y debe existir en la tabla users
            'nombre' => 'sometimes|string|max:255', // El titulo es opcional, debe ser una cadena y no debe exceder los 255 caracteres
         //   'descripcion' => 'sometimes|string', // La descripcion es opcional y debe ser una cadena
         //   'imagen' => 'sometimes|image|max:2048', // La imagen es opcional, debe ser un archivo de imagen y no debe exceder los 2MB
            //'etiquetas' => 'sometimes|array', // Las etiquetas son opcionales y deben ser un array
        ];
    
    //{
      //  return [
     //       'nombre' => 'required|string|max:255|unique:categorias,nombre',
      //      'descripcion' => 'nullable|string',
       // ];
    }
}
 