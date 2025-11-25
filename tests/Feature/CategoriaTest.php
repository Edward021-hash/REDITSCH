<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use App\Models\Categoria;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
uses(WithFaker::class);

test('index', function () {
    $this->artisan('db:seed', ['--class' => 'RolSeeder']);
    
    Sanctum::actingAs(User::factory()->create()->assignRole('Usuario'));

    Categoria::factory(3)->create();

    $response = $this->getJson('/api/categorias');

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(3, 'data');
});

test('show', function () {
    $this->artisan('db:seed', ['--class' => 'RolSeeder']);
    
    Sanctum::actingAs(User::factory()->create()->assignRole('Usuario'));

    $categoria = Categoria::factory()->create();

    $response = $this->getJson("/api/categorias/{$categoria->id}");

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'data' => [
                'id',
                'tipo',
                'atributos' => [
                    'nombre',
                 //   'descripcion',
                ]
            ]
        ]);
});

test('store', function () {
    $this->artisan('db:seed', ['--class' => 'RolSeeder']);
    
    Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

    $data = [
        'nombre' => 'Categoria Test',
   //     'descripcion' => 'Descripcion de prueba',
    ];

    $response = $this->postJson('/api/categorias/', $data);

    $response->assertStatus(Response::HTTP_CREATED);

    $this->assertDatabaseHas('categorias', [
        'nombre' => 'Categoria Test'
    ]);
});

test('update', function () {
    $this->artisan('db:seed', ['--class' => 'RolSeeder']);
    
    Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

    $categoria = Categoria::factory()->create();

    $data = [
        'nombre' => 'Nombre actualizado',
    //    'descripcion' => 'Descripcion actualizada',
    ];

    $response = $this->putJson("/api/categorias/{$categoria->id}", $data);

    $response->assertStatus(Response::HTTP_OK);

    $this->assertDatabaseHas('categorias', [
        'id' => $categoria->id,
        'nombre' => 'Nombre actualizado'
    ]);
});

test('destroy', function () {
    $this->artisan('db:seed', ['--class' => 'RolSeeder']);
    
    Sanctum::actingAs(User::factory()->create()->assignRole('Administrador'));

    $categoria = Categoria::factory()->create();

    $response = $this->deleteJson("/api/categorias/{$categoria->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);

    $this->assertDatabaseMissing('categorias', ['id' => $categoria->id]);
});