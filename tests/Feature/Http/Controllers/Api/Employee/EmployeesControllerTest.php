<?php

namespace Tests\Feature\Http\Controllers\Api\Employee;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeesControllerTest extends TestCase
{
    /**
     * test
     */
    public function user_can_get_all_employees()
    {
        $response = $this->get('api/employees', $this->apiHeaders());

        $this->getResponse($response);
    }


    /**
     * @test
     */
    public function user_can_get_all_employee_access_rights()
    {
        $response = $this->get('api/employees/access_rights', $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }

    /**
     * test
     */
    public function user_can_get_employees()
    {
        $data = [
            'employee_id' => 1
        ];

        $response = $this->post('api/employees/detail', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_create_employees()
    {
        $data = [
            'name' => 'YasRaj',
            'email' => 'genephillip222@gmail.com',
            'phone' => '09429007480',
            'role' => 'cashier'
        ];

        $response = $this->post('api/employees', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }

    /**
     * test
     */
    public function user_can_update_employees()
    {
        $data = [
            'employee_id' => 1,
            'name' => 'YasRaj',
            'email' => 'unique@email.com',
            'phone' => '09429007480',
            'role' => 'cashier'
        ];

        $response = $this->put('api/employees', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_delete_employees()
    {
        $data = [
            'employee_ids' => [
                1
            ]
        ];

        $response = $this->delete('api/employees', $data, $this->apiHeaders());

        $this->getResponse($response);
    }
}
