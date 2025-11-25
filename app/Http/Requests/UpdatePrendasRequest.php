<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdatePrendasRequest",
 *     required={"categoria_id","titulo","descripcion"},
 *     @OA\Property(
 *         property="categoria_id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="titulo",
 *         type="string",
 *         maxLength=255,
 *         example="Camiseta Deportiva Actualizada"
 *     ),
 *     @OA\Property(
 *         property="descripcion",
 *         type="string",
 *         example="Descripción actualizada de la prenda"
 *     ),
 *     @OA\Property(
 *         property="imagen",
 *         type="string",
 *         format="binary",
 *         nullable=true
 *     )
 * )
 */

class UpdatePrendasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;  // Permitir que cualquier usuario pueda hacer esta solicitud
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'categoria_id' => 'sometimes|exists:categorias,id', // La categoria_id es opcional y debe existir en la tabla categorias
            //'user_id' => 'sometimes|exists:users,id', // La usuario_id es opcional y debe existir en la tabla users
            'titulo' => 'sometimes|string|max:255', // El titulo es opcional, debe ser una cadena y no debe exceder los 255 caracteres
            'descripcion' => 'sometimes|string', // La descripcion es opcional y debe ser una cadena
            'imagen' => 'sometimes|image|max:2048', // La imagen es opcional, debe ser un archivo de imagen y no debe exceder los 2MB
            //'etiquetas' => 'sometimes|array', // Las etiquetas son opcionales y deben ser un array
        ];

    }
}
