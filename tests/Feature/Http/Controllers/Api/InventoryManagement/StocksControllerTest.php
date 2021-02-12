<?php

namespace Tests\Feature\Http\Controllers\Api\InventoryManagement;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StocksControllerTest extends TestCase
{
    /**
     * test
     */
    public function user_can_adjust_stocks()
    {
        $this->actingAsAdmin();

        $data = [
            'reason' => 'Received items',
            'stockAdjustmentDetails' => [
                [
                    'product_id' => 19,
                    'in_stock' => 120,
                    'added_stock' => 100,
                    'updated_cost' => 120.00,
                    'stock_after' => 220
                ],
                [
                    'product_id' => 20,
                    'in_stock' => 120,
                    'added_stock' => 100,
                    'updated_cost' => 120.00,
                    'stock_after' => 220
                ],
            ]
        ];

        $response = $this->put('api/stocks/stock-adjustments',
            $data,
            $this->apiHeaders());

        $this->getResponse($response, 201);
    }

}
