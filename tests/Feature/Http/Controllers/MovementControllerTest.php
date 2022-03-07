<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Movement;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MovementControllerTest extends TestCase
{
    use RefreshDatabase;

    const ADDICTION = 1;
    const SUSCTRACT = 0;

    /** @test */
    function can_show_all_movements()
    {
        $product = Product::factory()->create();

        Movement::factory()->create([
            'product_id' => $product->id,
            'amount' => 5,
            'type' => self::ADDICTION,
            'description' => 'Primer movimiento',
        ]);

        Movement::factory()->create([
            'product_id' => $product->id,
            'amount' => 3,
            'type' => self::SUSCTRACT,
            'description' => 'Segundo movimiento',
        ]);

        $this->json('GET', 'api/movements')
            ->assertJsonCount(2)
            ->assertStatus(200)
            ->assertJson([
                ['product_id' => $product->id, 'amount' => 5, 'type' => self::ADDICTION, 'description' => 'Primer movimiento'],
                ['product_id' => $product->id, 'amount' => 3, 'type' => self::SUSCTRACT, 'description' => 'Segundo movimiento'],
            ])
            ->assertJsonStructure(['*' => 
                ['id', 'product_id', 'amount', 'type', 'description']
            ]);
    }

    /** @test */
    function can_show_only_a_movement()
    {
        $product = Product::factory()->create();

        $movement = Movement::factory()->create([
            'product_id' => '1',
            'amount' => '5',
            'type' => self::ADDICTION,
            'description' => 'lorem',
        ]);

        Movement::factory(10)->create();

        $this->json('GET', "/api/movements/$movement->id")
            ->assertJsonCount(1)
            ->assertJson([0 => [
                'id' => 1,
                'product_id' => '1',
                'amount' => '5',
                'type' => self::ADDICTION,
                'description' => 'lorem',
            ]])->assertJsonStructure(['*' => 
                ['id', 'product_id', 'amount', 'type', 'description']
            ]);
    }

    /** @test */
    function can_store_a_movement()
    {
        $payload = [
            'product_id' => Product::factory()->create()->id,
            'amount' => 5,
            'type' => self::ADDICTION,
            'description' => 'Ingreso de producto',
        ];

        $this->json('POST', '/api/movements', $payload)
            ->assertStatus(201)
            ->assertJson([
                'id' => 1,
                'amount' => 5,
                'type' => self::ADDICTION,
                'description' => 'Ingreso de producto',
            ]);
    }
}
