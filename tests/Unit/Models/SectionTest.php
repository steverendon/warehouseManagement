<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class SectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_has_many_categories()
    {
        $section = new Section;

        $this->assertInstanceOf(Collection::class, $section->categories);
    }

    /** @test */
    function sections_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumns('sections', [
            'id', 'name',
        ]));
    }
}
