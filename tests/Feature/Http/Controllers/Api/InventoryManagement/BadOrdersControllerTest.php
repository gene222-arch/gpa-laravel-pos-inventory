<?php

namespace Tests\Feature\Http\Controllers\Api\InventoryManagement;

use Tests\TestCase;

class BadOrdersControllerTest extends TestCase
{
    /**
     * test
     */
    public function user_can_get_all_bad_orders()
    {
        $this->actingAsAdmin();

        $response = $this->get('api/bad-orders', $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }

    /**
     * test
     */
    public function user_can_get_bad_order()
    {
        $this->actingAsAdmin();

        $data = [
            'bad_order_id' => 5,
        ];

        $response = $this->post('api/bad-orders/details', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


    /**
     * @test
     */
    public function user_can_create_bad_orders()
    {
        $this->actingAsAdmin();

        $data = [
            'purchase_order_id' => 1,
            'badOrderDetails' => [
                [
                    'purchase_order_details_id' => 1,
                    'product_id' => 19,
                    'defect' => 'Damaged',
                    'quantity' => 10,
                    'price' => 20.00,
                    'unit_of_measurement' => 'pcs',
                    'amount' => 2000.00
                ],
                [
                    'purchase_order_details_id' => 2,
                    'product_id' => 20,
                    'defect' => 'Damaged',
                    'quantity' => 10,
                    'price' => 20.00,
                    'unit_of_measurement' => 'pcs',
                    'amount' => 2000.00
                ],
            ]
        ];

        $response = $this->post('api/bad-orders', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_update_bad_orders()
    {
        $this->actingAsAdmin();

        $data = [
            'bad_order_id' => 20,
            'purchase_order_id' => 1,
            'badOrderDetails' => [
                [
                    'purchase_order_details_id' => 1,
                    'product_id' => 19,
                    'defect' => 'Medyo Damaged',
                    'quantity' => 10,
                    'price' => 20.00,
                    'unit_of_measurement' => 'pcs',
                    'amount' => 2000.00
                ],
                [
                    'purchase_order_details_id' => 6,
                    'product_id' => 20,
                    'defect' => 'Medyo Damaged',
                    'quantity' => 10,
                    'price' => 20.00,
                    'unit_of_measurement' => 'pcs',
                    'amount' => 2000.00
                ]
            ]
        ];

        $response = $this->put('api/bad-orders', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_delete_bad_orders()
    {
        $this->actingAsAdmin();

        $data = [
            'bad_order_ids' => [
                4,
                5
            ]
        ];

        $response = $this->delete('api/bad-orders', $data, $this->apiHeaders());

        $this->getResponse($response);
    }
}
