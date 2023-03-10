<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Cicles;
use App\Articles;
use Laravel\Passport\ClientRepository;

class EliminarArticuloTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function borrado_correctamente() {

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

        $cicle = factory(Cicles::class)->create();
        $article = factory(Articles::class)->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])->deleteJson('api/articles/' . $article->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('articles', [
            'id' => $article->id
        ]);

    }

    /** @test */
    public function error_al_borrar() {

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

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])->deleteJson('api/articles/999');

        $response->assertStatus(500);

        $json = json_encode($response, true);

        $array = json_decode($json, true);

        $response->assertJson([
            "message" => "Call to a member function delete() on null",
        ]);

    }

}
