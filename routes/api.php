<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importar controladores
use App\Http\Controllers\Api\CategoriaController;

// Rutas de categorias 
Route::apiResource('categorias', CategoriaController::class);





// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');