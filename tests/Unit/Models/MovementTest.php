<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Movement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class MovementTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    function it_belongs_to_product()
    {
        $product = Product::factory()->create();

        $movement = Movement::factory()->make([
            'product_id' => $product->id,
        ]);

        $this->assertInstanceOf(Product::class, $movement->product);
    }

    /** @test */
    function movements_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumns('movements', [
            'id',
            'product_id',
            'amount',
            'type',
            'description' 
        ]));
    }
}
