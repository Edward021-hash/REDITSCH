<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory;

    // Relación 1:N (Una etiqueta tiene muchas prendas)
    public function categorias(){
        return $this->belongsToMany(Categoria::class);
    }
}
