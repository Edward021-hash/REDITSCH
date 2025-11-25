<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use App\Models\Prenda;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Producto;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
uses(WithFaker::class);

test('index', function () {
    // Ejecuta el seeder de roles
    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    Sanctum::actingAs(User::factory()->create()->assignRole('Usuario'));  // Simula un usuario autenticado con el rol de Usuario

    Categoria::factory()->create();  // Crear una categoría para las recetas
    Prenda::factory(3)->create();  // Crear 3 recetas

    $response = $this->getJson('/api/prendas');  // Realiza una solicitud GET a la ruta /api/recetas
    //dd($response->json());  // Mostrar la respuesta JSON para depuración

    $response->assertStatus(Response::HTTP_OK)  // Verificar que el estado de la respuesta sea 200 OK
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'tipo',
                    'atributos' => [
                       'categoria',
                       'titulo',
                       'descripcion',
                    ]
                ]
            ]
        ]);
});


test('show', function () {  // Muestra una receta específica
    // Ejecuta el seeder de roles
    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    Sanctum::actingAs(User::factory()->create()->assignRole('Usuario'));  // Simula un usuario autenticado con el rol de Usuario

    $categoria = Categoria::factory()->create();  // Crear una categoría para la receta
    $prenda = Prenda::factory()->create();  // Crear una receta

    $response = $this->getJson("/api/prendas/{$prenda->id}");  // Realiza una solicitud GET a la ruta /api/recetas/{id}
    //dd($response->json());

    $response->assertStatus(Response::HTTP_OK)  // Verificar que el estado de la respuesta sea 200 OK
        ->assertJsonStructure([
            'data' => [
                 'id',
                    'tipo',
                    'atributos' => [
                       'categoria',
                       'titulo',
                       'descripcion',
                ]
            ]
        ]);
});

test('store', function () {  // Crea una nueva receta
    // Ejecuta el seeder de roles
    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    $usuario = Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));  // Simula un usuario autenticado con el rol de Administrador

    $categoria = Categoria::factory()->create();  // Crear una categoría para la receta
    $producto = Producto::factory()->create();  // Crear una etiqueta para la receta

    $data = [  // Datos de la nueva receta
        'categoria_id' => $categoria->id,
        'titulo' => $this->faker->sentence,        
        'descripcion' => $this->faker->sentence,
        //'ingredientes' => $this->faker->sentence,
        //'instrucciones' => $this->faker->sentence,
        'imagen' => UploadedFile::fake()->image('receta.png'),
       // 'etiquetas' => $etiqueta->id,
    ];

    $response = $this->postJson('/api/prendas/', $data);  // Realiza una solicitud POST a la ruta /api/recetas
    //dd($response->json());

    $response->assertStatus(Response::HTTP_CREATED);  // Verificar que el estado de la respuesta sea 201 Created

    // Verificar que se haya creado el registro
    $this->assertDatabaseHas('prendas', 
                            ['titulo' => $response['atributos']['titulo']]);
});

test('update', function () {
    // Ejecuta el seeder de roles
    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    $usuario = Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));  // Simula un usuario autenticado con el rol de Administrador

    $categoria = Categoria::factory()->create();  // Crear una categoría para la receta
    $prenda = Prenda::factory()->create();  // Crear una receta

    $data = [   // Datos actualizados de la receta
        'categoria_id' => $categoria->id,
        'titulo' => 'titulo actualizado',      
        'descripcion' => 'descripcion actualizada',
       // 'ingredientes' => $this->faker->sentence,
       // 'instrucciones' => $this->faker->sentence,
    ];

    $response = $this->putJson("/api/prendas/{$prenda->id}", $data);  // Realiza una solicitud PUT a la ruta /api/recetas/{id}
    //dd($response->json());

    $response->assertStatus(Response::HTTP_OK);  // Verificar que el estado de la respuesta sea 200 OK

    // Verificar que se haya actualizado el registro
    $this->assertDatabaseHas('prendas', [
        'titulo' => 'titulo actualizado',
        'descripcion' => 'descripcion actualizada',
    ]);
});


test('destroy', function () {  // Elimina una receta
    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));  // Simula un usuario autenticado con el rol de Administrador

    Categoria::factory()->create();  // Crear una categoría para la receta

    $prenda = Prenda::factory()->create();  // Crear una receta

    $response = $this->deleteJson("/api/prendas/{$prenda->id}");  // Realiza una solicitud DELETE a la ruta /api/recetas/{id}

    $response->assertStatus(Response::HTTP_NO_CONTENT);  // Verificar que el estado de la respuesta sea 204 No Content

    // Verificar que se haya eliminado el registro
    $this->assertDatabaseMissing('prendas', ['id' => $prenda->id]);
});

//test('destroy_administrador', function () {  // Intenta eliminar una receta con un usuario con rol Editor
  //  $this->artisan('db:seed', ['--class' => 'RolSeeder']);  // Ejecuta el seeder de roles

    //Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));  // Simula un usuario autenticado con el rol de Editor

    //Categoria::factory()->create();  // Crear una categoría para la receta

//    $prenda = Prenda::factory()->create();  // Crear una receta

  //  $response = $this->deleteJson("/api/prendas/{$prenda->id}");  // Realiza una solicitud DELETE a la ruta /api/recetas/{id}

  //  $response->assertStatus(Response::HTTP_FORBIDDEN);  // Verificar que el estado de la respuesta sea 403 Forbidden
//});

