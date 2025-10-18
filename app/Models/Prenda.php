<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prenda extends Model
{
    /** @use HasFactory<\Database\Factories\PrendaFactory> */
    use HasFactory;

    protected $fillable = [ //Campos que se pueden asignar masivamente
        'categoria_id', 
        'user_id',
        'titulo',
        'descripcion',
        'imagen',
    ];

     // Una prenda puede tener muchos productos y un producto puede tener muchas prendas
    public function productos(){
        return $this->belongsToMany(Producto::class);
    }

    // Una prenda pertenece a una categoria  
    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }
   
    // Una prenda pertenece a un usuario
    public function user() {
        return $this->belongsTo(User::class);
    }

}
