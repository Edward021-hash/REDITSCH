<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importar controladores
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\PrendaController;
use App\Http\Controllers\Api\ProductoController;
use App\Models\Prenda;
use App\Models\Producto;

// Rutas de categorias 
Route::apiResource('categoria', CategoriaController::class);

// Rutas de prendas
Route::apiResource('prenda', PrendaController::class);

// Rutas de productos
Route::apiResource('producto', ProductoController::class);





// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');