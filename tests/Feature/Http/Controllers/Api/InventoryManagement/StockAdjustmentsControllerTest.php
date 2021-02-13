<?php

namespace Tests\Feature\Http\Controllers\Api\InventoryManagement;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StockAdjustmentsControllerTest extends TestCase
{

    /**
     * test
     */
    public function user_can_get_stock_adjustments()
    {
        $response = $this->get('api/stocks/stock-adjustments', $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }



    /**
     * @test
     */
    public function user_can_get_stock_adjustment()
    {
        $data = [
            'stock_adjustment_id' => 4
        ];

        $response = $this->post('api/stocks/stock-adjustment',
        $data,
        $this->apiHeaders());
        dd(json_decode($response->getContent()));


        $this->getResponse($response, 200);
    }

    /**
     * test
     */
    public function user_can_create_stock_adjustment()
    {
        $data = [
            'reason' => 'Received items',
            'stockAdjustmentDetails' => [
                [
                    'stock_id' => 1,
                    'in_stock' => 120,
                    'added_stock' => 0,
                    'removed_stock' => 0,
                    'counted_stock' => 1000,
                    'stock_after' => 1000
                ],
                [
                    'stock_id' => 2,
                    'in_stock' => 120,
                    'added_stock' => 0,
                    'removed_stock' => 0,
                    'counted_stock' => 1000,
                    'stock_after' => 1000
                ],
            ]
        ];

        $response = $this->post('api/stocks/stock-adjustments',
            $data,
            $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }

}
