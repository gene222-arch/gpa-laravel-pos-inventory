<?php

namespace Tests\Feature\Http\Controllers\Api\Products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DiscountsControllerTest extends TestCase
{
    /**
     * test
     */
    public function user_can_get_all_discounts()
    {
        $this->actingAsAdmin();

        $response = $this->get('api/discount', $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }

    /**
     * test
     */
    public function user_can_get_discounts()
    {
        $this->actingAsAdmin();

        $data = [
            'discount_id' => 1
        ];

        $response = $this->post('api/discount/detail', $data, $this->apiHeaders());

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_create_discounts()
    {
        $this->actingAsAdmin();

        $data = [
            'name' => 'RFX',
            'percentage' => 50
        ];

        $response = $this->post('api/discount', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }

    /**
     * test
     */
    public function user_can_update_discounts()
    {
        $this->actingAsAdmin();

        $data = [
            'discount_id' => 4,
            'name' => 'News',
            'percentage' => 90.00
        ];

        $response = $this->put('api/discount', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_delete_discounts()
    {
        $this->actingAsAdmin();

        $data = [
            'discount_ids' => [
                2
            ]
        ];

        $response = $this->delete('api/discount', $data, $this->apiHeaders());

        $this->getResponse($response);
    }
}
