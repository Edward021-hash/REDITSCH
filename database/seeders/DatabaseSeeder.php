<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Prenda;
use App\Models\Categoria;
use App\Models\Producto;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuarios
       User::firstOrCreate(
    ['email' => 'Edward@example.com'],
    [
        'name' => 'Test User',
        'password' => bcrypt('password'), // o cualquier contraseña
    ]
);


        User::factory(29)->create();

        // Crear categorías, prendas y productos
        $categorias = Categoria::factory(10)->create();
        $prendas = Prenda::factory(100)->create();
        $productos = Producto::factory(40)->create();

        // Relación muchos a muchos: Categoria <-> Producto
        foreach ($productos as $producto) {
            $producto->categorias()->attach(
                $categorias->random(rand(2, 4))->pluck('id')->toArray()
            );
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


