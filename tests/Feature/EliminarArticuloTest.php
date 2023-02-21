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

        //Número de registros en la base de datos sumando 1 que vamos a crear
        $num = Articles::all()->count()+1;

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

        $cicle = factory(Cicles::class)->create();
        $article = factory(Articles::class)->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer token'])->deleteJson('api/articles/' . $article->id);

        $this->assertCount($num, Articles::all());

        $response->assertStatus(200);

    }

    /** @test */
    public function error_al_borrar() {

        //Número de registros en la base de datos sumando 1 que vamos a crear
        $num = Articles::all()->count()+1;

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

        $cicle = factory(Cicles::class)->create();
        $article = factory(Articles::class)->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer token'])->deleteJson('api/articles/' . $article->description);

        $this->assertCount($num, Articles::all());

        $response->assertStatus(200);

    }

}
