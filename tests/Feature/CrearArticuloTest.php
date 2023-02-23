<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Articles;
use Laravel\Passport\ClientRepository;

class CrearArticuloTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function creado_correctamente() {
        $this->withoutExceptionHandling();

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

        $client->save();

        $response = $this->postJson("api/login", [
            'email' => 'test@example.com',
            'password' => 'secret'
        ]);

        $json = json_encode($response, true);

        $array = json_decode($json, true);

        $token = $array['baseResponse']['original']['success']['token'];

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])->postJson('api/articles', [
            'title' => 'mesa',
            'description'=> 'mesa de 3 patas',
            'image'=> 'imagen',
            'cicle_id'=> 1,
            'deleted'=> 0
        ]);
    
        // Primero comprobamos que todo ha ido bien
        $response->assertStatus(200);
        
        // Verifica que la respuesta incluya el artículo
        $response->assertJsonStructure([
            'Article' => [
                'title',
                'description',
                'image',
                'cicle_id',
                'updated_at',
                'created_at',
                'id'
            ]
        ]);
    }

    /** @test */
    public function error_al_crear() {
        $this->withoutExceptionHandling();

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

        $response = $this->postJson("api/login", [
            'email' => 'test@example.com',
            'password' => 'secret'
        ]);

        $json = json_encode($response, true);

        $array = json_decode($json, true);

        $token = $array['baseResponse']['original']['success']['token'];

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])->postJson('api/articles', [
            'title' => 'mesa',
            'description'=> 'mesa de 3 patas',
            'image'=> 'imagen',
            //quitamos el id del ciclo
            'deleted'=> 0
        ]);
    
        // Verifica que la respuesta tenga un estado de 401 (No autorizado)
        $response->assertStatus(401);

        // Verifica que la respuesta incluya el mensaje de error 'The cicle id field is required.'
        $response->assertJson([
            "error" => [
                "cicle_id" => [
                    "The cicle id field is required."
                ]
            ]
        ]);
    }


    
    
}
