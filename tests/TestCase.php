<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;

    protected function setUp():void
    {
        parent::setUp();
        $this->actingAsAdmin();

        $this->withoutExceptionHandling();
    }


    protected function admin()
    {
        $user = new User;

        return $user->admin();
    }


    protected function generateTokenByAdmin()
    {
        return $this->admin()->generateToken();
    }


    protected function actingAsAdmin()
    {
        $this->actingAs(User::first(), 'api');
    }


    /**
     * Default headers for API
     *
     * @param [] $accessToken
     * @return array
     */
    protected function apiHeaders()
    {
        return [
            'Accept' => 'application/json',
        ];
    }


    /**
     * Default headers for API Authenticated User
     *
     * @param [] $accessToken
     * @return array
     */
    protected function apiAuthHeaders($accessToken)
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ];
    }

    /**
     * Default Success Json Structure
     *
     * @return array
     */
    protected function jsonStructure($tokenExist = false)
    {
        if ($tokenExist)
        {
            return [
                'status',
                'message',
                'data' => ['access_token', 'token_type']
            ];
        }

        return [
            'status',
            'message',
            'data',
        ];
    }

    /**
     * Undocumented function
     *
     * @param [type] $response
     * @param [type] $tokenExist
     * @return \Tests\[type] $response
     */
    protected function getResponse($response, $status = 200, $tokenExist = null)
    {
        $response
            ->assertStatus($status)
            ->assertJsonStructure($this->jsonStructure($tokenExist));
    }


    /**
     * Undocumented function
     *
     * @param [type] $response
     * @param [type] $tokenExist
     * @return \Tests\[type] $response
     */
    protected function postPutResponse($response, $tokenExist = null)
    {
        $response
            ->assertStatus(201)
            ->assertJsonStructure($this->jsonStructure($tokenExist));
    }

}
