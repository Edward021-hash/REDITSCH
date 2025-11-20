<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Factory; // importar la clase Factory
use Illuminate\Support\Facades\Hash; // Para hashear contraseñas
use Illuminate\Support\Str; // Para generar cadenas aleatorias

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Prenda;
use App\Models\Categoria;
use App\Models\Producto;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

     $this->call(RolSeeder::class);  // Llamar al seeder de roles   

        User::factory()->create([
            'name' => 'Eduardo Crisostomo Garcia',
            'email' => 'Edward@example.com',
        ])-> assignRole('Administrador');  // Asignar rol de Administrador al usuario creado

        User::factory()->create([
            'name' => 'Claudie Paucek',
            'email' => 'dalton76@example.com',
        ])->assignRole('Usuario');  // Asignar rol de usuario al usuario Iraic


        User::factory(29)->create()->each(function ($user) {
            $user->assignRole('Usuario');
        });  // Crear 29 usuarios y les asigna el rol de Usuario


        // Crear categorías, prendas y productos
        $categorias = Categoria::factory(10)->create();
        $prendas = Prenda::factory(100)->create();
        $productos = Producto::factory(40)->create();


    // Relación muchos a muchos
        $prendas = Prenda::all();
        $productos = Producto::all();

 // Asignar entre 2 y 4 etiquetas aleatorias a cada receta
        // attach() agrega registros a la tabla intermedia sin eliminar los existentes 
        foreach ($prendas as $prenda) {
            $prenda->productos()->attach($productos->random(rand(2, 4)));
        }


        // Relación muchos a muchos: Categoria <-> Producto
      foreach ($productos as $producto) {
    // Asigna una sola categoría aleatoria
    $producto->categoria_id = $categorias->random()->id;
    $producto->save();
}

        // Asignar prendas a productos (uno a muchos)
        foreach ($productos as $producto) {
            // Aquí usamos la colección $prendas correctamente
            $producto->update([
                'prenda_id' => $prendas->random()->id,
            ]);
        }
    }
}
