<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PrendaResource;  // Importar el recurso RecetasResource
use App\Http\Requests\StorePrendasRequest;  // Importar la request StoreRecetasRequest
use App\Http\Requests\UpdatePrendasRequest; // Importar la request UpdateRecetasRequest
use Symfony\Component\HttpFoundation\Response; //Importar la 

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Importar el trait AuthorizesRequests para la autorización de políticas

use App\Http\Controllers\Controller;
use App\Models\Prenda; // Importar el modelo Prenda

use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Prenda",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="tipo", type="string", example="prenda"),
 *     @OA\Property(
 *         property="atributos",
 *         type="object",
 *         @OA\Property(property="titulo", type="string", example="Camiseta Deportiva"),
 *         @OA\Property(property="descripcion", type="string", example="Descripción de la prenda"),
 *         @OA\Property(property="imagen", type="string", example="prendas/imagen.jpg"),
 *         @OA\Property(property="categoria", type="string", example="Ropa Deportiva")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="PrendaCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Prenda")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="StorePrendaRequest",
 *     required={"categoria_id","titulo","descripcion","imagen"},
 *     @OA\Property(property="categoria_id", type="integer", example=1),
 *     @OA\Property(property="titulo", type="string", example="Prenda 1"),
 *     @OA\Property(property="descripcion", type="string", example="descripcion de la prenda"),
 *     @OA\Property(property="imagen", type="string", format="binary")
 * )
 * 
 * @OA\Tag(
 *     name="Prendas",
 *     description="Operaciones para gestionar prendas"
 * )
 */


class PrendaController extends Controller
{

    use AuthorizesRequests; // Usar el trait AuthorizesRequests para la autorización de políticas

 /**
     * @OA\Get(
     *     path="/prendas",
     *     summary="Obtener todas las prendas",
     *     tags={"Prendas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de prendas",
     *         @OA\JsonContent(ref="#/components/schemas/PrendaCollection")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */

    // Muestra todas las recetas
    public function index(){
        // return Receta::all(); // Devuelve todas las recetas
        // return Receta::with('categoria', 'etiquetas', 'user')->get(); // Carga las relaciones categoria, etiquetas y user
        $this->authorize('Ver prendas'); 
        $prendas = Prenda::with('categoria', 'productos', 'user')->get();
        return PrendaResource::collection($prendas); // Devuelve todas las recetas como recurso API
    }

  /**
     * @OA\Get(
     *     path="/prendas/{id}",
     *     summary="Obtener una prenda específica",
     *     tags={"Prendas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Prenda encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Prenda")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Prenda no encontrada"
     *     )
     * )
     */

    // Muestra una receta a partir de su id
    public function show(Prenda $prenda){
        // return $receta; // Devuelve la receta
        //return $receta->load('categoria', 'etiquetas', 'user'); // Carga las relaciones categoria, etiquetas y user
        $this->authorize('Ver prendas'); 
        $prenda = $prenda->load('categoria', 'productos', 'user');
        return new PrendaResource($prenda); // Devuelve la receta como recurso API 
    }

/**
     * @OA\Post(
     *     path="/prendas",
     *     summary="Crear una nueva prenda",
     *     tags={"Prendas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/StorePrendaRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Prenda creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Prenda")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */

    // Almacena una nueva prenda
    public function store(StorePrendasRequest $request){  // Usar la request StoreRecetasRequest para validar los datos
        $this->authorize('Crear prendas');    
        
        $prendas = $request->user()->prendas()->create($request->all());  // Crear una nueva receta asociada al usuario autenticado
        //$prendas->productos()->attach(json_decode($request->productos));  // Asociar las etiquetas a la receta (decodificar el JSON recibido)

        $prendas->imagen = $request->file('imagen')->store('prendas','public'); // Almacenar la imagen en el disco 'public' dentro de la carpeta 'recetas'
        $prendas->save(); // Guardar la receta con la ruta de la imagen

        //$prendas = Prenda::create($request->all());  // Crear una nueva receta con los datos validados
        //$prendas->productos()->attach(json_decode($request->productos));  // Asociar las etiquetas a la receta (decodificar el JSON recibido)

        // Devolver la receta creada como recurso API con código de estado 201 (creado) 
        return response()->json(new PrendaResource($prendas), Response::HTTP_CREATED); 
    }

/**
     * @OA\Put(
     *     path="/prendas/{id}",
     *     summary="Actualizar una prenda",
     *     tags={"Prendas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/StorePrendaRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Prenda actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Prenda")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */

    // Actualiza una receta existente
    public function update(UpdatePrendasRequest $request, Prenda $prenda){  // Usar la request UpdateRecetasRequest para validar los datos
      // $this->authorize('Editar prendas');

        //$this->authorize('update', $prendas);  // Autorizar la acción usando la política RecetaPolicy
       
        $prenda->update($request->all());  // Actualizar la receta con los datos validados
/*
        if($productos = json_decode($request->productos)){  // Si se reciben etiquetas, decodificar el JSON
            $prendas->productos()->sync($productos);  // Sincronizar las etiquetas (eliminar las que no están y agregar las nuevas)
        }
*/
        if($request->hasFile('imagen')){  // Si se recibe una imagen
            $prenda->imagen = $request->file('imagen')->store('prendas','public');  // Almacenar la imagen en el disco 'public' dentro de la carpeta 'recetas'
            $prenda->save(); // Guardar la receta con la ruta de la imagen
        }

        $prenda->load('categoria', 'productos', 'user');

        // Devolver la receta actualizada como recurso API con código de estado 200 (OK)
        return response()->json(new PrendaResource($prenda), Response::HTTP_OK);
    }

/**
     * @OA\Delete(
     *     path="/prendas/{id}",
     *     summary="Eliminar una prenda",
     *     tags={"Prendas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Prenda eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Prenda no encontrada"
     *     )
     * )
     */

    // Elimina una receta existente
    public function destroy(Prenda $prenda){  // Inyectar la prenda a eliminar
     
        $this->authorize('Eliminar prendas');
        
       // $this->authorize('delete', $prendas);  // Autorizar la acción usando la política RecetaPolicy 
     
        $prenda->delete();  // Eliminar la prenda

        // Devolver una respuesta vacía con código de estado 204 (No Content)
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    
}