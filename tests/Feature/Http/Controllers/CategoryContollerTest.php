<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Section;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryContollerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_show_all_categories()
    {
        $section = Section::factory()->create();

        Category::factory()->create([
            'section_id' => $section->id,
            'name' => 'Balones',
        ]);

        Category::factory()->create([
            'section_id' => $section->id,
            'name' => 'Camisetas',
        ]);

        Category::factory()->create([
            'section_id' => $section->id,
            'name' => 'Guayos',
        ]);

        $response = $this->json('GET', '/api/categories');

        $response
            ->assertJson([
                'data' => [
                    ['id' => 1, 'section_id' => $section->id, 'name' => 'Balones'],
                    ['id' => 2, 'section_id' => $section->id, 'name' => 'Camisetas'],
                    ['id' => 3, 'section_id' => $section->id, 'name' => 'Guayos'],
                ]
            ])
            ->assertJsonStructure([
                'data' => [
                    ['id', 'section_id', 'name'] 
                ]
            ]);
    }

    /** @test */
    function can_show_only_one_category()
    {        
        $section = Section::factory()->create();

        Category::factory()->create([
            'section_id' => $section->id,
            'name' => 'Camisetas',
        ]);

        $category = Category::factory()->create([
            'section_id' => $section->id,
            'name' => 'Balones',
        ]);

        Category::factory()->create([
            'section_id' => $section->id,
            'name' => 'Guayos',
        ]);

        $response = $this->json('GET', "/api/categories/{$category->id}");

        $response
            ->assertJson([
                'data' => ['id' => 2, 'section_id' => $section->id, 'name' => 'Balones']
            ])
            ->assertJsonMissing([
                'data' => ['id' => 1, 'section_id' => $section->id, 'name' => 'Camisetas']
            ])
            ->assertJsonMissing([
                'data' => ['id' => 3, 'section_id' => $section->id, 'name' => 'Guayos']
            ])
            ->assertJsonStructure([
                'data' => ['id', 'section_id', 'name']
            ]);
    }

    /** @test */
    function can_store_a_category()
    {
        $section = Section::factory()->create();

        $this->json('POST', '/api/categories', [
            'name' => 'Balones',
            'section_id' => $section->id,
        ])
        ->assertJson([
            'data' => [
                'id' => 1,
                'section_id' => $section->id,
                'name' => 'Balones'
            ]
        ])
        ->assertJsonStructure([
            'data' => ['id', 'section_id', 'name']
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => 1,
            'section_id' => $section->id,
            'name' => 'Balones',
        ]);
    }

    /** @test */
    function can_update_a_category()
    {
        $section = Section::factory()->create();

        $category = Category::factory()->create([
            'section_id' => $section->id,
            'name' => 'Balones',
        ]);

        $response = $this->json('PUT', "/api/categories/$category->id", [
            'name' => 'Camisetas',
        ]);

        $response
            ->assertJson([
                'data' => [
                    'id' => $category->id,
                    'section_id' => $section->id,
                    'name' => 'Camisetas',
                ]
            ]);

        $this->assertDatabaseMissing('categories', [
            'section_id' => $section->id,
            'name' => 'Balones',
        ]);

        $this->assertDatabaseHas('categories', [
            'section_id' => $section->id,
            'name' => 'Camisetas',
        ]);
    }

    /** @test */
    function can_delete_a_category()
    {
        $section = Section::factory()->create();

        $category = Category::factory()->create([
            'section_id' => $section->id,
            'name' => 'Balones',
        ]);

        $response = $this->delete("/api/categories/$category->id");

        $response
            ->assertStatus(204);

        $this->assertDatabaseMissing('categories', [
            'section_id' => $section->id,
            'name' => 'Balones',
        ]);
    }
}
