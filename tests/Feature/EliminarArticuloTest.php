<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use almagest\Article;

class EliminarArticuloTest extends TestCase
{

    /** @test */
    public function borrado_correctamente() {

        //Número de registros en la base de datos sumando 1 que vamos a crear
        $num = Article::all()->count()+1;

        $this->withoutExceptionHandling();

        $article = factory(Article::class)->create();

        $response = $this->delete('/articulo/' . $article->id . '/borrar');

        $this->assertCount($num, Article::all());

        $response->assertStatus(200);

    }

    /** @test */
    public function error_al_borrar() {

        //Número de registros en la base de datos sumando 1 que vamos a crear
        $num = Article::all()->count()+1;

        $this->withoutExceptionHandling();

        $article = factory(Article::class)->create();

        $response = $this->delete('/articulo/' . $article->name . '/borrar');

        $this->assertCount($num, Article::all());

        $response->assertStatus(200);

    }

}
