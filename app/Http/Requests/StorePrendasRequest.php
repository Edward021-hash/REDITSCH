<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StorePrendasRequest",
 *     required={"categoria_id","titulo","descripcion","imagen"},
 *     @OA\Property(
 *         property="categoria_id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="titulo",
 *         type="string",
 *         maxLength=255,
 *         example="Camiseta Deportiva"
 *     ),
 *     @OA\Property(
 *         property="descripcion",
 *         type="string",
 *         example="Descripción de la prenda deportiva"
 *     ),
 *     @OA\Property(
 *         property="imagen",
 *         type="string",
 *         format="binary"
 *     )
 * )
 */

class StorePrendasRequest extends FormRequest
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
           'categoria_id' => 'required|exists:categorias,id', // La categoria_id es obligatoria y debe existir en la tabla categorias
            //'user_id' => 'required|exists:users,id', // La usuario_id es obligatoria y debe existir en la tabla users
            'titulo' => 'required|string|max:255', // El titulo es obligatorio, debe ser una cadena y no debe exceder los 255 caracteres
            'descripcion' => 'required|string', // La descripcion es obligatoria y debe ser una cadena
            'imagen' => 'required|mimes:webp,jpeg,png,jpg,gif,svg|max:2048', // La imagen es opcional, debe ser un archivo de imagen y no debe exceder los 2MB
            //'etiquetas' => 'required', // Las etiquetas son opcionales y deben ser un array
        ];

    }
}
