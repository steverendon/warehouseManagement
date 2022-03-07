<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Section;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SectionControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_can_store_a_section()
    {
        $this->post('/api/sections', [
            'name' => 'Comestibles',
        ])
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Created',
        ]);

        $this->assertDatabaseHas('sections', [
            'name' => 'Comestibles',
        ]);
    }

    /** @test */
    function it_can_lists_all_registers()
    {
        Section::factory()->create(['name' => 'Comestibles']);
        Section::factory(2)->create();

        $response = $this->get('/api/sections');

        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json->has(3)
                    ->first(fn ($json) => 
                        $json->where('id', 1)
                             ->where('name', 'Comestibles')
                             ->etc()
                )
            );
    }

    /** @test */
    function it_can_show_only_one_section()
    {
        Section::factory()->create(['name' => 'Comestibles']);
        $section = Section::factory()->create(['name' => 'Deportes']);

        $response = $this->get("/api/sections/$section->id");

        $response
            ->assertJsonCount(1)
            ->assertJson([0 => [
                'id' => 2,
                'name' => 'Deportes',
            ]]);
    }

    /** @test */
    function it_can_update_a_section()
    {
        $section = Section::factory()->create(['name' => 'Comestibles']);

        $response = $this->put("api/sections/$section->id", [
            'name' => 'Deportes',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'updated',
            ]);

        $this->assertDatabaseHas('sections', [
            'name' => 'Deportes',
        ]);
    }

    /** @test */
    function it_can_delete_a_section()
    {
        $section = Section::factory()->create(['name' => 'Comestibles']);

        $response = $this->delete("/api/sections/$section->id");

        $response
            ->assertJson([
                'message' => 'Deleted',
            ]);

        $this->assertDatabaseMissing('sections', [
            'name' => 'Comestibles',
        ]);
    }
}
