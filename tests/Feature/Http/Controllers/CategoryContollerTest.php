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
        $this->withoutExceptionHandling();
        $section = Section::factory()->create();

        Category::factory()->create([
            'section_id' => $section->id,
            'name' => 'Balones',
        ]);
        
        Category::factory(2)->create();

        $response = $this->get('/api/categories');

        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json->has(3)
                    ->first(fn ($json) => 
                        $json->where('id', 1)
                            ->where('section_id', '1')
                            ->where('name', 'Balones')
                            ->etc()
                )
            );
    }

    /** @test */
    function can_show_only_one_category()
    {        
        Category::factory(2)->create();

        $section = Section::factory()->create();
        $category = Category::factory()->create([
            'section_id' => $section->id,
            'name' => 'Balones',
        ]);

        $response = $this->get("/api/categories/{$category->id}");

        $response
            ->assertJsonCount(1)
            ->assertJson([0 => [
                'section_id' => '3',
                'name' => 'Balones',
            ]]);
    }

    /** @test */
    function can_store_a_category()
    {
        $section = Section::factory()->create();

        $this->post('/api/categories', [
            'name' => 'Balones',
            'section_id' => $section->id,
        ])
        ->assertJson([
            'message' => 'Created',
        ]);
    }

    /** @test */
    function can_update_a_category()
    {
        $data = [
            'section_id' => Section::factory()->create(),
            'name' => 'Balones',
        ];

        $category = Category::factory()->create($data);

        $this->put("/api/categories/$category->id")
            ->assertJson(['message' => 'Updated']);

        $this->assertDatabaseMissing('categories', $data);
    }

    /** @test */
    function can_delete_a_category()
    {
        $data = [
            'section_id' => Section::factory()->create(),
            'name' => 'Balones',
        ];

        $category = Category::factory()->create($data);

        $this->delete("/api/categories/$category->id")
            ->assertJson(['message' => 'Deleted']);

        $this->assertDatabaseMissing('categories', $data);
    }
}
