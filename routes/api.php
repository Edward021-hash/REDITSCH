<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importar controladores
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\PrendaController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\LoginController;
use App\Models\Prenda;
use App\Models\Producto;

Route::post('login', [LoginController::class, 'store']);  // Ruta para el login

// Rutas protegidas por autenticación 
Route::middleware('auth:sanctum')->group(function () {  
    Route::apiResource('categorias', CategoriaController::class);  // Rutas de categorias
    Route::apiResource('prendas', PrendaController::class);// Rutas de recetas
    Route::apiResource('productos', ProductoController::class);  // Rutas de etiquetas
    Route::post('logout', [LoginController::class, 'destroy']); // Ruta para el logout

});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');