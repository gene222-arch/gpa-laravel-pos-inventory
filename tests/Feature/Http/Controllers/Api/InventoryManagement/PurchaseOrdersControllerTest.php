<?php

namespace Tests\Feature\Http\Controllers\Api\InventoryManagement;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseOrdersControllerTest extends TestCase
{
    /**
     * * test
     */
    public function user_can_get_purchase_orders()
    {


        $response = $this->get('api/purchase-order', $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }



    /**
     * * test
     */
    public function user_can_get_purchase_order_detail()
    {


        $data = [
            'purchase_order_id' => 4
        ];

        $response = $this->post('api/purchase-order/purchase-order-detail',
        $data,
        $this->apiHeaders());

        $this->getResponse($response, 200);
    }



    /**
     * test
     */
    public function user_can_purchase_order()
    {


        $data = [
            'supplier_id' => 3,
            'purchase_order_date' => Carbon::now(),
            'expected_delivery_date' => Carbon::now()->addDays(10),
            'items' => [
                [
                    'product_id' => 19,
                    'ordered_quantity' => 100,
                    'purchase_cost' => 1200.00,
                    'amount' => 100.00,
                ],
                [
                    'product_id' => 20,
                    'ordered_quantity' => 150,
                    'purchase_cost' => 1200.00,
                    'amount' => 100.00,
                ],
            ],
        ];

        $response = $this->post('api/purchase-order', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }



    /**
     * test
     */
    public function user_can_upsert_purchase_order()
    {


        $data = [
            'purchase_order_id' => 8,
            'expected_delivery_date' => now(),
            'items' =>
            [
                [
                    'product_id' => 19,
                    'ordered_quantity' => 131,
                    'remaining_ordered_quantity' => 131,
                    'purchase_cost' => 131.00,
                    'amount' => 131.00,
                ],
                [
                    'product_id' => 22,
                    'ordered_quantity' => 88,
                    'remaining_ordered_quantity' => 88,
                    'purchase_cost' => 88.00,
                    'amount' => 88.00,
                ],
            ]
        ];

        $response = $this->put('api/purchase-order', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * @test
     */
    public function user_can_mail_supplier()
    {
        $data = [
            'purchase_order_id' => 1,
            'supplier_id' => 3,
            'subject' => 'Subject',
            'note' => 'Note'
        ];

        $response = $this->post('api/purchase-order/mail-supplier', $data,
        $this->apiHeaders());

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_mark_all_purchased_order_as_received()
    {
        $data = [
            'purchase_order_id' => 1,
            'product_ids' => [
                19,
                20
            ]
        ];

        $response = $this->put('api/purchase-order/mark-all-as-received', $data,
        $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_receive_purchase_order()
    {


        $data = [
            'supplier_id' => 3,
            'purchase_order_id' => 8,
            'items_received_quantities' =>
            [
                [
                    'purchase_order_details_id' => 1,
                    'product_id' => 19,
                    'received_quantity' => 20,
                ],
            ]
        ];

        $response = $this->put('api/purchase-order/to-receive',
        $data,
        $this->apiHeaders()
        );

        $this->getResponse($response, 201);
    }



    /**
     * test
     */
    public function user_can_delete_purchase_order()
    {


        $data = [
            'purchase_order_id' => [6],
        ];

        $response = $this->delete('api/purchase-order', $data, $this->apiHeaders());

        $this->getResponse($response, 200);
    }


    /**
     * test
     */
    public function user_can_delete_purchase_order_details_product()
    {


        $data = [
            'purchase_order_id' => 5,
            'product_ids' => [
                19
            ]
        ];

        $response = $this->delete('api/purchase-order/products',
        $data,
        $this->apiHeaders());

        $this->getResponse($response, 200);
    }
}

