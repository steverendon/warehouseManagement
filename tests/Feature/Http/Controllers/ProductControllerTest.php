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
        Product::factory(3)->create();

        $this->get('/api/products')
            ->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    function can_show_only_a_product()
    {
        $data = [
            'category_id' => Category::factory()->create()->id,
            'name' => 'Balon Golty Profesional',
            'description' => 'Balon numero 5 profesional, grama sintetica y natural',
            'code' => '1234asdf',
            'price' => 180000,
            'due_date' => '2022-08-12',
            'batch' => '122312234',
        ];

        $product = Product::factory()->create($data);

        Product::factory(5)->create();

        $this->get("/api/products/$product->id")
            ->assertJsonCount(1)
            ->assertJson([0 => $data]);
    }

    /** @test */
    function can_store_a_product()
    {
        $data = [
            'category_id' => Category::factory()->create()->id,
            'name' => 'Balon Golty Profesional',
            'description' => 'Balon numero 5 profesional, grama sintetica y natural',
            'code' => '1234asdf',
            'price' => 180000,
            'due_date' => '2022-08-12',
            'batch' => '122312234',
        ];

        $this->post('api/products', $data)
            ->assertJson([
                'message' => 'Created',
            ]);

        $this->assertDatabaseHas('products', $data);
    }

    /** @test */
    function can_update_a_product()
    {
        $data = [
            'category_id' => Category::factory()->create()->id,
            'name' => 'Balon Golty Profesional',
            'description' => 'Balon numero 5 profesional, grama sintetica y natural',
            'code' => '1234asdf',
            'price' => 180000,
            'due_date' => '2022-08-12',
            'batch' => '122312234',
        ];

        $product = Product::factory()->create();

        $this->put("/api/products/$product->id", $data);

        $this->assertDatabaseHas('products', ['id' => $product->id] + $data);
    }

    /** @test */
    function can_delete_a_product()
    {
        $this->withoutExceptionHandling();
        $data = [
            'category_id' => Category::factory()->create()->id,
            'name' => 'Balon Golty Profesional',
            'description' => 'Balon numero 5 profesional, grama sintetica y natural',
            'code' => '1234asdf',
            'price' => 180000,
            'due_date' => '2022-08-12',
            'batch' => '122312234',
        ];

        $product = Product::factory()->create($data);

        $this->delete("/api/products/$product->id")
            ->assertJson(['message' => 'Deleted']);

        $this->assertDatabaseMissing('products', $data);
    }
}
