<?php

namespace Tests\Feature\Http\Controllers\Api\Users;

use Tests\TestCase;

class CashierControllerTest extends TestCase
{
    /**
     * @test
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
