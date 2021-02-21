<?php

namespace Tests\Feature\Http\Controllers\Api\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_get_all()
    {
        $response = $this->get('api/dashboard', $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }
}
