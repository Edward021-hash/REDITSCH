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


class PrendaController extends Controller
{

    use AuthorizesRequests; // Usar el trait AuthorizesRequests para la autorización de políticas

    // Muestra todas las recetas
    public function index(){
        // return Receta::all(); // Devuelve todas las recetas
        // return Receta::with('categoria', 'etiquetas', 'user')->get(); // Carga las relaciones categoria, etiquetas y user
        $this->authorize('Ver prendas'); 
        $prendas = Prenda::with('categoria', 'productos', 'user')->get();
        return PrendaResource::collection($prendas); // Devuelve todas las recetas como recurso API
    }

    // Muestra una receta a partir de su id
    public function show(Prenda $prenda){
        // return $receta; // Devuelve la receta
        //return $receta->load('categoria', 'etiquetas', 'user'); // Carga las relaciones categoria, etiquetas y user
        $this->authorize('Ver prendas'); 
        $prenda = $prenda->load('categoria', 'productos', 'user');
        return new PrendaResource($prenda); // Devuelve la receta como recurso API 
    }

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



    // Elimina una receta existente
    public function destroy(Prenda $prendas){  // Inyectar la prenda a eliminar
     
        $this->authorize('Eliminar prendas');
        
       // $this->authorize('delete', $prendas);  // Autorizar la acción usando la política RecetaPolicy 
     
        $prendas->delete();  // Eliminar la prenda

        // Devolver una respuesta vacía con código de estado 204 (No Content)
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    
}