<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
uses(WithFaker::class);

test('index', function () {

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);

    Sanctum::actingAs(User::factory()->create()->assignRole('Usuario'));

    Categoria::factory()->create();
    Producto::factory(3)->create();

    $response = $this->getJson('/api/productos');

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'tipo',
                    'atributos' => [
                        'categoria',
                        'nombre',
                     //   'descripcion',
                     //   'precio',
                     //   'stock'
                    ]
                ]
            ]
        ]);
});

test('show', function () {

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);
    Sanctum::actingAs(User::factory()->create()->assignRole('Usuario'));

    $categoria = Categoria::factory()->create();
    $producto = Producto::factory()->create(['categoria_id' => $categoria->id]);

    $response = $this->getJson("/api/productos/{$producto->id}");

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'data' => [
                'id',
                'tipo',
                'atributos' => [
                    'categoria',
                    'nombre',
                 //   'descripcion',
                 //   'precio',
               //     'stock'
                ]
            ]
        ]);
});

test('store', function () {

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);
    Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

    $categoria = Categoria::factory()->create();

    $data = [
        'categoria_id' => $categoria->id,
        'nombre' => 'Producto Test',
     //   'descripcion' => 'Descripcion test',
      //  'precio' => 99.50,
       // 'stock' => 10,
        'imagen' => UploadedFile::fake()->image('producto.png'),
    ];

    $response = $this->postJson('/api/productos', $data);

    $response->assertStatus(Response::HTTP_CREATED);

});

test('update', function () {

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);
    Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

    $categoria = Categoria::factory()->create();
    $producto = Producto::factory()->create();

    $data = [
        'categoria_id' => $categoria->id,
        'nombre' => 'Nombre actualizado',
      //  'descripcion' => 'Descripcion actualizada',
        //'precio' => 150.00,
       // 'stock' => 7
    ];

    $response = $this->putJson("/api/productos/{$producto->id}", $data);

    $response->assertStatus(Response::HTTP_OK);


});

test('destroy', function () {

    $this->artisan('db:seed', ['--class' => 'RolSeeder']);
    Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

    $producto = Producto::factory()->create();

    $response = $this->deleteJson("/api/productos/{$producto->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);

    $this->assertDatabaseMissing('productos', [
        'id' => $producto->id
    ]);
});
