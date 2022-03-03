<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_belongs_to_a_category()
    {
        $category = Category::factory()->create();

        $product = Product::factory()->make([
            'category_id' => $category->id,
        ]);

        $this->assertInstanceOf(Category::class, $product->category);
    }

    /** @test */
    function it_has_many_movements()
    {
        $product = new Product;

        $this->assertInstanceOf(Collection::class, $product->movements);
    }

    /** @test */
    function products_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumns('products', [
            'id',
            'category_id',
            'name',
            'description',
            'code',
            'price',
            'due_date',
            'batch',
        ]));
    }
}
