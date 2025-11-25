<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;  // Para verificar contraseñas
use Symfony\Component\HttpFoundation\Response;  // Para usar códigos de estado HTTP
use App\Models\User;  // Importar el modelo User

/**
 * @OA\Info(
 *     title="Luna Athletics API",
 *     version="1.0.0",
 *     description="API para gestión de productos y categorías de Luna Athletics",
 *     @OA\Contact(
 *         email="admin@lunaathletics.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Servidor Local"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * 
 * @OA\Schema(
 *     schema="LoginResponse",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         @OA\Property(
 *             property="attributes",
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="nombre", type="string", example="Juan Pérez"),
 *             @OA\Property(property="correo", type="string", example="usuario@example.com")
 *         ),
 *         @OA\Property(property="token", type="string", example="1|randomtoken123")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="LogoutResponse",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="Cierre de sesión exitoso.")
 * )
 * 
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Operaciones de login y logout"
 * )
 */
class LoginController extends Controller
{

 /**
     * @OA\Post(
     *     path="/login",
     *     summary="Iniciar sesión",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login exitoso",
     *         @OA\JsonContent(ref="#/components/schemas/LoginResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Credenciales inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Las credenciales son incorrectas.")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required',
            'dispositivo' => 'required',
        ]);
   
        // Buscar el usuario por correo electrónico
        $user = User::where('email', $request->correo)->first();
   
        // Verificar si el usuario existe y la contraseña es correcta
        if (!$user || ! Hash::check($request->contraseña, $user->password)) {
            return response()->json([
                'message' => 'Las credenciales son incorrectas.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        }

        // Generar un token de acceso para el usuario
        return response()->json([
            'data' => [
                'attributes' => [
                    'id' => $user->id,
                    'nombre' => $user->name,
                    'correo' => $user->email,
                ],
                'token' => $user->createToken($request->dispositivo)->plainTextToken,
            ],
        ],Response::HTTP_OK); // 200
    }

/**
     * @OA\Post(
     *     path="/logout",
     *     summary="Cerrar sesión",
     *     tags={"Autenticación"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout exitoso",
     *         @OA\JsonContent(ref="#/components/schemas/LogoutResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */

    public function destroy(Request $request)
    {
        // Revocar el token de acceso del usuario autenticado
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Cierre de sesión exitoso.',
        ], Response::HTTP_OK); // 200
    }

}
