<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;

class ApiResponserTest extends TestCase
{
    protected $object;

    protected function setUp(): void
    {
        parent::setUp();
        $this->object = new class {use ApiResponser; };
    }
    
    /** @test */
    function can_return_a_success_response_json()
    {
        $data = [
            ['lorem' => 'ipsum', 'foo' => 'baz']
        ];

        $json = json_encode(['data' => $data]);

        $this->assertEquals(
            $json,
            $this->object->successResponse($data)->content()
        );

        $this->assertEquals(200, $this->object->successResponse($data)->getStatusCode());
    }

    /** @test */
    function can_return_a_error_response_json()
    {
        $error = ['error' => 'This is a error', 'code' => Response::HTTP_NOT_FOUND];
        $error = json_encode($error);

        $this->assertEquals(
            $error,
            $this->object->errorResponse('This is a error', Response::HTTP_NOT_FOUND)->content()
        );

        $this->assertEquals(
            404,
            $this->object->errorResponse('This is a error', Response::HTTP_NOT_FOUND)->getStatusCode()
        );
    }
}
