<?php

namespace App\Policies;

use App\Models\User;  // Importar el modelo User
use App\Models\Prenda;  // Importar el modelo Receta

class PrendaPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    // Determinar si el usuario puede actualizar la prenda
    public function update(User $user, Prenda $prenda)
    {
        return $user->id === $prenda->user_id;
    }

    // Determinar si el usuario puede eliminar la prenda
    public function delete(User $user, Prenda $prenda)
    {
        return $user->id === $prenda->user_id;
    }

}