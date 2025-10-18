<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaFactory> */
    use HasFactory;

     // Relación 1:N (Una categoría tiene muchos productos)
    public function productos(){
        return $this->hasMany(Producto::class);
    }
}
