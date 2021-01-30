<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use Tests\TestCase;
use App\Models\User;

class LoginControllerTest extends TestCase
{

    /**
     * @test
     */
    public function user_can_login()
    {
        $response = $this->post('/api/login', [
            'email' => 'admin@admin.com',
            'password' => 'admin@admin.com'
        ]);

        $this->postPutResponse($response, true);
    }


    /**
     * @test
     */
    public function user_can_logout()
    {
        $accessToken = User::first()->generateToken();

        $response = $this->post('/api/logout', $this->apiAuthHeaders($accessToken));

        $this->postPutResponse($response,);
    }

}
