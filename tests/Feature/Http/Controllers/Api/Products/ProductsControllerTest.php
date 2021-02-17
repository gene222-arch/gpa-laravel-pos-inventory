<?php

namespace Tests\Feature\Http\Controllers\Api\Products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsControllerTest extends TestCase
{

    /**
     * test
     */
    public function user_can_get_products()
    {
        $this->actingAsAdmin();

        $response = $this->get('/api/products', $this->apiHeaders());

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_store_product()
    {
        $this->actingAsAdmin();

        $data = [
            'product' => [
                'sku' => 'product11111',
                'barcode' => 'product11111',
                'name' => 'Product111',
                'image' => null,
                'category' => 20,
                'sold_by' => 'each',
                'price' => 1000.50,
                'cost' => 1000.50,
            ],
            'stock' => [
                'supplier_id' => 3,
                'in_stock' => 100000,
                'minimum_reorder_level' => 100,
                'default_purchase_costs' => 100.50
            ]
        ];

        $response = $this->post('api/products',$data,$this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }


    /**
     * @test
     */
    public function user_can_update_product()
    {
        $this->actingAsAdmin();

        $data = [
            'product' => [
                'product_id' => 26,
                'data' => [
                    'sku' => 'SKUUUU2UU22',
                    'barcode' => 'SKUU2UUUU22',
                    'name' => 'New SKU2PRODUCT',
                    'image' => null,
                    'category' => 20,
                    'sold_by' => 'each',
                    'price' => 100.00,
                    'cost' => 100.00,
                ]
            ],
            'stock' => [
                'data' => [
                    'supplier_id' => 3,
                    'in_stock' => 1,
                    'stock_in' => 1,
                    'stock_out' => 2,
                    'minimum_reorder_level' => 22,
                    'default_purchase_costs' => 22.00
                ]
            ]
        ];

        $response = $this->put('api/products', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_delete_product()
    {
        $this->actingAsAdmin();

        $data = [
            'product_ids' => [18]
        ];

        $response = $this->delete('api/products',$data, $this->apiHeaders());

        $this->getResponse($response);
    }

}
