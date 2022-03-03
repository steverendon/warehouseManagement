<?php

namespace Tests\Unit\models;

use Tests\TestCase;
use App\Models\Section;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    function it_belongs_to_section()
    {
        $section = Section::factory()->create();
        $category = Category::factory()->make([
            'section_id' => $section->id,
        ]);

        $this->assertInstanceOf(Section::class, $category->section);
    }

    /** @test */
    function it_has_many_products()
    {
        $category = new Category;

        $this->assertInstanceOf(Collection::class, $category->products);
    }

    /** @test */
    function categories_table_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('categories', [
                'id', 'name', 'section_id',
            ])
        );
    }
}
