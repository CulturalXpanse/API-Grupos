<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PizzaTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */


    public function test_ListarPizzas()
    {
        $response = $this->get('/api/pizza');
        $response->assertStatus(200);
        $response->assertJsonStructure([
                '*' => [
                     'id',
                     'nombre',
                     'precio',
                     'deleted_at',
                     'created_at',
                     'updated_at'
                ]
        ]);

    }

    public function test_ObtenerUnaPizza()
    {
        $response = $this->get('/api/pizza/5000');
        $response->assertStatus(200);
        $response->assertJsonStructure([
                'id',
                'nombre',
                'precio',
                'deleted_at',
                'created_at',
                'updated_at'
        ]);

    }
    public function test_ObtenerUnaPizzaQueNoExiste()
    {
        $response = $this->get('/api/pizza/999999');
        $response->assertStatus(404);
        

    }

    public function test_CrearUnaPizza(){
        $parametros = [
            "nombre" => "Una pizza test",
            "precio" => 555,
        ];

        $response = $this->post('/api/pizza/',$parametros);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'nombre',
            'precio',
            'created_at',
            'updated_at'
        ]);


        $this->assertDatabaseHas('pizzas', [
            "nombre" => "Una pizza test",
            "precio" => 555,
        ]);
    }

    public function test_EliminarUnaPizza(){
        $response = $this->delete('/api/pizza/5001');
        $response->assertStatus(200);
        $response->assertJsonStructure(["mensaje"]);
        $this->assertDatabaseMissing('pizzas', [
            "id" => "5001",
            "deleted_at" => null,
        ]);
    }

    public function test_EliminarUnaPizzaQueNoExiste(){
        $response = $this->delete('/api/pizza/999999');
        $response->assertStatus(404);
       
    }
}
