<?php

namespace Tests\Feature\Http\Controllers\Api\Customer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomersControllerTest extends TestCase
{
    /**
     * test
     */
    public function user_can_get_all_customers()
    {
        $this->actingAsAdmin();

        $response = $this->get('api/customers', $this->apiHeaders());

        $this->getResponse($response);
    }

    /**
     * test
     */
    public function user_can_get_customer()
    {
        $this->actingAsAdmin();

        $data = [
            'customer_id' => 1
        ];

        $response = $this->post('api/customers/details', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


    /**
     * @test
     */
    public function user_can_create_customer()
    {
        $this->actingAsAdmin();

        $data = [
            'name' => 'Gene Phillip Artista',
            'email' => 'genephillip222@gmail.com',
            'phone' => '09264774547',
            'address' => '134 Daisy St. Brgy. Lingga',
            'city' => 'Calamba',
            'province' => 'Laguna',
            'postal_code' => '4027',
            'country' => 'Philippines',
        ];

        $response = $this->post('api/customers', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }

    /**
     * test
     */
    public function user_can_update_customer()
    {
        $this->actingAsAdmin();

        $data = [
            'customer_id' => 3,
            'customer_data' => [
                'name' => 'Gene Phillip D. Artista',
                'email' => 'genephillip222@gmail.com',
                'phone' => '09264774547',
                'address' => '134 Daisy St. Brgy. Lingga',
                'city' => 'Calamba',
                'province' => 'Laguna',
                'postal_code' => '4027',
                'country' => 'Philippines',
            ]
        ];

        $response = $this->put('api/customers', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_delete_customer()
    {
        $this->actingAsAdmin();

        $data = [
            'customer_ids' => [
                3,
            ]
        ];

        $response = $this->delete('api/customers', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }
}
