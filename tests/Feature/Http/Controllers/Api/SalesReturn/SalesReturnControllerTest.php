<?php

namespace Tests\Feature\Http\Controllers\Api\SalesReturn;

use Tests\TestCase;

class SalesReturnControllerTest extends TestCase
{
    /**
     * test
     */
    public function user_can_get_all_sales_return()
    {
        $this->actingAsAdmin();

        $response = $this->get('api/sales-returns', $this->apiHeaders());

        $this->getResponse($response);
    }

    /**
     * test
     */
    public function user_can_get_sales_return()
    {
        $this->actingAsAdmin();

        $data = [
            'sales_return_id' => 1
        ];

        $response = $this->post('api/sales-returns', $data, $this->apiHeaders());

        $this->getResponse($response);
    }


    /**
     * @test
     */
    public function user_can_create_sales_return()
    {
        $this->actingAsAdmin();

        $data = [
            'pos_id' => 1,
            'posSalesReturnDetails' => [
                [
                    'pos_details_id' => 1,
                    'product_id' => 23,
                    'defect' => 'Slightly Damaged',
                    'quantity' => 1,
                    'price' => 215.00,
                    'unit_of_measurement' => 'each',
                    'sub_total' => 15.00,
                    'discount' => 0.00,
                    'tax' => 1.80,
                    'total' => 16.80
                ],
            ]
        ];

        $response = $this->post('api/sales-returns', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }

    /**
     * test
     */
    public function user_can_update_sales_return()
    {
        $this->actingAsAdmin();

        $data = [
            'pos_sales_return_id' => 1,
            'pos_id' => 1,
            'posSalesReturnDetails' => [
                [
                    'pos_details_id' => 1,
                    'product_id' => 19,
                    'defect' => 'Slightly Damaged',
                    'quantity' => 5,
                    'price' => 200.00,
                    'unit_of_measurement' => 'pcs',
                    'sub_total' => 1,
                    'discount' => 10.00,
                    'tax' => 2.00,
                    'total' => 20.00
                ],
                [
                    'pos_details_id' => 1,
                    'product_id' => 20,
                    'defect' => 'Slightly Damaged',
                    'quantity' => 5,
                    'price' => 200.00,
                    'unit_of_measurement' => 'pcs',
                    'sub_total' => 1,
                    'discount' => 10.00,
                    'tax' => 2.00,
                    'total' => 20.00
                ],
            ]
        ];

        $response = $this->put('api/sales-returns', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_delete_sales_return()
    {
        $this->actingAsAdmin();

        $data = [

        ];

        $response = $this->delete('api/sales-returns', $data, $this->apiHeaders());

        $this->getResponse($response);
    }

    /**
     * test
     */
    public function user_can_remove_sales_return()
    {
        $this->actingAsAdmin();

        $data = [
            'pos_sales_return_ids' => [1]
        ];

        $response = $this->delete('api/sales-returns', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_remove_sales_return_items()
    {
        $this->actingAsAdmin();

        $data = [
            'pos_sales_return_id' => 1,
            'product_ids' => [
                19,
                20
            ]
        ];

        $response = $this->delete('api/sales-returns/items', $data, $this->apiHeaders());

        $this->getResponse($response);
    }
}
