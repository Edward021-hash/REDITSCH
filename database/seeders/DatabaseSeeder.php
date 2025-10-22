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

    // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Eduardo Crisostomo Garcia',
            'email' => 'Edward@example.com',
        ]);


        User::factory(29)->create();

        // Crear categorías, prendas y productos
        $categorias = Categoria::factory(10)->create();
        $prendas = Prenda::factory(100)->create();
        $productos = Producto::factory(40)->create();


    // Relación muchos a muchos
        $prendas = Prenda::all();
        $productos = Producto::all();






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
