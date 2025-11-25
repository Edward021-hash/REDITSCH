<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductoResource;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Http\Controllers\Controller;
use App\Models\Producto;

/**
 * @OA\Schema(
 *     schema="Producto",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="tipo", type="string", example="producto"),
 *     @OA\Property(
 *         property="atributos",
 *         type="object",
 *         @OA\Property(property="categoria", type="string", example="Ropa Deportiva"),
 *         @OA\Property(property="nombre", type="string", example="Camiseta Deportiva"),
 *         @OA\Property(property="descripcion", type="string", example="Descripción del producto")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="ProductoCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Producto")
 *     )
 * )
 * 
 * @OA\Tag(
 *     name="Productos",
 *     description="Operaciones para gestionar productos"
 * )
 */

class ProductoController extends Controller
{
    use AuthorizesRequests;

/**
     * @OA\Get(
     *     path="/productos",
     *     summary="Obtener todos los productos",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos",
     *         @OA\JsonContent(ref="#/components/schemas/ProductoCollection")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */

    // Listar productos
    public function index()
    {
        $this->authorize('Ver productos');

        $productos = Producto::with('prendas.categoria', 'prendas.productos', 'prendas.user')->get();

        return ProductoResource::collection($productos);
    }

/**
     * @OA\Get(
     *     path="/productos/{id}",
     *     summary="Obtener un producto específico",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Producto")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */

    // Mostrar un producto específico
    public function show(Producto $producto)
    {
        $this->authorize('Ver productos');

        $producto->load('prendas.categoria', 'prendas.productos', 'prendas.user');

        return new ProductoResource($producto);
    }

/**
     * @OA\Post(
     *     path="/productos",
     *     summary="Crear un nuevo producto",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreProductoRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Producto")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */

    // Crear producto
    public function store(StoreProductoRequest $request)
    {
        $this->authorize('Crear productos');

        $producto = Producto::create($request->validated());

        return response()->json(
            new ProductoResource($producto),
            Response::HTTP_CREATED
        );
    }

 /**
     * @OA\Put(
     *     path="/productos/{id}",
     *     summary="Actualizar un producto",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateProductoRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Producto")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */

    // Actualizar producto
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $this->authorize('Editar productos');

        $producto->update($request->validated());

        $producto->load('prendas.categoria', 'prendas.productos', 'prendas.user');

        return response()->json(
            new ProductoResource($producto),
            Response::HTTP_OK
        );
    }

/**
     * @OA\Delete(
     *     path="/productos/{id}",
     *     summary="Eliminar un producto",
     *     tags={"Productos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Producto eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */

    // Eliminar producto
    public function destroy(Producto $producto)
    {
        $this->authorize('Eliminar productos');

        $producto->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
