<?php

namespace Tests\Feature\Http\Controllers\Api\Employee;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccessRightsControllerTest extends TestCase
{
    /**
     * test
     */
    public function user_can_get_all_access_rights()
    {
        $response = $this->get('api/access-rights', $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }

    /**
     * test
     */
    public function user_can_get_access_rights()
    {
        $data = [
            'role_id' => 1
        ];

        $response = $this->post('api/access-rights/details', $data, $this->apiHeaders());

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_create_access_rights()
    {
        $data = [
            'role_name' => 'test_role',
            'back_office' => true,
            'pos' => true,
        ];

        $response = $this->post('api/access-rights', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }

    /**
     * test
     */
    public function user_can_update_access_rights()
    {
        $data = [
            'role_id' => 4,
            'role_name' => 'test_role',
            'back_office' => false,
            'pos' => false,
        ];

        $response = $this->put('api/access-rights', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_delete_access_rights()
    {
        $data = [
            'role_ids' => [
                4
            ]
        ];

        $response = $this->delete('api/access-rights', $data, $this->apiHeaders());

        $this->getResponse($response);
    }
}
