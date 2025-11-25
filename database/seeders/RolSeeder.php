<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;  // Importar el modelo Role
use Spatie\Permission\Models\Permission;  // Importar el modelo Permission
use App\Models\User;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        Administrador -> CRUD
        Editor -> CRU
        Usuario -> R
        */


        $administrador = Role::create(['name' => 'Administrador']);
        $usuario = Role::create(['name' => 'Usuario']);

        Permission::create(['name' => 'Crear prendas'])->syncRoles([$administrador]);
        Permission::create(['name' => 'Editar prendas'])->syncRoles([$administrador]);

        Permission::create(['name' => 'Eliminar prendas'])->syncRoles([$administrador]);
        Permission::create(['name' => 'Ver prendas'])->syncRoles([$administrador, $usuario]);
   
Permission::create(['name' => 'Ver productos'])->syncRoles([$administrador, $usuario]);
Permission::create(['name' => 'Crear productos'])->syncRoles([$administrador]);
Permission::create(['name' => 'Editar productos'])->syncRoles([$administrador]);
Permission::create(['name' => 'Eliminar productos'])->syncRoles([$administrador]);

// Agrega estas líneas después de los permisos de productos
Permission::create(['name' => 'Ver categorias'])->syncRoles([$administrador, $usuario]);
Permission::create(['name' => 'Crear categorias'])->syncRoles([$administrador]);
Permission::create(['name' => 'Editar categorias'])->syncRoles([$administrador]);
Permission::create(['name' => 'Eliminar categorias'])->syncRoles([$administrador]);

        
    }
}