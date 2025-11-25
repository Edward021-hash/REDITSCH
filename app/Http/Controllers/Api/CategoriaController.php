<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoriaResource; // Importar el recurso CategoriaResource 
use App\Http\Resources\CategoriaCollection;  // Importar el recurso CategoriaCollection

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Categoria; // Importar el modelo Categoria

/**
 * @OA\Schema(
 *     schema="Categoria",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="tipo", type="string", example="categoria"),
 *     @OA\Property(
 *         property="atributos",
 *         type="object",
 *         @OA\Property(property="nombre", type="string", example="Ropa Deportiva"),
 *         @OA\Property(property="descripcion", type="string", example="Descripción de la categoría")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="CategoriaCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Categoria")
 *     )
 * )
 * 
 * @OA\Tag(
 *     name="Categorías",
 *     description="Operaciones para gestionar categorías"
 * )
 */



class CategoriaController extends Controller
{
 use AuthorizesRequests;

  /**
     * @OA\Get(
     *     path="/categorias",
     *     summary="Obtener todas las categorías",
     *     tags={"Categorías"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorías",
     *         @OA\JsonContent(ref="#/components/schemas/CategoriaCollection")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */

    // Muestra todas las categorias
    public function index(){
        // return Categoria::all(); // Devuelve todas las categorias
      //  return new CategoriaCollection(Categoria::all());  // Devuelve todas las categorias como recurso API
          $this->authorize('Ver categorias');
        $categorias = Categoria::with('prendas')->get();
        return new CategoriaCollection($categorias);
    }

/**
     * @OA\Get(
     *     path="/categorias/{id}",
     *     summary="Obtener una categoría específica",
     *     tags={"Categorías"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Categoria")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada"
     *     )
     * )
     */

    // Muestra una categoria a partir de su id
    public function show(Categoria $categoria){
        // return $categoria; // Devuelve la categoria
      //  $categoria = $categoria->load('prendas');  // Carga las recetas relacionadas con la categoria
      //  return new CategoriaResource($categoria);  // Devuelve la categoria como recurso API 
         $this->authorize('Ver categorias');

        $categoria->load('prendas');

        return new CategoriaResource($categoria);
    }

/**
     * @OA\Post(
     *     path="/categorias",
     *     summary="Crear una nueva categoría",
     *     tags={"Categorías"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreCategoriaRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoría creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Categoria")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */


    // Crear categoría
    public function store(StoreCategoriaRequest $request)
    {
        $this->authorize('Crear categorias');

        $categoria = Categoria::create($request->validated());

        return response()->json(
            new CategoriaResource($categoria),
            Response::HTTP_CREATED
        );
    }

 /**
     * @OA\Put(
     *     path="/categorias/{id}",
     *     summary="Actualizar una categoría",
     *     tags={"Categorías"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCategoriaRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Categoria")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */

    // Actualizar categoría
    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        $this->authorize('Editar categorias');

        $categoria->update($request->validated());

        $categoria->load('prendas');

        return response()->json(
            new CategoriaResource($categoria),
            Response::HTTP_OK
        );
    } 

/**
     * @OA\Delete(
     *     path="/categorias/{id}",
     *     summary="Eliminar una categoría",
     *     tags={"Categorías"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Categoría eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada"
     *     )
     * )
     */

    // Eliminar categoría
    public function destroy(Categoria $categoria)
    {
        $this->authorize('Eliminar categorias');

        $categoria->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

}
