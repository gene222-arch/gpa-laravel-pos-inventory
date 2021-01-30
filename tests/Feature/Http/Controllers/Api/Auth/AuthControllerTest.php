<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{

    /**
     * @test
     */
    public function can_get_authenticated_user()
    {
        $response = $this->get('/api/authenticated-user',
        $this->apiAuthHeaders($this->generateTokenByAdmin()));

        $this->getResponse($response);
    }


    /**
     * @test
     */
    public function can_get_authenticated_user_with_roles()
    {
        $accessToken = User::first()->generateToken();

        $response = $this->get('/api/authenticated-user-roles',
        $this->apiAuthHeaders($accessToken)
        );

        $this->getResponse($response);
    }

}
