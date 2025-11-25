<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="LoginRequest",
 *     required={"correo","contraseña","dispositivo"},
 *     @OA\Property(
 *         property="correo",
 *         type="string",
 *         format="email",
 *         example="Edward@example.com"
 *     ),
 *     @OA\Property(
 *         property="contraseña",
 *         type="string",
 *         format="password",
 *         example="password"
 *     ),
 *     @OA\Property(
 *         property="dispositivo",
 *         type="string",
 *         example="windows"
 *     )
 * )
 */


class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'correo' => 'required|email',
            'contraseña' => 'required|string|min:3',
            'dispositivo' => 'required|string'
        ];
    }
}
