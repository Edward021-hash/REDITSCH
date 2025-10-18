<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\http\Resources\CategoriaResources;

class CategoriaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,  // ID de la categoria
            'tipo' => 'categoria', 
            'atributos' => [  // Estructuramos los atributos de la categoria
                'nombre' => $this->nombre
            ],
            'relaciones' => [  // Estructuramos las relaciones de la categoria
                'prenda' => $this->prenda
                // 'prenda' => PrendaResource::collection($this->prenda)  // Usamos el recurso prendaResource para formatear las prendas relacionadas
            ],
        ];
    }
}
