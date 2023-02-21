<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use almagest\Article;

class CrearArticuloTest extends TestCase
{

    /** @test */
    public function creado_correctamente() {
        //Número de registros en la base de datos sumando 1 que vamos a crear
        $num = Article::all()->count()+1;
        
        $this->withoutExceptionHandling();

        $response = $this->post('/addArticulo', [
            'title' => 'mesa',
            'description'=> 'mesa de 3 patas',
            'image'=> 'imagen',
            'cicle_id'=> 1,
            'deleted'=> 0
        ]);
    
        // Primero comprobamos que todo ha ido bien
        $response->assertStatus(200);
        // Comprobamos los que hay en la base de datos sumando 1 para saber que se ha insertado)
        $this->assertCount($num, Article::all());
        // Y comprobamos que sea el que acabamos de insertar
        $article = Article::where('name', '=', 'mesa')->first();
        $this->assertEquals($article->name, 'mesa');
        $this->assertEquals($article->description, 'mesa de 3 patas');
        $this->assertEquals($article->image, 'imagen');
        $this->assertEquals($article->cicle_id, 1);
        $this->assertEquals($article->deleted, '0');
    }

    /** @test */
    public function error_al_crear() {
        //Número de registros en la base de datos sumando 1 que vamos a crear
        $num = Article::all()->count()+1;
        
        $this->withoutExceptionHandling();

        $response = $this->post('/addArticulo', [
            'name' => 'mesa',
            'description'=> 'mesa de 3 patas',
            'image'=> 'imagen',
            //quitamos el id del ciclo
            'deleted'=> 0
        ]);
    
        // Primero comprobamos que todo ha ido bien
        $response->assertStatus(200);
        // Comprobamos los que hay en la base de datos sumando 1 para saber que se ha insertado)
        $this->assertCount($num, Article::all());
        // Y comprobamos que sea el que acabamos de insertar
        $article = Article::where('name', '=', 'mesa')->first();
        $this->assertEquals($article->name, 'mesa');
        $this->assertEquals($article->description, 'mesa de 3 patas');
        $this->assertEquals($article->image, 'imagen');
        //quitamos el id del ciclo
        $this->assertEquals($article->deleted, '0');
    }


    
    
}
