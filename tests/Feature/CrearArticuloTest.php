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

        $response = $this->withHeaders(['Authorization' => 'Bearer token'])->postJson('api/articles', [
            'title' => 'mesa',
            'description'=> 'mesa de 3 patas',
            'image'=> 'imagen',
            'cicle_id'=> 1,
            'deleted'=> 0
        ]);
    
        // Primero comprobamos que todo ha ido bien
        $response->assertStatus(200);
        // Comprobamos los que hay en la base de datos sumando 1 para saber que se ha insertado)
        $this->assertCount($num, Articles::all());
        // Y comprobamos que sea el que acabamos de insertar
        $article = Articles::where('name', '=', 'mesa')->first();
        $this->assertEquals($article->name, 'mesa');
        $this->assertEquals($article->description, 'mesa de 3 patas');
        $this->assertEquals($article->image, 'imagen');
        $this->assertEquals($article->cicle_id, 1);
        $this->assertEquals($article->deleted, '0');
    }

    /** @test */
    public function error_al_crear() {
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

        $response = $this->postJson("api/login", [
            'email' => 'test@example.com',
            'password' => 'secret'
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer token'])->postJson('api/articles', [
            'name' => 'mesa',
            'description'=> 'mesa de 3 patas',
            'image'=> 'imagen',
            //quitamos el id del ciclo
            'deleted'=> 0
        ]);
    
        // Primero comprobamos que todo ha ido bien
        $response->assertStatus(200);
        // Comprobamos los que hay en la base de datos sumando 1 para saber que se ha insertado)
        $this->assertCount($num, Articles::all());
        // Y comprobamos que sea el que acabamos de insertar
        $article = Articles::where('name', '=', 'mesa')->first();
        $this->assertEquals($article->name, 'mesa');
        $this->assertEquals($article->description, 'mesa de 3 patas');
        $this->assertEquals($article->image, 'imagen');
        //quitamos el id del ciclo
        $this->assertEquals($article->deleted, '0');
    }


    
    
}
