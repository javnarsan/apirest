<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\User;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\DB;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifica que el registro de un nuevo usuario sea exitoso.
     *
     * @return void
     */
    public function test_can_register()
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
        
        $response = $this->postJson('api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'c_password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success' => [
                         'token',
                         'name',
                     ],
                 ]);
    }

    /**
     * Verifica que se devuelva un error si los datos de registro son inválidos.
     *
     * @return void
     */
    public function test_cannot_register()
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
        
        $response = $this->postJson('api/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '',
            'c_password' => '',
        ]);

        $response->assertStatus(401)
                 ->assertJsonStructure([
                     'error' => [
                         'email',
                         'password',
                         'c_password',
                     ],
                 ]);
    }
}
