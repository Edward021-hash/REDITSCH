<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductoResource;  // Importar el recurso ProductoResource


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Producto;  // Importar el modelo Producto


class ProductoController extends Controller
{
   // Muestra todas las etiquetas
    public function index(){
        // return Etiqueta::all(); // Devuelve todas las etiquetas
        // return Etiqueta::with('recetas')->get(); // Carga las recetas asociadas a la etiqueta
        // return EtiquetaResource::collection(Etiqueta::with('recetas')->get()); // Devuelve todas las etiquetas como recurso API

        $productos = Producto::with('prendas.categoria', 'prendas.productos', 'prendas.user')->get();  // Carga las relaciones anidadas: categoria, productos y user de las prendas 
        return ProductoResource::collection($productos);  // Devuelve todas las etiquetas como recurso API con relaciones anidadas
    }

    // Muestra una etiqueta a partir de su id
    public function show(Producto $producto){ 
        // return $producto; // Devuelve la prenda
        // return $producto->load('prendas'); // Carga las recetas asociadas a la etiqueta
        // return new ProductosResource($productos->load('prenda')); // Devuelve la etiqueta como recurso API 

        $producto = $producto->load('prendas.categoria', 'prendas.productos', 'prendas.user');  // Carga las relaciones anidadas: categoria, etiquetas y user de las recetas
        return new ProductoResource($producto); // Devuelve la etiqueta como recurso API con relaciones anidadas
    }}
