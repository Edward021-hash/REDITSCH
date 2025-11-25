<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('user can login with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $data = [
        'correo' => 'test@example.com',
        'contraseña' => 'password123',
        'dispositivo' => 'Test Device',
    ];

    $response = $this->postJson('/api/login', $data);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'data' => [
                'attributes' => [
                    'id',
                    'nombre',
                    'correo',
                ],
                'token',
            ]
        ]);
});

test('user cannot login with invalid password', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $data = [
        'correo' => 'test@example.com',
        'contraseña' => 'wrongpassword',
        'dispositivo' => 'Test Device',
    ];

    $response = $this->postJson('/api/login', $data);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            'message' => 'Las credenciales son incorrectas.',
        ]);
});

test('user cannot login with non-existent email', function () {
    $data = [
        'correo' => 'nonexistent@example.com',
        'contraseña' => 'password123',
        'dispositivo' => 'Test Device',
    ];

    $response = $this->postJson('/api/login', $data);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            'message' => 'Las credenciales son incorrectas.',
        ]);
});

test('login requires all fields', function () {
    $response = $this->postJson('/api/login', []);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['correo', 'contraseña', 'dispositivo']);
});

test('user can logout', function () {
    $user = User::factory()->create();
    
    Sanctum::actingAs($user);

    // CAMBIO: Usar POST en lugar de DELETE
    $response = $this->postJson('/api/logout');

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'message' => 'Cierre de sesión exitoso.',
        ]);

    // Verificar que el token fue eliminado
    $this->assertCount(0, $user->tokens);
});

test('logout requires authentication', function () {
    // CAMBIO: Usar POST en lugar de DELETE
    $response = $this->postJson('/api/logout');
    
    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
});