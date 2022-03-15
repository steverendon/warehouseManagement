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
        $this->json('POST', '/api/sections', [
            'name' => 'Comestibles',
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => ['id' => 1, 'name' => 'Comestibles']
        ]);

        $this->assertDatabaseHas('sections', [
            'name' => 'Comestibles',
        ]);
    }

    /** @test */
    function should_return_an_error_message_when_the_name_field_is_missing()
    {
        $this->json('POST', '/api/sections', [])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => ['The name field is required.'],
                ],
                'code' => 422   
        ]);
    }

    /** @test */
    function it_can_lists_all_registers()
    {
        Section::factory()->create(['name' => 'Comestibles']);
        Section::factory()->create(['name' => 'Deportes']);
        Section::factory()->create(['name' => 'Belleza']);

        $response = $this->get('/api/sections');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    ['id', 'name']
                ]
            ])
            ->assertJson([
                'data' => [
                    ['id' => 1, 'name' => 'Comestibles'],
                    ['id' => 2, 'name' => 'Deportes'],
                    ['id' => 3, 'name' => 'Belleza']
                ]
            ]);
    }

    /** @test */
    function it_can_show_only_one_section()
    {
        Section::factory()->create(['name' => 'Comestibles']);
        $section = Section::factory()->create(['name' => 'Deportes']);
        Section::factory()->create(['name' => 'Belleza']);

        $response = $this->get("/api/sections/{$section->id}");

        $response
            ->assertJsonStructure([
                'data' => ['id', 'name']
            ])
            ->assertJson([
                'data' => ['id' => 2, 'name' => 'Deportes']
            ]);
    }

    /** @test */
    function it_can_update_a_section()
    {
        $section = Section::factory()->create(['name' => 'Comestibles']);

        $response = $this->json('PUT', "api/sections/{$section->id}", [
            'name' => 'Deportes'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => ['id' => $section->id, 'name' => 'Deportes'],
            ]);

        $this->assertDatabaseHas('sections', [
            'name' => 'Deportes',
        ]);

        $this->assertDatabaseMissing('sections', [
            'name' => 'Comestibles',
        ]);
    }

        /** @test */
        function it_should_return_a_error_message_when_update_without_name()
        {
            $section = Section::factory()->create(['name' => 'Comestibles']);
    
            $response = $this->json('PUT', "api/sections/{$section->id}", []);
    
            $response
                ->assertStatus(422)
                ->assertJson([
                    'errors' => [
                        'name' => ['The name field is required.']
                    ],
                ]);
        }

    /** @test */
    function it_can_delete_a_section()
    {
        $section = Section::factory()->create(['name' => 'Comestibles']);

        $response = $this->json('DELETE', "/api/sections/$section->id", []);

        $response
            ->assertStatus(204);

        $this->assertDatabaseMissing('sections', [
            'name' => 'Comestibles',
        ]);
    }
}
