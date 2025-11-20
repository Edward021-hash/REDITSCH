<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\http\Resources\PrendaResources;

class PrendaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [  // Estructuramos la respuesta de la receta como recurso API
             'id' => $this->id,
            'tipo' => 'receta',
            'atributos' => [
                'categoria' =>$this->categoria->nombre,  // Nombre de la categoria asociada a la receta
                'titulo' => $this->titulo,
                'descripcion' => $this->descripcion,
                'imagen' => $this->imagen,
            ],
        ];  
    }
}