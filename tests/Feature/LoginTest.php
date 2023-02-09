<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\User;
use Laravel\Passport\ClientRepository;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function can_login() {
        $this->withoutExceptionHandling();
        // Crea un usuario de prueba en la base de datos
        $user = factory(User::class)->create([
            'email' => 'test@example.com',
            'password' => bcrypt('secret')
        ]);

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            null, 'Test Personal Access Client', 'http://localhost'
        );

        \DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new \DateTime,
            'updated_at' => new \DateTime,
        ]);
        
        // Realiza una petición POST a la ruta de inicio de sesión
        $response = $this->postJson("api/login", [
            'email' => 'test@example.com',
            'password' => 'secret'
        ]);

        // Verifica que la respuesta tenga un estado de 200 (OK)
        $response->assertStatus(200);

        // Verifica que la respuesta incluya el token de acceso
        $response->assertJsonStructure([
            'success' => [
                'token'
            ]
        ]);
    }

    /** @test */
    public function cannot_login()
    {
        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            null, 'Test Personal Access Client', 'http://localhost'
        );

        \DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new \DateTime,
            'updated_at' => new \DateTime,
        ]);

        // Realiza una petición POST a la ruta de inicio de sesión
        $response = $this->postJson("api/login", [
            'email' => 'test@example.com',
            'password' => 'incorrect'
        ]);

        // Verifica que la respuesta tenga un estado de 401 (No autorizado)
        $response->assertStatus(401);

        // Verifica que la respuesta incluya el mensaje de error 'No estás autorizado'
        $response->assertJson([
            'error' => 'No estás autorizado'
        ]);
    }
}
