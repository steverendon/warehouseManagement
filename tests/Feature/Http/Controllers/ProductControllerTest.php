<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_show_all_products()
    {
        $category = Category::factory()->create();

        Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Balon',
            'description' => 'Balon de futbol profesional numero 5',
            'code' => '123qwerty',
            'price' => 190000,
            'due_date' => '',
            'batch' => '12345678',
        ]);

        Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Camiseta',
            'description' => 'Camiseta equipo de futbol profesional',
            'code' => '342asdfg',
            'price' => 120000,
            'due_date' => '',
            'batch' => '231233234',
        ]);

        $this->json('GET', '/api/products')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    ['category_id', 'name', 'description', 'code', 'price', 'due_date', 'batch']
                ]
            ])
            ->assertJson([
                'data' => [
                    [
                        'category_id' => $category->id,
                        'name' => 'Balon',
                        'description' => 'Balon de futbol profesional numero 5',
                        'code' => '123qwerty',
                        'price' => 190000,
                        'due_date' => '',
                        'batch' => '12345678',
                    ],
                    [
                        'category_id' => $category->id,
                        'name' => 'Camiseta',
                        'description' => 'Camiseta equipo de futbol profesional',
                        'code' => '342asdfg',
                        'price' => 120000,
                        'due_date' => '',
                        'batch' => '231233234',
                    ],
                ]
            ]);
    }

    /** @test */
    function can_show_only_a_product()
    {
        $category = Category::factory()->create();

        Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Balon',
            'description' => 'Balon de futbol profesional numero 5',
            'code' => '123qwerty',
            'price' => 190000,
            'due_date' => '',
            'batch' => '12345678',
        ]);

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Camiseta',
            'description' => 'Camiseta equipo de futbol profesional',
            'code' => '342asdfg',
            'price' => 120000,
            'due_date' => '',
            'batch' => '231233234',
        ]);

        $this->json('GET', "/api/products/$product->id")
            ->assertJsonStructure([
                'data' => ['category_id', 'name', 'description', 'code', 'price', 'due_date', 'batch']
            ])
            ->assertJson([
                'data' => [
                    'category_id' => $category->id,
                    'name' => 'Camiseta',
                    'description' => 'Camiseta equipo de futbol profesional',
                    'code' => '342asdfg',
                    'price' => 120000,
                    'due_date' => '',
                    'batch' => '231233234',
                    ]
            ])
            ->assertJsonMissing([
                'data' => [
                    'category_id' => $category->id,
                    'name' => 'Balon',
                    'description' => 'Balon de futbol profesional numero 5',
                    'code' => '123qwerty',
                    'price' => 190000,
                    'due_date' => '',
                    'batch' => '12345678',
                ]
            ]);
    }

    /** @test */
    function can_store_a_product()
    {
        $category = Category::factory()->create();

        $reponse = $this->json('POST', 'api/products', [
            'category_id' => $category->id,
            'name' => 'Balon Golty Profesional',
            'description' => 'Balon numero 5 profesional, grama sintetica y natural',
            'code' => '1234asdf',
            'price' => 180000,
            'due_date' => '2022-08-12',
            'batch' => '122312234',
        ]);

        $reponse
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['category_id', 'name', 'description', 'code', 'price', 'due_date', 'batch']
            ])
            ->assertJson([
                'data' => [
                    'category_id' => $category->id,
                    'name' => 'Balon Golty Profesional',
                    'description' => 'Balon numero 5 profesional, grama sintetica y natural',
                    'code' => '1234asdf',
                    'price' => 180000,
                    'due_date' => '2022-08-12',
                    'batch' => '122312234',
                ],
            ]);

        $this->assertDatabaseHas('products', [
            'category_id' => $category->id,
            'name' => 'Balon Golty Profesional',
            'description' => 'Balon numero 5 profesional, grama sintetica y natural',
            'code' => '1234asdf',
            'price' => 180000,
            'due_date' => '2022-08-12',
            'batch' => '122312234',
        ]);
    }

    /** @test */
    function can_update_a_product()
    {
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Balon Golty Profesional',
            'description' => 'Balon numero 5 profesional, grama sintetica y natural',
            'code' => '1234asdf',
            'price' => 180000,
            'due_date' => '2022-08-12',
            'batch' => '122312234',
        ]);

        $response = $this->json('PUT', "/api/products/$product->id", [
            'category_id' => $category->id,
            'name' => 'Camiseta Real Madrid',
            'description' => 'Camiseta Original Equipo Real Madrid',
            'code' => '2312234',
            'price' => 260000,
            'due_date' => '2022-08-15',
            'batch' => '3454364563',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'category_id' => $category->id,
                    'name' => 'Camiseta Real Madrid',
                    'description' => 'Camiseta Original Equipo Real Madrid',
                    'code' => '2312234',
                    'price' => 260000,
                    'due_date' => '2022-08-15',
                    'batch' => '3454364563',
                ]
            ]);

        $this->assertDatabaseHas('products', [
            'category_id' => $category->id,
            'name' => 'Camiseta Real Madrid',
            'description' => 'Camiseta Original Equipo Real Madrid',
            'code' => '2312234',
            'price' => 260000,
            'due_date' => '2022-08-15',
            'batch' => '3454364563',
        ]);

        $this->assertDatabaseMissing('products', [
            'category_id' => $category->id,
            'name' => 'Balon Golty Profesional',
            'description' => 'Balon numero 5 profesional, grama sintetica y natural',
            'code' => '1234asdf',
            'price' => 180000,
            'due_date' => '2022-08-12',
            'batch' => '122312234',
        ]);
    }

    /** @test */
    function can_delete_a_product()
    {
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Balon Golty Profesional',
            'description' => 'Balon numero 5 profesional, grama sintetica y natural',
            'code' => '1234asdf',
            'price' => 180000,
            'due_date' => '2022-08-12',
            'batch' => '122312234',
        ]);

        $response = $this->delete("/api/products/$product->id");

        $response
            ->assertStatus(204);

        $this->assertDatabaseMissing('products', [
            'category_id' => $category->id,
            'name' => 'Balon Golty Profesional',
            'description' => 'Balon numero 5 profesional, grama sintetica y natural',
            'code' => '1234asdf',
            'price' => 180000,
            'due_date' => '2022-08-12',
            'batch' => '122312234',
        ]);
    }
}
