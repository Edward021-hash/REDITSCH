<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\http\Resources\ProductoResources;

class ProductoResource extends JsonResource
{
  /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [  // Estructuramos la respuesta de la etiqueta como recurso API

            'id' => $this->id,
            'tipo' => 'producto',
            'atributos' => [
                'categoria' => $this->categoria->nombre ?? null,
                'nombre' => $this->nombre,
              //  'titulo' => $this->titulo,
              //  'descripcion' => $this->descripcion,
              //  'precio' => $this->precio,
             //   'stock' => $this->stock,
        //    'id' => $this->id,
          //  'tipo' => 'etiqueta',
         //   'atributos' => [  // Estructuramos los atributos de la etiqueta
      //          'nombre' => $this->nombre
       //     ],
        //    'relaciones' => [  // Estructuramos las relaciones de la etiqueta
     //           'prenda' => $this->prenda
                // 'prenda' => PrendaResource::collection($this->prenda)  // Usamos el recurso prendaResource para formatear las prenda relacionadas
            ]
        ];

    }
}