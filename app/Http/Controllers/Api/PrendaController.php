<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PrendaResource;  // Importar el recurso RecetasResource
use App\Http\Requests\StorePrendasRequest;  // Importar la request StoreRecetasRequest
use App\Http\Requests\UpdatePrendasRequest; // Importar la request UpdateRecetasRequest
use Symfony\Component\HttpFoundation\Response; //Importar la 

use App\Http\Controllers\Controller;
use App\Models\Prenda; // Importar el modelo Prenda

use Illuminate\Http\Request;


class PrendaController extends Controller
{
    // Muestra todas las recetas
    public function index(){
        // return Receta::all(); // Devuelve todas las recetas
        // return Receta::with('categoria', 'etiquetas', 'user')->get(); // Carga las relaciones categoria, etiquetas y user
        $prendas = Prenda::with('categoria', 'productos', 'user')->get();
        return PrendaResource::collection($prendas); // Devuelve todas las recetas como recurso API
    }

    // Muestra una receta a partir de su id
    public function show(Prenda $prendas){
        // return $receta; // Devuelve la receta
        //return $receta->load('categoria', 'etiquetas', 'user'); // Carga las relaciones categoria, etiquetas y user
        $prendas = $prendas->load('categoria', 'productos', 'user');
        return new PrendaResource($prendas); // Devuelve la receta como recurso API 
    }

    // Almacena una nueva receta 
    public function store(StorePrendasRequest $request){  // Usar la request StoreRecetasRequest para validar los datos
        $prendas = Prenda::create($request->all());  // Crear una nueva receta con los datos validados
        $prendas->productos()->attach(json_decode($request->productos));  // Asociar las etiquetas a la receta (decodificar el JSON recibido)
       
        // Devolver la receta creada como recurso API con código de estado 201 (creado) 
        return response()->json(new PrendaResource($prendas), Response::HTTP_CREATED); 
    }

    // Actualiza una receta existente
    public function update(UpdatePrendasRequest $request, Prenda $prendas){  // Usar la request UpdateRecetasRequest para validar los datos
        $prendas->update($request->all());  // Actualizar la receta con los datos validados

        if($productos = json_decode($request->productos)){  // Si se reciben etiquetas, decodificar el JSON
            $prendas->productoss()->sync($productos);  // Sincronizar las etiquetas (eliminar las que no están y agregar las nuevas)
        }

        // Devolver la receta actualizada como recurso API con código de estado 200 (OK)
        return response()->json(new PrendaResource($prendas), Response::HTTP_OK);
    }

    // Elimina una receta existente
    public function destroy(Prenda $prendas){  // Inyectar la receta a eliminar
        $prendas->delete();  // Eliminar la receta

        // Devolver una respuesta vacía con código de estado 204 (No Content)
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    
}