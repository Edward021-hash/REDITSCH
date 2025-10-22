<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory;

    // Relación 1:N (Un producto tiene muchas prendas)
    public function prendas(){
        return $this->belongsToMany(Prenda::class);
    }

    // relación con categorias
    public function categorias(){
        return $this->belongsTo(Categoria::class);
    }
}
